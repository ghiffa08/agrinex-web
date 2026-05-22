<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GetdataLog;
use App\Models\IrrigateLog;
use App\Models\Node;
use App\Models\NodeLog;
use App\Models\SensorNodeData;
use App\Models\SensorWeatherData;
use App\Models\ValveLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the dashboard
     */
    public function index()
    {
        // Get all nodes
        $nodes = Node::all();
        
        // Statistics
        $stats = [
            'total_nodes' => $nodes->count(),
            'active_nodes' => $this->getActiveNodes(),
            'total_plots' => $nodes->whereNotNull('kode_perlakuan')->count(),
            'active_alerts' => $this->getActiveAlerts(),
            'ongoing_irrigation' => IrrigateLog::whereNull('waktu_akhir')->count(),
        ];

        // Get latest sensor readings for each node
        $nodesWithData = $nodes->map(function ($node) {
            $latestData = SensorNodeData::where('node_id', $node->node_id)
                ->latest('received_at')
                ->first();
            
            $latestLog = NodeLog::where('node_id', $node->node_id)
                ->latest('waktu')
                ->first();
            
            $node->latestReading = $latestData;
            $node->lastCommunication = $latestLog?->waktu;
            $node->is_active = $latestLog && Carbon::parse($latestLog->waktu)->gt(Carbon::now()->subHours(24));
            
            return $node;
        });

        // Get latest weather data (Node 65)
        $weather = SensorWeatherData::where('node_id', 65)
            ->latest('received_at')
            ->first();

        // Get recent alerts (from node logs with status issues)
        $recentAlerts = $this->getRecentAlerts();

        // Get today's irrigation events
        $todayIrrigation = IrrigateLog::whereDate('waktu_mulai', Carbon::today())
            ->latest('waktu_mulai')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'nodes',
            'nodesWithData',
            'weather',
            'recentAlerts',
            'todayIrrigation'
        ));
    }

    /**
     * Get chart data for specific node
     */
    public function chartData(Request $request)
    {
        $nodeId = $request->input('node_id');
        $hours = $request->input('hours', 24);
        
        $startTime = Carbon::now()->subHours($hours);
        
        // Get sensor node data
        $sensorData = SensorNodeData::where('node_id', $nodeId)
            ->where('received_at', '>=', $startTime)
            ->orderBy('received_at')
            ->get();
        
        // Get weather data
        $weatherData = SensorWeatherData::where('node_id', 65)
            ->where('received_at', '>=', $startTime)
            ->orderBy('received_at')
            ->get();
        
        $labels = $sensorData->pluck('received_at')->map(function ($date) {
            return Carbon::parse($date)->format('H:i');
        });
        
        return response()->json([
            'labels' => $labels,
            'soil_moisture' => $sensorData->pluck('soil_pct'),
            'soil_temperature' => $sensorData->pluck('temp_c'),
            'air_temperature' => $weatherData->pluck('temp_dht'),
            'air_humidity' => $weatherData->pluck('humidity'),
        ]);
    }

    /**
     * Get realtime data for dashboard refresh
     */
    public function realtimeData()
    {
        $nodes = Node::all()->map(function ($node) {
            $latestData = SensorNodeData::where('node_id', $node->node_id)
                ->latest('received_at')
                ->first();
            
            return [
                'node_id' => $node->node_id,
                'node_code' => $node->node_id,
                'soil_moisture' => $latestData?->soil_pct,
                'temperature' => $latestData?->temp_c,
                'last_reading' => $latestData?->received_at,
            ];
        });

        return response()->json([
            'nodes' => $nodes,
            'active_alerts' => $this->getActiveAlerts(),
            'timestamp' => now(),
        ]);
    }

    /**
     * Show the monitor page
     */
    public function monitor()
    {
        return view('monitor');
    }

    /**
     * Get count of active nodes (communicated in last 24 hours)
     */
    private function getActiveNodes()
    {
        return NodeLog::where('waktu', '>=', Carbon::now()->subHours(24))
            ->where('status', 'Aktif')
            ->distinct('node_id')
            ->count('node_id');
    }

    /**
     * Get active alerts from node logs
     */
    private function getActiveAlerts()
    {
        return NodeLog::where('waktu', '>=', Carbon::now()->subHours(24))
            ->where('status', 'Non Aktif')
            ->orWhereRaw("LOWER(keterangan) LIKE '%error%'")
            ->orWhereRaw("LOWER(keterangan) LIKE '%gagal%'")
            ->count();
    }

    /**
     * Get recent alerts
     */
    private function getRecentAlerts()
    {
        $alerts = [];
        
        // Check for nodes with communication issues
        $failedNodes = NodeLog::where('waktu', '>=', Carbon::now()->subHours(24))
            ->where('status', 'Non Aktif')
            ->latest('waktu')
            ->limit(5)
            ->get();
        
        foreach ($failedNodes as $log) {
            $alerts[] = (object)[
                'severity' => 'warning',
                'message' => "Node {$log->node_id} communication failed",
                'timestamp' => $log->waktu,
            ];
        }
        
        // Check for low soil moisture
        $lowMoisture = SensorNodeData::where('received_at', '>=', Carbon::now()->subHours(24))
            ->where('soil_pct', '<', 30)
            ->latest('received_at')
            ->limit(3)
            ->get();
        
        foreach ($lowMoisture as $reading) {
            $alerts[] = (object)[
                'severity' => 'critical',
                'message' => "Low soil moisture on Node {$reading->node_id} ({$reading->soil_pct}%)",
                'timestamp' => $reading->received_at,
            ];
        }
        
        // Sort by timestamp
        usort($alerts, function ($a, $b) {
            return Carbon::parse($b->timestamp)->timestamp - Carbon::parse($a->timestamp)->timestamp;
        });
        
        return array_slice($alerts, 0, 5);
    }
}
