<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorDataController;
use App\Http\Controllers\Api\IrrigationController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\MonitorController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\DataIngestionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// API v1
Route::prefix('v1')->group(function () {
    
    // Data Ingestion Endpoints (NEW - for IoT devices)
    Route::prefix('ingest')->group(function () {
        Route::get('/health', [DataIngestionController::class, 'healthCheck']);
        Route::post('/sensor-data', [DataIngestionController::class, 'storeSensorData']);
        Route::post('/valve-on', [DataIngestionController::class, 'storeValveOn']);
        Route::post('/valve-off', [DataIngestionController::class, 'storeValveOff']);
    });
    
    // Dashboard Endpoints (NEW - for web dashboard)
    Route::prefix('dashboard')->group(function () {
        Route::get('/devices', [DashboardApiController::class, 'getDevices']);
        Route::get('/tank', [DashboardApiController::class, 'getTank']);
        Route::get('/schedule', [DashboardApiController::class, 'getSchedule']);
        Route::get('/usage', [DashboardApiController::class, 'getUsage']);
        Route::get('/usage/daily', [DashboardApiController::class, 'getUsageDaily']);
        Route::get('/charts', [DashboardApiController::class, 'getChartData']);
        Route::get('/weather', [DashboardApiController::class, 'getWeather']);
        Route::get('/json-backup', [DashboardApiController::class, 'getJsonBackup']);
    });
    
    // Sensor Data Endpoints
    Route::prefix('sensor-data')->group(function () {
        Route::post('/', [SensorDataController::class, 'store']);
        Route::get('/', [SensorDataController::class, 'index']);
        Route::get('/statistics', [SensorDataController::class, 'statistics']);
        Route::get('/latest', [SensorDataController::class, 'latest']);
    });
    
    // Irrigation Endpoints
    Route::prefix('irrigation')->group(function () {
        Route::post('/', [IrrigationController::class, 'store']);
        Route::get('/', [IrrigationController::class, 'index']);
        Route::get('/statistics', [IrrigationController::class, 'statistics']);
    });
    
    // Export Endpoints
    Route::prefix('export')->group(function () {
        Route::get('/', [ExportController::class, 'export']);
        Route::get('/download/{filename}', [ExportController::class, 'download']);
        Route::get('/list', [ExportController::class, 'list']);
    });
    
    // Monitor Endpoints
    Route::prefix('monitor')->group(function () {
        Route::get('/stats', [MonitorController::class, 'getStats']);
        Route::get('/logs', [MonitorController::class, 'getLogs']);
        Route::get('/health', [MonitorController::class, 'health']);
        Route::get('/nodes', [MonitorController::class, 'nodes']);
    });
});

// BMKG Weather Proxy (untuk bypass CORS)
Route::get('/bmkg/forecast', function () {
    try {
        $response = \Illuminate\Support\Facades\Http::get('https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=35.78.11.1001');
        $raw = $response->json();

        // Attempt to normalize BMKG response into flat entries array
        $entries = [];

        // Case A: BMKG v2 style: data -> [ { cuaca: [ [entry,...], [entry,...] ] } ]
        if (isset($raw['data'][0]['cuaca']) && is_array($raw['data'][0]['cuaca'])) {
            foreach ($raw['data'][0]['cuaca'] as $block) {
                if (is_array($block)) {
                    foreach ($block as $entry) {
                        if (!is_array($entry)) continue;
                        $entries[] = [
                            'local_datetime' => $entry['local_datetime'] ?? ($entry['datetime'] ?? null),
                            't' => $entry['t'] ?? $entry['temperature_c'] ?? null,
                            'humidity' => $entry['h'] ?? $entry['humidity'] ?? null,
                            'rain' => $entry['tp'] ?? $entry['rain'] ?? null,
                            'weather_desc' => $entry['weather_desc'] ?? ($entry['weather'] ?? null),
                            'weather_icon' => $entry['image'] ?? ($entry['weather_icon'] ?? null),
                            'wind_speed_ms' => $entry['ws'] ?? $entry['wind_speed_ms'] ?? null,
                            'wind_dir_cardinal' => $entry['wd'] ?? ($entry['wind_dir_cardinal'] ?? null),
                            'tcc' => $entry['tcc'] ?? null,
                        ];
                    }
                }
            }
        }

        // Case B: Some proxies return entries directly
        if (empty($entries)) {
            if (is_array($raw) && count($raw) && array_values($raw) === $raw) {
                // top-level array
                $entries = $raw;
            } elseif (isset($raw['entries']) && is_array($raw['entries'])) {
                $entries = $raw['entries'];
            } elseif (isset($raw['data']) && is_array($raw['data']) && array_values($raw['data']) === $raw['data']) {
                // If data is already an array of entries, try to flatten
                $possible = [];
                foreach ($raw['data'] as $d) {
                    if (is_array($d)) $possible[] = $d;
                }
                if ($possible) $entries = $possible;
            }
        }

        // If we have normalized entries, return them under `entries` key for frontend convenience
        if (!empty($entries)) {
            return response()->json(['entries' => $entries]);
        }

        // Fallback: return raw response if normalization failed
        return response()->json($raw);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch BMKG data: ' . $e->getMessage()
        ], 500);
    }
});

