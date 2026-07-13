<?php

namespace App\Services;

use App\Repositories\Contracts\DeviceRepositoryInterface;
use App\Repositories\Contracts\SensorDataRepositoryInterface;
use App\Repositories\Contracts\WeatherDataRepositoryInterface;
use App\Repositories\Contracts\SessionRepositoryInterface;
use App\Repositories\Contracts\LogRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SensorDataService
{
    protected $deviceRepo;
    protected $sensorRepo;
    protected $weatherRepo;
    protected $sessionRepo;
    protected $logRepo;
    protected CacheService $cacheService;

    public function __construct(
        DeviceRepositoryInterface $deviceRepo,
        SensorDataRepositoryInterface $sensorRepo,
        WeatherDataRepositoryInterface $weatherRepo,
        SessionRepositoryInterface $sessionRepo,
        LogRepositoryInterface $logRepo,
        CacheService $cacheService
    ) {
        $this->deviceRepo = $deviceRepo;
        $this->sensorRepo = $sensorRepo;
        $this->weatherRepo = $weatherRepo;
        $this->sessionRepo = $sessionRepo;
        $this->logRepo = $logRepo;
        $this->cacheService = $cacheService;
    }

    public function processSensorData(array $requestData)
    {
        $metadata = $requestData['metadata'];
        $data = $requestData['data'];
        $statistics = $requestData['statistics'];
        
        $sesiId = $metadata['sesi_id_getdata'];

        return DB::transaction(function () use ($data, $sesiId, $statistics) {
            $insertedCounts = [];

            // 1. Insert getdata_logs (create DataSession first to get ID)
            $session = null;
            if (!empty($data['getdata_logs'])) {
                foreach ($data['getdata_logs'] as $log) {
                    $session = $this->sessionRepo->createGetdataLog(array_merge($log, [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]));

                    // Auto-register master node if it doesn't exist
                    if (isset($log['node_id'])) {
                        $this->deviceRepo->firstOrCreateNode(
                            ['node_id' => $log['node_id']],
                            [
                                'group' => 'A',
                                'kode_perlakuan' => 'P' . $log['node_id'],
                                'lokasi' => 'Otomatis dari API',
                                'keterangan' => 'Node ' . $log['node_id'] . ' didaftarkan otomatis'
                            ]
                        );
                    }
                }
                $insertedCounts['getdata_logs'] = count($data['getdata_logs']);
            }

            // Get session ID (auto-increment primary key)
            $sessionId = $session ? $session->id : null;

            // 2. Insert weather_data
            if (!empty($data['sensor_weather_data']) && $sessionId) {
                foreach ($data['sensor_weather_data'] as $weather) {
                    $this->weatherRepo->createWeatherRecord([
                        'data_session_id' => $sessionId, // Use auto-increment ID
                        'device_id' => $weather['node_id'] ?? 0,
                        'temp_c' => $weather['temp_dht'] ?? null,
                        'humidity_pct' => $weather['humidity'] ?? null,
                        'light_lux' => $weather['light'] ?? null,
                        'voltage_v' => $weather['voltage'] ?? null,
                        'current_ma' => $weather['arus'] ?? $weather['current'] ?? null,
                        'power_mw' => $weather['power'] ?? null,
                    ]);
                }
                $insertedCounts['weather_data'] = count($data['sensor_weather_data']);
            }

            // 3. Insert sensor_data
            if (!empty($data['sensor_node_data']) && $sessionId) {
                foreach ($data['sensor_node_data'] as $node) {
                    $this->sensorRepo->createSensorRecord([
                        'data_session_id' => $sessionId, // Use auto-increment ID
                        'device_id' => $node['node_id'] ?? null,
                        'voltage_v' => $node['voltage_v'] ?? null,
                        'battery_pct' => $node['battery_pct'] ?? null,
                        'current_ma' => $node['current_ma'] ?? null,
                        'power_mw' => $node['power_mw'] ?? null,
                        'temperature' => $node['temp_c'] ?? null,
                        'soil_moisture' => $node['soil_pct'] ?? null,
                        'soil_adc' => $node['soil_adc'] ?? null,
                        'ts_counter' => $node['ts_counter'] ?? null,
                    ]);

                    // Auto-register master node if it doesn't exist
                    $this->deviceRepo->firstOrCreateNode(
                        ['node_id' => $node['node_id']],
                        [
                            'group' => 'A',
                            'kode_perlakuan' => 'P' . $node['node_id'],
                            'lokasi' => 'Otomatis dari API',
                            'keterangan' => 'Node ' . $node['node_id'] . ' didaftarkan otomatis'
                        ]
                    );
                }
                $insertedCounts['sensor_data'] = count($data['sensor_node_data']);
            }

            // 4. Insert node_logs (device_logs)
            if (!empty($data['node_logs'])) {
                foreach ($data['node_logs'] as $nodeLog) {
                    // Map legacy field names to new schema
                    $this->logRepo->createNodeLog([
                        'node_id' => $nodeLog['node_id'],
                        'sesi_id' => $sesiId,
                        'rssi_dbm' => $nodeLog['rssi_dbm'] ?? null,
                        'snr_db' => $nodeLog['snr_db'] ?? null,
                        'signal_quality' => $nodeLog['signal_quality'] ?? null,
                        'status' => $nodeLog['status'] ?? 'success',
                        'type_sesi' => 'getdata',
                        'keterangan' => $nodeLog['keterangan'] ?? null,
                        'waktu' => $nodeLog['waktu'] ?? now(),
                    ]);
                }
                $insertedCounts['node_logs'] = count($data['node_logs']);
            }

            Log::info('Data inserted successfully', [
                'sesi_id' => $sesiId,
                'counts' => $insertedCounts
            ]);

            return [
                'sesi_id_getdata' => $sesiId,
                'inserted_records' => $insertedCounts,
                'total_inserted' => array_sum($insertedCounts),
                'node_completeness' => $statistics['node_status']['completeness_percentage'] ?? 'N/A'
            ];
        });
    }

    public function getSensorData($filters = [])
    {
        return $this->sensorRepo->getHistory($filters, $filters['limit'] ?? 100);
    }

    public function getStatistics($sesiId = null)
    {
        return $this->sensorRepo->getStatistics($sesiId);
    }

    public function getLatestReadings($nodeId = null)
    {
        if ($nodeId) {
            return $this->cacheService->remember(
                "sensor_latest_{$nodeId}",
                CacheService::TTL_SHORT,
                fn() => $this->sensorRepo->getLatestForNode($nodeId)
            );
        }
        return $this->cacheService->remember(
            'sensor_latest_all',
            CacheService::TTL_SHORT,
            fn() => $this->sensorRepo->getLatestForDevices()
        );
    }

    public function getLatestWeatherData()
    {
        return $this->cacheService->remember(
            CacheService::KEY_DASHBOARD_WEATHER,
            CacheService::TTL_SHORT,
            fn() => $this->weatherRepo->getLatestWeatherData()
        );
    }
}