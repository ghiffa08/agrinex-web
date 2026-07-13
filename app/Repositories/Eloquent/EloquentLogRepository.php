<?php

namespace App\Repositories\Eloquent;

use App\Models\DeviceLog;
use App\Repositories\Contracts\LogRepositoryInterface;

class EloquentLogRepository implements LogRepositoryInterface
{
    public function createDeviceLog(array $data)
    {
        return DeviceLog::create($data);
    }

    public function createNodeLog(array $data)
    {
        // Map legacy node_logs fields to new device_logs schema
        return DeviceLog::create([
            'device_id' => $data['node_id'],
            'rssi_dbm' => $data['rssi_dbm'] ?? null,
            'snr_db' => $data['snr_db'] ?? null,
            'signal_quality' => $data['signal_quality'] ?? null,
            'is_active' => ($data['status'] ?? 'success') === 'success',
            'session_type' => $data['type_sesi'] ?? 'getdata',
            'session_ref_id' => $data['sesi_id'] ?? null,
            'remarks' => $data['keterangan'] ?? null,
            'logged_at' => $data['waktu'] ?? now(),
        ]);
    }

    public function getLatestDeviceLogs()
    {
        return DeviceLog::with('device')
            ->orderBy('logged_at', 'desc')
            ->limit(50)
            ->get();
    }

    public function getLatestForNode($nodeId)
    {
        // Updated to use DeviceLog instead of legacy NodeLog
        return DeviceLog::where('device_id', $nodeId)
            ->latest('logged_at')
            ->first();
    }

    public function getLatestLogsForDevices()
    {
        return DeviceLog::select('device_logs.*')
            ->from('device_logs')
            ->join(
                \DB::raw('(SELECT device_id, MAX(logged_at) as max_logged_at FROM device_logs GROUP BY device_id) as latest'),
                function ($join) {
                    $join->on('device_logs.device_id', '=', 'latest.device_id')
                        ->on('device_logs.logged_at', '=', 'latest.max_logged_at');
                }
            )
            ->get();
    }

    public function getLogsForDevice($deviceId, $filters, $limit)
    {
        $query = DeviceLog::where('device_id', $deviceId);

        if (!empty($filters['start_date'])) {
            $query->where('logged_at', '>=', $filters['start_date']);
        }

        return $query->orderBy('logged_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
