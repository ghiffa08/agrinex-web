<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Node;
use App\Models\IrrigateLog;
use App\Models\SensorNodeData;
use App\Models\SensorWeatherData;
use App\Models\NodeLog;
use Carbon\Carbon;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    protected static ?string $title = 'Dashboard';

    protected static ?string $slug = 'dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -2;

    /**
     * Get view data for the dashboard.
     */
    public function getViewData(): array
    {
        $nodes = Node::all();

        if ($nodes->isEmpty()) {
            return [
                'hasNoNodes' => true,
            ];
        }

        // Statistics
        $stats = [
            'total_nodes' => $nodes->count(),
            'active_nodes' => $this->getActiveNodes(),
            'total_plots' => $nodes->whereNotNull('kode_perlakuan')->count(),
            'active_alerts' => $this->getActiveAlerts(),
            'ongoing_irrigation' => IrrigateLog::whereNull('waktu_akhir')->count(),
        ];

        // Get latest sensor readings for each device
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

        // Get latest weather data (Device ID 65 is still weather)
        $weather = SensorWeatherData::where('node_id', 65)
            ->latest('received_at')
            ->first();

        // Get recent alerts
        $recentAlerts = $this->getRecentAlerts();

        // Get today's irrigation events
        $todayIrrigation = IrrigateLog::whereDate('waktu_mulai', Carbon::today())
            ->latest('waktu_mulai')
            ->limit(5)
            ->get();

        return [
            'hasNoNodes' => false,
            'stats' => $stats,
            'nodes' => $nodes,
            'nodesWithData' => $nodesWithData,
            'weather' => $weather,
            'recentAlerts' => $recentAlerts,
            'todayIrrigation' => $todayIrrigation,
        ];
    }

    /**
     * Get count of active nodes (communicated in last 24 hours)
     */
    private function getActiveNodes()
    {
        return NodeLog::where('status', 'Aktif')
            ->where('waktu', '>=', Carbon::now()->subHours(24))
            ->distinct('node_id')
            ->count();
    }

    /**
     * Get active alerts from node logs
     */
    private function getActiveAlerts()
    {
        return NodeLog::where('waktu', '>=', Carbon::now()->subHours(24))
            ->where(function ($query) {
                $query->where('status', 'Non Aktif')
                    ->orWhereRaw("LOWER(keterangan) LIKE '%error%'")
                    ->orWhereRaw("LOWER(keterangan) LIKE '%gagal%'")
                    ->orWhereRaw("LOWER(keterangan) LIKE '%timeout%'");
            })
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
