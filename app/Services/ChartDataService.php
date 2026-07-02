<?php

namespace App\Services;

use App\Models\GetdataLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ChartDataService
{
    /**
     * Get sensor sessions based on requested timeframe/limit
     */
    public function getSessions($days, $limit)
    {
        $cacheKey = "chart_data_sessions_days_{$days}_limit_{$limit}";

        return Cache::remember($cacheKey, 300, function () use ($days, $limit) {
            if ($days) {
                $startTime = Carbon::now()->subDays($days);
                $sessions = GetdataLog::where('waktu_mulai', '>=', $startTime)
                    ->with(['sensorWeatherData', 'sensorNodeData'])
                    ->orderBy('waktu_mulai', 'asc')
                    ->get();
            } elseif ($limit) {
                $sessions = GetdataLog::with(['sensorWeatherData', 'sensorNodeData'])
                    ->orderBy('waktu_mulai', 'desc')
                    ->limit($limit)
                    ->get()
                    ->reverse();
                    
                $startTime = $sessions->first() ? Carbon::parse($sessions->first()->waktu_mulai) : Carbon::now();
            } else {
                $startTime = Carbon::now()->subDays(7);
                $sessions = GetdataLog::where('waktu_mulai', '>=', $startTime)
                    ->with(['sensorWeatherData', 'sensorNodeData'])
                    ->orderBy('waktu_mulai', 'asc')
                    ->get();
            }

            return [
                'sessions' => $sessions,
                'start_time' => $startTime
            ];
        });
    }
}
