<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GetdataLog;
use App\Models\IrrigateLog;
use App\Models\SensorNodeData;
use App\Models\SensorWeatherData;
use App\Models\NodeLog;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitorController extends Controller
{
    /**
     * Get API statistics
     * GET /api/v1/monitor/stats
     */
    public function getStats(Request $request)
    {
        try {
            $stats = [
                'database' => [
                    'status' => 'connected',
                    'type' => config('database.default'),
                    'name' => config('database.connections.mysql.database')
                ],
                'tables' => [
                    'getdata_logs' => GetdataLog::count(),
                    // 'irrigate_logs' => IrrigateLog::count(),
                    'sensor_node_data' => SensorNodeData::count(),
                    'sensor_weather_data' => SensorWeatherData::count(),
                    'node_logs' => NodeLog::count(),
                ],
                'latest_sessions' => [
                    'getdata' => GetdataLog::first(),
                    // 'irrigate' => IrrigateLog::latest()->first(),
                ],
                'today' => [
                    // format date (2025-10-15 12:42:03)
                    // 'date' => date('Y-m-d H:i:s'),
                    'getdata_sessions' => GetdataLog::whereDate('waktu_mulai', date('Y-m-d H:i:s'))->count(),
                    // 'irrigate_sessions' => IrrigateLog::whereDate('created_at', date('Y-m-d H:i:s'))->count(),
                    'sensor_readings' => SensorNodeData::whereDate('received_at', date('Y-m-d H:i:s'))->count(),
                ],
                'server' => [
                    'php_version' => PHP_VERSION,
                    'laravel_version' => app()->version(),
                    'timezone' => config('app.timezone'),
                    'environment' => config('app.env'),
                ]
            ];

            return response()->json([
                'success' => true,
                'statistics' => $stats,
                'timestamp' => now()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics: ' . $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * Get recent logs
     * GET /api/v1/monitor/logs
     */
    public function getLogs(Request $request)
    {
        try {
            $type = $request->query('type', 'all'); // getdata, irrigate, all
            $limit = $request->query('limit', 50);

            $logs = [];

            if ($type === 'getdata' || $type === 'all') {
                $logs['getdata'] = GetdataLog::with(['sensorNodeData', 'nodeLogs'])
                    ->limit($limit)
                    ->get();
            }

            // if ($type === 'irrigate' || $type === 'all') {
            //     $logs['irrigate'] = IrrigateLog::with(['valveLogs', 'nodeLogs'])
            //         ->limit($limit)
            //         ->get();
            // }

            return response()->json([
                'success' => true,
                'logs' => $logs,
                'timestamp' => now()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve logs: ' . $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * Check system health
     * GET /api/v1/monitor/health
     */
    public function health()
    {
        try {
            $health = [
                'status' => 'healthy',
                'checks' => []
            ];

            // Database check
            try {
                DB::connection()->getPdo();
                $health['checks']['database'] = [
                    'status' => 'ok',
                    'message' => 'Database connection successful'
                ];
            } catch (\Exception $e) {
                $health['status'] = 'unhealthy';
                $health['checks']['database'] = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }

            // Storage check
            $storagePath = storage_path('app');
            $health['checks']['storage'] = [
                'status' => is_writable($storagePath) ? 'ok' : 'error',
                'writable' => is_writable($storagePath),
                'path' => $storagePath
            ];

            // Logs check
            $logsPath = storage_path('logs');
            $health['checks']['logs'] = [
                'status' => is_writable($logsPath) ? 'ok' : 'error',
                'writable' => is_writable($logsPath),
                'path' => $logsPath
            ];

            return response()->json([
                'success' => true,
                'health' => $health,
                'timestamp' => now()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Health check failed: ' . $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * Get node status
     * GET /api/v1/monitor/nodes
     */
    public function nodes(Request $request)
    {
        try {
            $sesiId = $request->query('sesi_id');
            
            $query = SensorNodeData::select('node_id')
                ->selectRaw('MAX(received_at) as last_seen')
                ->selectRaw('COUNT(*) as total_readings')
                ->selectRaw('AVG(voltage_v) as avg_voltage')
                ->selectRaw('AVG(temp_c) as avg_temperature')
                ->selectRaw('AVG(soil_pct) as avg_soil_moisture')
                ->groupBy('node_id')
                ->orderBy('node_id');

            if ($sesiId) {
                $query->where('sesi_id_getdata', $sesiId);
            }

            $nodes = $query->get();

            return response()->json([
                'success' => true,
                'nodes' => $nodes,
                'count' => count($nodes),
                'timestamp' => now()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve node status: ' . $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }
}
