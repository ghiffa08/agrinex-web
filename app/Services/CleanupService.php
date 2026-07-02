<?php

namespace App\Services;

use App\Models\GetdataLog;
use App\Models\IrrigateLog;
use App\Models\SensorNodeData;
use App\Models\SensorWeatherData;
use App\Models\NodeLog;
use App\Models\ValveLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanupService
{
    public function cleanup($days = 90)
    {
        $cutoffDate = now()->subDays($days);
        
        Log::info('Starting cleanup', [
            'days' => $days,
            'cutoff_date' => $cutoffDate
        ]);

        return DB::transaction(function () use ($cutoffDate) {
            $result = [
                'cutoff_date' => $cutoffDate->toDateTimeString(),
                'deleted_counts' => []
            ];

            // Delete old sensor_node_data
            $deletedNodeData = SensorNodeData::where('created_at', '<', $cutoffDate)->delete();
            $result['deleted_counts']['sensor_node_data'] = $deletedNodeData;

            // Delete old sensor_weather_data
            $deletedWeatherData = SensorWeatherData::where('created_at', '<', $cutoffDate)->delete();
            $result['deleted_counts']['sensor_weather_data'] = $deletedWeatherData;

            // Delete old node_logs
            $deletedNodeLogs = NodeLog::where('created_at', '<', $cutoffDate)->delete();
            $result['deleted_counts']['node_logs'] = $deletedNodeLogs;

            // Delete old valve_logs
            $deletedValveLogs = ValveLog::where('created_at', '<', $cutoffDate)->delete();
            $result['deleted_counts']['valve_logs'] = $deletedValveLogs;

            // Delete old getdata_logs
            $deletedGetdataLogs = GetdataLog::where('created_at', '<', $cutoffDate)->delete();
            $result['deleted_counts']['getdata_logs'] = $deletedGetdataLogs;

            // Delete old irrigate_logs
            $deletedIrrigateLogs = IrrigateLog::where('created_at', '<', $cutoffDate)->delete();
            $result['deleted_counts']['irrigate_logs'] = $deletedIrrigateLogs;

            $result['total_deleted'] = array_sum($result['deleted_counts']);

            Log::info('Cleanup completed', $result);

            return $result;
        });
    }

    public function cleanupOrphaned()
    {
        Log::info('Starting orphaned records cleanup');

        return DB::transaction(function () {
            $result = [
                'deleted_counts' => []
            ];

            // Delete sensor data without getdata logs
            $deletedOrphanedSensorData = SensorNodeData::whereNotExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('getdata_logs')
                    ->whereRaw('getdata_logs.sesi_id_getdata = sensor_node_data.sesi_id_getdata');
            })->delete();
            $result['deleted_counts']['orphaned_sensor_node_data'] = $deletedOrphanedSensorData;

            // Delete weather data without getdata logs
            $deletedOrphanedWeatherData = SensorWeatherData::whereNotExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('getdata_logs')
                    ->whereRaw('getdata_logs.sesi_id_getdata = sensor_weather_data.sesi_id_getdata');
            })->delete();
            $result['deleted_counts']['orphaned_sensor_weather_data'] = $deletedOrphanedWeatherData;

            // Delete valve logs without irrigate logs
            $deletedOrphanedValveLogs = ValveLog::whereNotExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('irrigate_logs')
                    ->whereRaw('irrigate_logs.sesi_id_irrigate = valve_logs.sesi_id_irrigate');
            })->delete();
            $result['deleted_counts']['orphaned_valve_logs'] = $deletedOrphanedValveLogs;

            $result['total_deleted'] = array_sum($result['deleted_counts']);

            Log::info('Orphaned records cleanup completed', $result);

            return $result;
        });
    }

    public function optimizeTables()
    {
        $tables = [
            'getdata_logs',
            'irrigate_logs',
            'sensor_node_data',
            'sensor_weather_data',
            'node_logs',
            'valve_logs'
        ];

        $result = [];

        foreach ($tables as $table) {
            DB::statement("OPTIMIZE TABLE `$table`");
            $result[$table] = 'optimized';
        }

        Log::info('Tables optimized', $result);

        return $result;
    }
}