// Device-specific endpoints (untuk detail pages)
Route::prefix('devices/{deviceId}')->group(function () {
    Route::get('/irrigation/sessions', function ($deviceId) {
        // Mock data - implement when irrigate_logs table available
        return response()->json([
            'success' => true,
            'data' => [],
            'note' => 'irrigate_logs table not available'
        ]);
    });
    
    Route::get('/usage-history', function ($deviceId) {
        // Mock data - implement when irrigate_logs table available
        return response()->json([
            'success' => true,
            'data' => [],
            'note' => 'irrigate_logs table not available'
        ]);
    });
});

// Legacy compatibility routes (untuk backward compatibility dengan Raspberry Pi)
Route::post('/index.php', [SensorDataController::class, 'store']);
Route::post('/api_getdata.php', [SensorDataController::class, 'store']);
Route::post('/api_irrigate.php', [IrrigationController::class, 'store']);
Route::get('/export_data.php', [ExportController::class, 'export']);

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'AgriNex API',
        'version' => '2.0',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// API Documentation
Route::get('/docs', function () {
    return response()->json([
        'service' => 'AgriNex API',
        'version' => '2.0',
        'description' => 'Smart Agriculture IoT Data Collection API',
        'endpoints' => [
            'dashboard' => [
                'GET /api/v1/dashboard/devices' => 'Get all devices with latest sensor data',
                'GET /api/v1/dashboard/tank' => 'Get water tank information',
                'GET /api/v1/dashboard/schedule' => 'Get irrigation schedule',
                'GET /api/v1/dashboard/usage' => 'Get 30-day water usage history',
                'GET /api/v1/dashboard/usage/daily' => 'Get 24-hour hourly usage',
                'GET /api/v1/dashboard/charts' => 'Get chart data (temperature, humidity, etc)',
                'GET /api/v1/dashboard/weather' => 'Get current weather from sensors',
            ],
            'sensor_data' => [
                'POST /api/v1/sensor-data' => 'Submit sensor data',
                'GET /api/v1/sensor-data' => 'Get sensor data',
                'GET /api/v1/sensor-data/statistics' => 'Get statistics',
                'GET /api/v1/sensor-data/latest' => 'Get latest readings',
            ],
            'irrigation' => [
                'POST /api/v1/irrigation' => 'Submit irrigation data',
                'GET /api/v1/irrigation' => 'Get irrigation logs',
                'GET /api/v1/irrigation/statistics' => 'Get irrigation statistics',
            ],
            'export' => [
                'GET /api/v1/export' => 'Export data (format=json|csv|sql)',
                'GET /api/v1/export/list' => 'List available exports',
                'GET /api/v1/export/download/{filename}' => 'Download export file',
            ],
            'monitor' => [
                'GET /api/v1/monitor/stats' => 'Get system statistics',
                'GET /api/v1/monitor/logs' => 'Get recent logs',
                'GET /api/v1/monitor/health' => 'System health check',
                'GET /api/v1/monitor/nodes' => 'Get node status',
            ],
            'legacy' => [
                'POST /api/index.php' => 'Legacy sensor data endpoint',
                'POST /api/api_getdata.php' => 'Legacy getdata endpoint',
                'POST /api/api_irrigate.php' => 'Legacy irrigation endpoint',
                'GET /api/export_data.php' => 'Legacy export endpoint',
            ]
        ],
        'documentation_url' => url('/api/docs'),
        'support' => 'agrinex@example.com'
    ]);
});

