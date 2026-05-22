<?php

namespace App\Services;

use App\Models\IrrigateLog;
use App\Models\ValveLog;
use App\Models\NodeLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IrrigationService
{
    public function processIrrigationData(array $requestData)
    {
        $metadata = $requestData['metadata'];
        $data = $requestData['data'];

        $sesiId = $metadata['sesi_id_irrigate'];

        return DB::transaction(function () use ($data, $sesiId) {
            $insertedCounts = [];

            // 1. Insert irrigate_logs
            if (!empty($data['irrigate_logs'])) {
                foreach ($data['irrigate_logs'] as $log) {
                    // Map field names to match database structure
                    $nextId = IrrigateLog::max('id') + 1;

                    $mappedLog = [
                        'id' => $nextId,  // Add this
                        'sesi_id_irrigate' => $log['sesi_id_irrigate'] ?? $sesiId,
                        'waktu_mulai' => $log['waktu_mulai'] ?? now(),
                        'waktu_akhir' => $log['waktu_akhir'] ?? $log['waktu_selesai'] ?? null,
                        'node_sukses' => $log['node_sukses'] ?? 0,
                        'node_gagal' => $log['node_gagal'] ?? 0,
                        'valve_on_akhir' => $log['valve_on_akhir'] ?? 0,
                    ];
                    IrrigateLog::create($mappedLog);
                }
                $insertedCounts['irrigate_logs'] = count($data['irrigate_logs']);
            }

            // 2. Insert valve_logs
            if (!empty($data['valve_logs'])) {
                foreach ($data['valve_logs'] as $valve) {
                    ValveLog::create(array_merge($valve, [
                        'sesi_id_irrigate' => $sesiId
                    ]));
                }
                $insertedCounts['valve_logs'] = count($data['valve_logs']);
            }

            // 3. Insert node_logs
            if (!empty($data['node_logs'])) {
                foreach ($data['node_logs'] as $nodeLog) {
                    NodeLog::create($nodeLog);
                }
                $insertedCounts['node_logs'] = count($data['node_logs']);
            }

            Log::info('Irrigation data inserted successfully', [
                'sesi_id' => $sesiId,
                'counts' => $insertedCounts
            ]);

            return [
                'sesi_id_irrigate' => $sesiId,
                'inserted_records' => $insertedCounts,
                'total_inserted' => array_sum($insertedCounts)
            ];
        });
    }

    public function getIrrigationData($sesiId = null, $limit = 100)
    {
        $query = IrrigateLog::with(['valveLogs', 'nodeLogs']);

        if ($sesiId) {
            $query->where('sesi_id_irrigate', $sesiId);
        }

        return $query
            ->limit($limit)
            ->get();
    }

    public function getStatistics($sesiId = null)
    {
        $query = IrrigateLog::query();

        if ($sesiId) {
            $query->where('sesi_id_irrigate', $sesiId);
        }

        $totalValves = ValveLog::query();
        if ($sesiId) {
            $totalValves->whereHas('irrigateLog', function ($q) use ($sesiId) {
                $q->where('sesi_id_irrigate', $sesiId);
            });
        }

        return [
            'total_sessions' => $query->count(),
            // Successful = waktu_akhir tidak null DAN tidak ada node gagal
            'successful_sessions' => $query->whereNotNull('waktu_akhir')
                ->where('node_gagal', 0)
                ->count(),
            // Failed = waktu_akhir null ATAU ada node gagal
            'failed_sessions' => $query->where(function ($q) {
                $q->whereNull('waktu_akhir')
                    ->orWhere('node_gagal', '>', 0);
            })->count(),
            'total_valves_operated' => $totalValves->count(),
            'total_volume_ml' => $totalValves->sum('volume_air'),
            'total_volume_liters' => round($totalValves->sum('volume_air') / 1000, 2),
            'avg_duration_seconds' => round($totalValves->avg('durasi_detik'), 2),
        ];
    }

    /**
     * Process Valve OFF data (valve logs only)
     * Used when receiving valve OFF events from IoT devices
     */
    public function processValveOffData(array $requestData)
    {
        $metadata = $requestData['metadata'];
        $data = $requestData['data'];

        $nodeId = $metadata['node_id'];

        return DB::transaction(function () use ($data, $nodeId) {
            $insertedCounts = [];

            // Insert valve_logs for valve OFF events
            if (!empty($data['valve_logs'])) {
                foreach ($data['valve_logs'] as $valve) {
                    ValveLog::create($valve);
                }
                $insertedCounts['valve_logs'] = count($data['valve_logs']);
            }

            Log::info('Valve OFF data inserted successfully', [
                'node_id' => $nodeId,
                'counts' => $insertedCounts
            ]);

            return [
                'node_id' => $nodeId,
                'inserted_records' => $insertedCounts,
                'total_inserted' => array_sum($insertedCounts)
            ];
        });
    }
}
