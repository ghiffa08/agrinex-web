<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataSession;
use App\Models\IrrigationLog;
use App\Models\SensorData;
use App\Models\WeatherData;
use App\Models\DeviceLog;
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
                    'getdata_logs' => DataSession::count(),
                    'irrigate_logs' => IrrigationLog::count(),
                    'sensor_node_data' => SensorData::count(),
                    'sensor_weather_data' => WeatherData::count(),
                    'node_logs' => DeviceLog::count(),
                ],
                'latest_sessions' => [
                    'getdata' => DataSession::latest()->first(),
                    'irrigate' => IrrigationLog::latest()->first(),
                ],
                'today' => [
                    'getdata_sessions' => DataSession::whereDate('started_at', date('Y-m-d'))->count(),
                    'irrigate_sessions' => IrrigationLog::whereDate('started_at', date('Y-m-d'))->count(),
                    'sensor_readings' => SensorData::whereDate('recorded_at', date('Y-m-d'))->count(),
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
                $logs['getdata'] = DataSession::with(['sensorData'])
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get()
                    ->map(function($session) {
                        return [
                            'sesi_id_getdata' => $session->session_id,
                            'status' => $session->status,
                            'created_at' => $session->created_at,
                            'node_sukses' => $session->success_count,
                            'jumlah_node' => $session->success_count + $session->failed_count,
                        ];
                    });
            }

            if ($type === 'irrigate' || $type === 'all') {
                $logs['irrigate'] = IrrigationLog::with(['valveLogs'])
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get()
                    ->map(function($session) {
                        return [
                            'sesi_id_irrigate' => $session->session_id,
                            'status' => $session->status,
                            'created_at' => $session->created_at,
                            'valve_sukses' => $session->success_count,
                            'jumlah_valve' => $session->success_count + $session->failed_count,
                        ];
                    });
            }

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
            
            $query = SensorData::select('device_id as node_id')
                ->selectRaw('MAX(recorded_at) as last_seen')
                ->selectRaw('COUNT(*) as total_readings')
                ->selectRaw('AVG(voltage_v) as avg_voltage')
                ->selectRaw('AVG(temp_c) as avg_temperature')
                ->selectRaw('AVG(soil_pct) as avg_soil_moisture')
                ->groupBy('device_id')
                ->orderBy('device_id');

            if ($sesiId) {
                // If filtering by session ID, we need to join with data_sessions to find by session_id 
                // OR filter by data_session_id directly. The front end might still send the string session_id.
                // Assuming it sends the string `session_id`, let's do a whereHas:
                $query->whereHas('dataSession', function($q) use ($sesiId) {
                    $q->where('session_id', $sesiId);
                });
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
