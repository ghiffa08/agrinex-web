<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\NodeLog;
use App\Models\SensorNodeData;
use App\Models\Node;
use Illuminate\Http\Request;

class AlertsController extends Controller
{
    /**
     * Display alerts dashboard
     */
    public function index()
    {
        // Get communication failures (bad signal quality)
        $commFailures = NodeLog::whereNotIn('signal_quality', ['Good', 'Excellent'])
            ->orWhere('rssi_dbm', '<', -120)
            ->with('node')
            ->latest('waktu')
            ->limit(50)
            ->get();

        // Get low soil moisture alerts (< 30%)
        $lowMoisture = SensorNodeData::where('soil_pct', '<', 30)
            ->where('received_at', '>=', now()->subDay())
            ->with('node')
            ->latest('received_at')
            ->limit(50)
            ->get();

        // Get high temperature alerts (> 35°C)
        $highTemp = SensorNodeData::where('temp_c', '>', 35)
            ->where('received_at', '>=', now()->subDay())
            ->with('node')
            ->latest('received_at')
            ->limit(50)
            ->get();

        // Get low voltage alerts (< 3.0V)
        $lowVoltage = SensorNodeData::where('voltage_v', '<', 3.0)
            ->where('received_at', '>=', now()->subDay())
            ->with('node')
            ->latest('received_at')
            ->limit(50)
            ->get();

        // Get offline nodes (no data in last 2 hours)
        $offlineNodes = Node::whereDoesntHave('sensorData', function($query) {
            $query->where('received_at', '>=', now()->subHours(2));
        })->get();

        // Statistics
        $stats = [
            'critical' => $lowMoisture->count() + $offlineNodes->count(),
            'warning' => $highTemp->count() + $lowVoltage->count(),
            'info' => $commFailures->count(),
            'total' => $commFailures->count() + $lowMoisture->count() + $highTemp->count() + $lowVoltage->count() + $offlineNodes->count(),
        ];

        return view('alerts.index', compact(
            'commFailures', 
            'lowMoisture', 
            'highTemp', 
            'lowVoltage', 
            'offlineNodes',
            'stats'
        ));
    }

    /**
     * Get alerts by type
     */
    public function byType($type)
    {
        $alerts = collect();
        $title = '';

        switch($type) {
            case 'communication':
                $alerts = NodeLog::whereNotIn('signal_quality', ['Good', 'Excellent'])
                    ->orWhere('rssi_dbm', '<', -120)
                    ->with('node')
                    ->latest('waktu')
                    ->paginate(50);
                $title = 'Communication Issues';
                break;
            
            case 'moisture':
                $alerts = SensorNodeData::where('soil_pct', '<', 30)
                    ->where('received_at', '>=', now()->subDay())
                    ->with('node')
                    ->latest('received_at')
                    ->paginate(50);
                $title = 'Low Soil Moisture Alerts';
                break;
            
            case 'temperature':
                $alerts = SensorNodeData::where('temp_c', '>', 35)
                    ->where('received_at', '>=', now()->subDay())
                    ->with('node')
                    ->latest('received_at')
                    ->paginate(50);
                $title = 'High Temperature Alerts';
                break;
            
            case 'voltage':
                $alerts = SensorNodeData::where('voltage_v', '<', 3.0)
                    ->where('received_at', '>=', now()->subDay())
                    ->with('node')
                    ->latest('received_at')
                    ->paginate(50);
                $title = 'Low Voltage Alerts';
                break;
        }

        return view('alerts.by-type', compact('alerts', 'title', 'type'));
    }
}
