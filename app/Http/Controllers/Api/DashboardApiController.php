<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DataSession;
use App\Models\SensorData;
use App\Models\WeatherData;
use App\Models\IrrigationLog;
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
            // Get all nodes from device table
            $nodes = Device::orderBy('id')->get();
            
            if ($nodes->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No nodes found'
                ]);
            }
            
            // Get latest sensor session
            $latestSession = DataSession::orderBy('started_at', 'desc')
                ->first();
            
            // Get weather data from latest session
            $weatherData = null;
            if ($latestSession) {
                $weatherData = WeatherData::where('data_session_id', $latestSession->id)
                    ->first();
            }
            
            // Transform node data into devices format
            $devices = $nodes->map(function ($node) use ($latestSession, $weatherData) {
                // Get latest sensor data for this node
                $sensorData = null;
                if ($latestSession) {
                    $sensorData = SensorData::where('data_session_id', $latestSession->id)
                        ->where('device_id', $node->id)
                        ->first();
                }
                
                return [
                    'id' => $node->id,
                    'device_id' => $node->id,
                    'device_name' => "Node {$node->id}",
                    'plot_number' => $node->id,
                    'location' => $node->lokasi ?? "Sensor Node {$node->id}",
                    
                    // Treatment info from node table
                    'treatment_description' => $node->keterangan ?? 'Monitoring Optimal',
                    'treatment_type' => $node->group ?? 'standard',
                    'treatment_code' => $node->kode_perlakuan ?? "T{$node->id}",
                    'group' => $node->group,
                    'kode_perlakuan' => $node->kode_perlakuan,
                    
                    // Sensor data from node (if available)
                    // Only show data for nodes that have sensor readings
                    'soil_moisture_pct' => $sensorData ? (float) $sensorData->soil_pct : null,
                    'temperature_c' => $sensorData ? (float) $sensorData->temp_c : null,
                    'soil_temp_c' => $sensorData ? (float) $sensorData->temp_c : null,
                    
                    // Weather data - only show for nodes with sensor data
                    'air_temp_c' => $sensorData && $weatherData ? (float) $weatherData->temp_c : null,
                    'air_humidity_pct' => $sensorData && $weatherData ? (float) $weatherData->humidity_pct : null,
                    'light_lux' => $sensorData && $weatherData ? (float) $weatherData->light_lux : null,
                    'water_height_cm' => null, // Not available in current schema
                    
                    // Battery info
                    'battery_voltage' => $sensorData ? (float) $sensorData->voltage_v : null,
                    'battery_voltage_v' => $sensorData ? (float) $sensorData->voltage_v : null,
                    'battery_percentage' => $sensorData ? $this->calculateBatteryPercentage($sensorData->voltage_v) : null,
                    
                    // Signal strength (real data if available, else null)
                    'signal_strength_rssi' => null,
                    'signal_strength_pct' => null,
                    
                    // Device status
                    'connection_state' => $latestSession ? $this->getConnectionStatus($latestSession->started_at) : 'offline',
                    'connection_status' => $latestSession ? $this->getConnectionStatus($latestSession->started_at) : 'offline',
                    'valve_state' => 'closed',
                    'valve_status' => 'closed',
                    'is_active' => true,
                    'status' => $sensorData ? 'normal' : 'no_data',
                    
                    // Water usage (placeholder)
                    'water_usage_today_l' => 0,
                    
                    // Timestamps
                    'recorded_at' => $latestSession ? $latestSession->started_at : null,
                    'last_seen' => $latestSession ? $latestSession->started_at : null,
                    'waktu_update' => $node->updated_at,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $devices,
                'session_info' => [
                    'session_id' => $latestSession ? $latestSession->session_id : null,
                    'timestamp' => $latestSession ? $latestSession->started_at : null,
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
            $tableExists = $this->hasIrrigationLogsTable();
            
            $recentIrrigation = null;
            if ($tableExists) {
                $recentIrrigation = IrrigationLog::with('valveLogs')
                    ->where('status', 'completed')
                    ->orderBy('started_at', 'desc')
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
                    'updated_at' => $recentIrrigation ? $recentIrrigation->started_at : now()
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
            $tableExists = $this->hasIrrigationLogsTable();
            
            if (!$tableExists) {
                // Return mock schedule if table doesn't exist
                return response()->json([
                    'success' => true,
                    'data' => [
                        'date' => today()->format('Y-m-d'),
                        'total_sessions' => 0,
                        'sessions' => []
                    ],
                    'note' => 'irrigation_logs table not available'
                ]);
            }
            
            // Get today's irrigation sessions
            $todaySessions = IrrigationLog::whereDate('started_at', today())
                ->with('valveLogs')
                ->orderBy('started_at')
                ->get();
            
            $schedule = $todaySessions->map(function ($session) {
                return [
                    'id' => $session->id,
                    'session_id' => $session->session_id,
                    'start_time' => $session->started_at,
                    'end_time' => $session->ended_at,
                    'duration_minutes' => Carbon::parse($session->started_at)->diffInMinutes($session->ended_at),
                    'total_valves' => $session->success_count + $session->failed_count,
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
            $tableExists = $this->hasIrrigationLogsTable();
            
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
                    'note' => 'irrigation_logs table not available'
                ]);
            }
            
            $startDate = Carbon::now()->subDays(30);
            
            // Get daily irrigation totals for last 30 days
            $usage = IrrigationLog::where('started_at', '>=', $startDate)
                ->where('status', 'completed')
                ->with('valveLogs')
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->started_at)->format('Y-m-d');
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
            $tableExists = $this->hasIrrigationLogsTable();
            
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
                    'note' => 'irrigation_logs table not available'
                ]);
            }
            
            $startTime = Carbon::now()->subHours(24);
            
            // Get hourly irrigation totals for last 24 hours
            $usage = IrrigationLog::where('started_at', '>=', $startTime)
                ->where('status', 'completed')
                ->with('valveLogs')
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->started_at)->format('Y-m-d H:00');
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
            $latestSession = DataSession::orderBy('started_at', 'desc')
                ->first();
            
            if (!$latestSession) {
                return response()->json([
                    'success' => false,
                    'message' => 'No weather data available'
                ], 200); // Changed to 200 to prevent browser console 404 error
            }
            
            $weatherData = WeatherData::where('data_session_id', $latestSession->id)
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
                    'temperature' => (float) $weatherData->temp_c,
                    'temp' => (float) $weatherData->temp_c,
                    'humidity' => (float) $weatherData->humidity_pct,
                    'light' => (float) $weatherData->light_lux,
                    'light_pct' => min(100, ($weatherData->light_lux / 100000) * 100), // Convert lux to percentage
                    'rain' => (float) $weatherData->rain_mm,
                    'wind' => (float) $weatherData->wind_speed_kmh,
                    'wind_speed' => (float) $weatherData->wind_speed_kmh,
                    'pressure' => (float) $weatherData->pressure_hpa,
                    'voltage' => null,
                    'current' => null,
                    'power' => null,
                    'timestamp' => $latestSession->started_at,
                    'session_id' => $latestSession->session_id
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
     * Check if irrigation_logs table exists using Cache to improve performance
     */
    private function hasIrrigationLogsTable()
    {
        return Cache::remember('has_irrigation_logs_table', 60 * 60 * 24, function () {
            return Schema::hasTable('irrigation_logs');
        });
    }
}
