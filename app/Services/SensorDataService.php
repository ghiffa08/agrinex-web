<?php

namespace App\Services;

use App\Models\GetdataLog;
use App\Models\SensorWeatherData;
use App\Models\SensorNodeData;
use App\Models\NodeLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SensorDataService
{
    public function processSensorData(array $requestData)
    {
        $metadata = $requestData['metadata'];
        $data = $requestData['data'];
        $statistics = $requestData['statistics'];
        
        $sesiId = $metadata['sesi_id_getdata'];

        return DB::transaction(function () use ($data, $sesiId, $statistics) {
            $insertedCounts = [];

            // 1. Insert getdata_logs
            if (!empty($data['getdata_logs'])) {
                foreach ($data['getdata_logs'] as $log) {
                    GetdataLog::create(array_merge($log, [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]));
                }
                $insertedCounts['getdata_logs'] = count($data['getdata_logs']);
            }

            // 2. Insert sensor_weather_data
            if (!empty($data['sensor_weather_data'])) {
                foreach ($data['sensor_weather_data'] as $weather) {
                    SensorWeatherData::create(array_merge($weather, [
                        'sesi_id_getdata' => $sesiId
                    ]));
                }
                $insertedCounts['sensor_weather_data'] = count($data['sensor_weather_data']);
            }

            // 3. Insert sensor_node_data
            if (!empty($data['sensor_node_data'])) {
                foreach ($data['sensor_node_data'] as $node) {
                    SensorNodeData::create(array_merge($node, [
                        'sesi_id_getdata' => $sesiId
                    ]));
                }
                $insertedCounts['sensor_node_data'] = count($data['sensor_node_data']);
            }

            // 4. Insert node_logs
            if (!empty($data['node_logs'])) {
                foreach ($data['node_logs'] as $nodeLog) {
                    NodeLog::create($nodeLog);
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
        $query = SensorNodeData::query();

        if (!empty($filters['sesi_id'])) {
            $query->where('sesi_id_getdata', $filters['sesi_id']);
        }

        if (!empty($filters['node_id'])) {
            $query->where('node_id', $filters['node_id']);
        }

        $orderBy = $filters['order_by'] ?? 'received_at';
        $orderDir = $filters['order_dir'] ?? 'desc';
        $limit = $filters['limit'] ?? 100;

        return $query->orderBy($orderBy, $orderDir)
            ->limit($limit)
            ->get();
    }

    public function getStatistics($sesiId = null)
    {
        $query = SensorNodeData::query();

        if ($sesiId) {
            $query->where('sesi_id_getdata', $sesiId);
        }

        return [
            'total_readings' => $query->count(),
            'avg_temperature' => round($query->avg('temp_c'), 2),
            'avg_soil_moisture' => round($query->avg('soil_pct'), 2),
            'avg_voltage' => round($query->avg('voltage_v'), 2),
            'min_temperature' => $query->min('temp_c'),
            'max_temperature' => $query->max('temp_c'),
            'nodes_count' => $query->distinct('node_id')->count('node_id'),
        ];
    }

    public function getLatestReadings($nodeId = null)
    {
        $query = SensorNodeData::query();

        if ($nodeId) {
            return $query->where('node_id', $nodeId)
                ->latest('received_at')
                ->first();
        }

        // Get latest reading for each node
        return $query->select('node_id')
            ->selectRaw('MAX(received_at) as latest_reading')
            ->groupBy('node_id')
            ->get()
            ->map(function ($item) {
                return SensorNodeData::where('node_id', $item->node_id)
                    ->where('received_at', $item->latest_reading)
                    ->first();
            });
    }
}