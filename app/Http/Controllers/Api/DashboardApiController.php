<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\GetdataLog;
use App\Models\SensorNodeData;
use App\Models\SensorWeatherData;
use App\Models\IrrigateLog;
use App\Models\ValveLog;
use App\Models\JsonBackup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use App\Services\ChartDataService;
use App\Http\Resources\ChartDataResource;

class DashboardApiController extends Controller
{
    /**
     * Get all devices with latest sensor data from node table
     * GET /api/v1/dashboard/devices
     */
    public function getDevices()
    {
        try {
            // Get all nodes from node table
            $nodes = Node::orderBy('node_id')->get();
            
            if ($nodes->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No nodes found'
                ]);
            }
            
            // Get latest sensor session
            $latestSession = GetdataLog::orderBy('waktu_mulai', 'desc')
                ->first();
            
            // Get weather data from latest session
            $weatherData = null;
            if ($latestSession) {
                $weatherData = SensorWeatherData::where('sesi_id_getdata', $latestSession->sesi_id_getdata)
                    ->first();
            }
            
            // Transform node data into devices format
            $devices = $nodes->map(function ($node) use ($latestSession, $weatherData) {
                // Get latest sensor data for this node
                $sensorData = null;
                if ($latestSession) {
                    $sensorData = SensorNodeData::where('sesi_id_getdata', $latestSession->sesi_id_getdata)
                        ->where('node_id', $node->node_id)
                        ->first();
                }
                
                return [
                    'id' => $node->id,
                    'device_id' => $node->node_id,
                    'device_name' => "Node {$node->node_id}",
                    'plot_number' => $node->node_id,
                    'location' => $node->lokasi ?? "Sensor Node {$node->node_id}",
                    
                    // Treatment info from node table
                    'treatment_description' => $node->keterangan ?? 'Monitoring Optimal',
                    'treatment_type' => $node->group ?? 'standard',
                    'treatment_code' => $node->kode_perlakuan ?? "T{$node->node_id}",
                    'group' => $node->group,
                    'kode_perlakuan' => $node->kode_perlakuan,
                    
                    // Sensor data from node (if available)
                    // Only show data for nodes that have sensor readings
                    'soil_moisture_pct' => $sensorData ? (float) $sensorData->soil_pct : null,
                    'temperature_c' => $sensorData ? (float) $sensorData->temp_c : null,
                    'soil_temp_c' => $sensorData ? (float) $sensorData->temp_c : null,
                    
                    // Weather data - only show for nodes with sensor data
                    'air_temp_c' => $sensorData && $weatherData ? (float) $weatherData->temp_dht : null,
                    'air_humidity_pct' => $sensorData && $weatherData ? (float) $weatherData->humidity : null,
                    'light_lux' => $sensorData && $weatherData ? (float) $weatherData->light : null,
                    'water_height_cm' => null, // Not available in current schema
                    
                    // Battery info
                    'battery_voltage' => $sensorData ? (float) $sensorData->voltage_v : null,
                    'battery_voltage_v' => $sensorData ? (float) $sensorData->voltage_v : null,
                    'battery_percentage' => $sensorData ? $this->calculateBatteryPercentage($sensorData->voltage_v) : null,
                    
                    // Signal strength (real data if available, else null)
                    'signal_strength_rssi' => null,
                    'signal_strength_pct' => null,
                    
                    // Device status
                    'connection_state' => $latestSession ? $this->getConnectionStatus($latestSession->waktu_mulai) : 'offline',
                    'connection_status' => $latestSession ? $this->getConnectionStatus($latestSession->waktu_mulai) : 'offline',
                    'valve_state' => 'closed',
                    'valve_status' => 'closed',
                    'is_active' => true,
                    'status' => $sensorData ? 'normal' : 'no_data',
                    
                    // Water usage (placeholder)
                    'water_usage_today_l' => 0,
                    
                    // Timestamps
                    'recorded_at' => $latestSession ? $latestSession->waktu_mulai : null,
                    'last_seen' => $latestSession ? $latestSession->waktu_mulai : null,
                    'waktu_update' => $node->waktu_update,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $devices,
                'session_info' => [
                    'session_id' => $latestSession ? $latestSession->sesi_id_getdata : null,
                    'timestamp' => $latestSession ? $latestSession->waktu_mulai : null,
                    'total_nodes' => $nodes->count()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching devices: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get water tank information
     * GET /api/v1/dashboard/tank
     */
    public function getTank()
    {
        try {
            // Calculate tank data from irrigation sessions
            // Use cached schema check to avoid DB overhead
            $tableExists = $this->hasIrrigateLogsTable();
            
            $recentIrrigation = null;
            if ($tableExists) {
                $recentIrrigation = IrrigateLog::with('valveLogs')
                    ->where('status', 'completed')
                    ->orderBy('waktu_mulai', 'desc')
                    ->first();
            }
            
            // Mock tank data (customize based on your actual tank monitoring)
            $tankCapacity = 1000; // liters
            $usedToday = 0;
            
            if ($recentIrrigation && $recentIrrigation->valveLogs) {
                $usedToday = $recentIrrigation->valveLogs->sum('volume_ml') / 1000; // Convert ml to liters
            }
            
            $currentVolume = max(0, $tankCapacity - $usedToday);
            $percentage = ($currentVolume / $tankCapacity) * 100;
            $waterLevelCm = ($percentage / 100) * 150; // Assuming 150cm max height
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => 1,
                    'tank_name' => 'Tangki Air Utama',
                    'name' => 'Tangki Air Utama',
                    'capacity' => $tankCapacity,
                    'capacity_liters' => $tankCapacity,
                    'current_volume_liters' => $currentVolume,
                    'water_level_cm' => round($waterLevelCm, 2),
                    'percentage' => round($percentage, 2),
                    'status' => $percentage > 70 ? 'normal' : ($percentage > 30 ? 'warning' : 'critical'),
                    'updated_at' => $recentIrrigation ? $recentIrrigation->waktu_mulai : now()
                ]
            ]);
            
        } catch (\Exception $e) {
            // Return null data if table doesn't exist
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => null,
                    'tank_name' => 'Tangki Air Utama',
                    'name' => 'Tangki Air Utama',
                    'capacity' => 0,
                    'capacity_liters' => 0,
                    'current_volume_liters' => 0,
                    'water_level_cm' => 0,
                    'percentage' => 0,
                    'status' => 'no_data',
                    'updated_at' => null
                ],
                'note' => 'No data available'
            ]);
        }
    }
    
    /**
     * Get irrigation schedule
     * GET /api/v1/dashboard/schedule
     */
    public function getSchedule()
    {
        try {
            // Use cached schema check to avoid DB overhead
            $tableExists = $this->hasIrrigateLogsTable();
            
            if (!$tableExists) {
                // Return mock schedule if table doesn't exist
                return response()->json([
                    'success' => true,
                    'data' => [
                        'date' => today()->format('Y-m-d'),
                        'total_sessions' => 0,
                        'sessions' => []
                    ],
                    'note' => 'irrigate_logs table not available'
                ]);
            }
            
            // Get today's irrigation sessions
            $todaySessions = IrrigateLog::whereDate('waktu_mulai', today())
                ->with('valveLogs')
                ->orderBy('waktu_mulai')
                ->get();
            
            $schedule = $todaySessions->map(function ($session) {
                return [
                    'id' => $session->id,
                    'session_id' => $session->sesi_id_irrigate,
                    'start_time' => $session->waktu_mulai,
                    'end_time' => $session->waktu_selesai,
                    'duration_minutes' => $session->duration,
                    'total_valves' => $session->jumlah_valve,
                    'status' => $session->status,
                    'total_volume_ml' => $session->valveLogs->sum('volume_ml')
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'date' => today()->format('Y-m-d'),
                    'total_sessions' => $schedule->count(),
                    'sessions' => $schedule
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'data' => [
                    'date' => today()->format('Y-m-d'),
                    'total_sessions' => 0,
                    'sessions' => []
                ],
                'note' => 'No data available: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get 30-day water usage history
     * GET /api/v1/dashboard/usage
     */
    public function getUsage()
    {
        try {
            // Use cached schema check to avoid DB overhead
            $tableExists = $this->hasIrrigateLogsTable();
            
            if (!$tableExists) {
                // Return mock usage if table doesn't exist
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'summary' => [
                        'total_days' => 0,
                        'total_usage_l' => 0,
                        'average_usage_l' => 0,
                        'period' => '30 days'
                    ],
                    'note' => 'irrigate_logs table not available'
                ]);
            }
            
            $startDate = Carbon::now()->subDays(30);
            
            // Get daily irrigation totals for last 30 days
            $usage = IrrigateLog::where('waktu_mulai', '>=', $startDate)
                ->where('status', 'completed')
                ->with('valveLogs')
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->waktu_mulai)->format('Y-m-d');
                })
                ->map(function ($daySessions, $date) {
                    $totalVolume = $daySessions->reduce(function ($carry, $session) {
                        return $carry + $session->valveLogs->sum('volume_ml');
                    }, 0);
                    
                    return [
                        'date' => $date,
                        'usage_date' => $date,
                        'total_l' => round($totalVolume / 1000, 2), // Convert ml to liters
                        'liters' => round($totalVolume / 1000, 2),
                        'sessions' => $daySessions->count()
                    ];
                })
                ->values();
            
            return response()->json([
                'success' => true,
                'data' => $usage,
                'summary' => [
                    'total_days' => $usage->count(),
                    'total_usage_l' => $usage->sum('total_l'),
                    'average_usage_l' => $usage->count() > 0 ? round($usage->avg('total_l'), 2) : 0,
                    'period' => '30 days'
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'data' => [],
                'summary' => [
                    'total_days' => 0,
                    'total_usage_l' => 0,
                    'average_usage_l' => 0,
                    'period' => '30 days'
                ],
                'note' => 'No data available: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get 24-hour water usage history (hourly)
     * GET /api/v1/dashboard/usage/daily
     */
    public function getUsageDaily()
    {
        try {
            // Use cached schema check to avoid DB overhead
            $tableExists = $this->hasIrrigateLogsTable();
            
            if (!$tableExists) {
                // Return mock usage if table doesn't exist
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'summary' => [
                        'total_hours' => 0,
                        'total_usage_l' => 0,
                        'average_usage_l' => 0,
                        'period' => '24 hours'
                    ],
                    'note' => 'irrigate_logs table not available'
                ]);
            }
            
            $startTime = Carbon::now()->subHours(24);
            
            // Get hourly irrigation totals for last 24 hours
            $usage = IrrigateLog::where('waktu_mulai', '>=', $startTime)
                ->where('status', 'completed')
                ->with('valveLogs')
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->waktu_mulai)->format('Y-m-d H:00');
                })
                ->map(function ($hourSessions, $datetime) {
                    $totalVolume = $hourSessions->reduce(function ($carry, $session) {
                        return $carry + $session->valveLogs->sum('volume_ml');
                    }, 0);
                    
                    $hour = Carbon::parse($datetime)->format('H:00');
                    
                    return [
                        'hour' => $hour,
                        'datetime' => $datetime,
                        'total_l' => round($totalVolume / 1000, 2), // Convert ml to liters
                        'liters' => round($totalVolume / 1000, 2),
                        'sessions' => $hourSessions->count()
                    ];
                })
                ->values();
            
            return response()->json([
                'success' => true,
                'data' => $usage,
                'summary' => [
                    'total_hours' => $usage->count(),
                    'total_usage_l' => $usage->sum('total_l'),
                    'average_usage_l' => $usage->count() > 0 ? round($usage->avg('total_l'), 2) : 0,
                    'period' => '24 hours'
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'data' => [],
                'summary' => [
                    'total_hours' => 0,
                    'total_usage_l' => 0,
                    'average_usage_l' => 0,
                    'period' => '24 hours'
                ],
                'note' => 'No data available: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get chart data for environmental monitoring
     * GET /api/v1/dashboard/charts
     */
    public function getChartData(Request $request, ChartDataService $chartService)
    {
        try {
            $type = $request->input('type', 'all');
            $days = $request->input('days', 7);
            $limit = $request->input('limit', null);
            
            $result = $chartService->getSessions($days, $limit);
            
            $formattedData = (new ChartDataResource($result['sessions']))
                                ->setType($type)
                                ->resolve();
            
            return response()->json([
                'success' => true,
                'data' => $formattedData,
                'meta' => [
                    'total_points' => $result['sessions']->count(),
                    'time_range_days' => $days ?? ($limit ? 'limited' : 7),
                    'start_time' => $result['start_time']->format('Y-m-d H:i:s'),
                    'end_time' => now()->format('Y-m-d H:i:s')
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching chart data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get current weather from latest sensor reading
     * GET /api/v1/dashboard/weather
     */
    public function getWeather()
    {
        try {
            $latestSession = GetdataLog::orderBy('waktu_mulai', 'desc')
                ->first();
            
            if (!$latestSession) {
                return response()->json([
                    'success' => false,
                    'message' => 'No weather data available'
                ], 200); // Changed to 200 to prevent browser console 404 error
            }
            
            $weatherData = SensorWeatherData::where('sesi_id_getdata', $latestSession->sesi_id_getdata)
                ->first();
            
            if (!$weatherData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No weather data in session'
                ], 200); // Changed to 200 to prevent browser console 404 error
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'temperature' => (float) $weatherData->temp_dht,
                    'temp' => (float) $weatherData->temp_dht,
                    'humidity' => (float) $weatherData->humidity,
                    'light' => (float) $weatherData->light,
                    'light_pct' => min(100, ($weatherData->light / 100000) * 100), // Convert lux to percentage
                    'rain' => (float) $weatherData->rain,
                    'wind' => (float) $weatherData->wind,
                    'wind_speed' => (float) $weatherData->wind,
                    'pressure' => null, // Not available in schema
                    'voltage' => (float) $weatherData->voltage,
                    'current' => (float) $weatherData->current,
                    'power' => (float) $weatherData->power,
                    'timestamp' => $latestSession->waktu_mulai,
                    'session_id' => $latestSession->sesi_id_getdata
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching weather: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get JSON backup history
     * GET /api/v1/dashboard/json-backup
     */
    public function getJsonBackup(Request $request)
    {
        try {
            $query = JsonBackup::orderBy('backup_timestamp', 'desc');
            
            // Filter by sesi_id_getdata if provided
            if ($request->has('sesi_id_getdata')) {
                $query->where('sesi_id_getdata', $request->sesi_id_getdata);
            }
            
            // Filter by date range if provided
            if ($request->has('date_from')) {
                $query->whereDate('backup_timestamp', '>=', $request->date_from);
            }
            if ($request->has('date_to')) {
                $query->whereDate('backup_timestamp', '<=', $request->date_to);
            }
            
            // Limit results
            $limit = min($request->get('limit', 50), 200);
            $backups = $query->limit($limit)->get();
            
            return response()->json([
                'success' => true,
                'data' => $backups,
                'metadata' => [
                    'total_records' => $backups->count(),
                    'limit' => $limit,
                    'filters_applied' => [
                        'sesi_id_getdata' => $request->sesi_id_getdata,
                        'date_from' => $request->date_from,
                        'date_to' => $request->date_to,
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching JSON backup: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Helper Methods
    
    /**
     * Calculate battery percentage from voltage
     */
    private function calculateBatteryPercentage($voltage)
    {
        if (!$voltage) return 0;
        
        // Assuming 3.3V - 4.2V range for battery
        $minVoltage = 3.3;
        $maxVoltage = 4.2;
        
        $percentage = (($voltage - $minVoltage) / ($maxVoltage - $minVoltage)) * 100;
        
        return max(0, min(100, round($percentage, 2)));
    }
    
    /**
     * Determine connection status based on last seen time
     */
    private function getConnectionStatus($lastSeen)
    {
        $minutesAgo = Carbon::parse($lastSeen)->diffInMinutes(now());
        
        if ($minutesAgo < 5) return 'online';
        if ($minutesAgo < 15) return 'idle';
        return 'offline';
    }
    
    /**
     * Check if irrigate_logs table exists using Cache to improve performance
     */
    private function hasIrrigateLogsTable()
    {
        return Cache::remember('has_irrigate_logs_table', 60 * 60 * 24, function () {
            return Schema::hasTable('irrigate_logs');
        });
    }
}
