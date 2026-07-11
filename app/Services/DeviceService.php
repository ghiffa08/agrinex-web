<?php

namespace App\Services;

use App\Models\SensorNodeData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DeviceService
{
    /**
     * Get chart data (last 100 readings) for a specific node.
     */
    public function getChartData(int|string $deviceId): array
    {
        return Cache::remember("chart_data_{$deviceId}", 30, function () use ($deviceId) {
            $rows = SensorNodeData::where('node_id', $deviceId)
                ->orderBy('received_at', 'desc')
                ->limit(100)
                ->get(['received_at', 'temp_c', 'soil_pct'])
                ->sortBy('received_at')
                ->values();

            $labels      = [];
            $temperature = [];
            $soilMoisture = [];

            foreach ($rows as $row) {
                $labels[]       = Carbon::parse($row->received_at)->format('H:i');
                $temperature[]  = (float) $row->temp_c;
                $soilMoisture[] = (float) $row->soil_pct;
            }

            return [
                'labels'   => $labels,
                'datasets' => [
                    'temperature'  => $temperature,
                    'soil_moisture' => $soilMoisture,
                ],
            ];
        });
    }

    /**
     * Get irrigation sessions for a specific device.
     * Returns empty until irrigate_logs table is properly wired.
     */
    public function getIrrigationSessions(int|string $deviceId): array
    {
        return [
            'sessions' => [],
            'summary'  => null,
        ];
    }

    /**
     * Get usage history (last 7 days) for a specific device.
     * Returns empty until irrigate_logs table is properly wired.
     */
    public function getUsageHistory(int|string $deviceId): array
    {
        return [
            'history' => [],
        ];
    }
}
