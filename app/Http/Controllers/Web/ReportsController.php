<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SensorNodeData;
use App\Models\IrrigateLog;
use App\Models\ValveLog;
use App\Models\Node;
use App\Models\GetdataLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(7));
        $endDate = $request->input('end_date', now());

        // Irrigation Statistics - using correct columns
        $irrigationStats = [
            'total_events' => IrrigateLog::whereBetween('waktu_mulai', [$startDate, $endDate])->count(),
            'total_duration' => ValveLog::whereBetween('waktu', [$startDate, $endDate])->sum('durasi_detik') / 60, // Convert to minutes
            'avg_duration' => ValveLog::whereBetween('waktu', [$startDate, $endDate])->avg('durasi_detik') / 60, // Convert to minutes
        ];

        // Sensor Data Statistics - using correct columns
        $sensorStats = [
            'total_readings' => SensorNodeData::whereBetween('received_at', [$startDate, $endDate])->count(),
            'avg_moisture' => SensorNodeData::whereBetween('received_at', [$startDate, $endDate])->avg('soil_pct') ?? 0,
            'avg_temp' => SensorNodeData::whereBetween('received_at', [$startDate, $endDate])->avg('temp_c') ?? 0,
            'min_moisture' => SensorNodeData::whereBetween('received_at', [$startDate, $endDate])->min('soil_pct') ?? 0,
            'max_temp' => SensorNodeData::whereBetween('received_at', [$startDate, $endDate])->max('temp_c') ?? 0,
        ];

        // Node Activity - using correct columns
        $nodeActivity = SensorNodeData::select(
                'node_id', 
                DB::raw('COUNT(*) as reading_count'),
                DB::raw('AVG(soil_pct) as avg_moisture'),
                DB::raw('AVG(temp_c) as avg_temp')
            )
            ->whereBetween('received_at', [$startDate, $endDate])
            ->groupBy('node_id')
            ->with('node')
            ->get();

        // Daily summary - using correct columns
        $dailySummary = SensorNodeData::select(
                DB::raw('DATE(received_at) as date'),
                DB::raw('COUNT(*) as total_readings'),
                DB::raw('AVG(soil_pct) as avg_moisture'),
                DB::raw('AVG(temp_c) as avg_temp'),
                DB::raw('MIN(soil_pct) as min_moisture'),
                DB::raw('MAX(temp_c) as max_temp')
            )
            ->whereBetween('received_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(received_at)'))
            ->orderBy('date', 'desc')
            ->get();

        // Get all nodes for filters
        $nodes = Node::orderBy('node_id')->get();

        return view('reports.index', compact(
            'irrigationStats',
            'sensorStats',
            'nodeActivity',
            'dailySummary',
            'nodes',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Generate detailed report by node
     */
    public function byNode(Request $request, $nodeId)
    {
        $startDate = $request->input('start_date', now()->subDays(7));
        $endDate = $request->input('end_date', now());

        $node = Node::findOrFail($nodeId);

        // Sensor data for this node
        $sensorData = SensorNodeData::where('node_id', $nodeId)
            ->whereBetween('received_at', [$startDate, $endDate])
            ->orderBy('received_at', 'desc')
            ->paginate(100);

        // Statistics for this node
        $stats = [
            'total_readings' => SensorNodeData::where('node_id', $nodeId)
                ->whereBetween('received_at', [$startDate, $endDate])->count(),
            'avg_moisture' => SensorNodeData::where('node_id', $nodeId)
                ->whereBetween('received_at', [$startDate, $endDate])->avg('soil_pct'),
            'avg_temp' => SensorNodeData::where('node_id', $nodeId)
                ->whereBetween('received_at', [$startDate, $endDate])->avg('temp_c'),
            // Count irrigate sessions that involved this node by checking valve logs
            'irrigation_events' => IrrigateLog::with('node')
                ->whereHas('valveLogs', function($q) use ($nodeId) {
                    $q->where('node_id', $nodeId);
                })
                ->whereBetween('waktu_mulai', [$startDate, $endDate])->count(),
        ];

        return view('reports.by-node', compact('node', 'sensorData', 'stats', 'startDate', 'endDate'));
    }

    /**
     * Export report to CSV
     */
    public function export(Request $request)
    {
        $type = $request->input('type', 'sensor');
        $startDate = $request->input('start_date', now()->subDays(7));
        $endDate = $request->input('end_date', now());

        $filename = "agrinex_report_{$type}_" . now()->format('Y-m-d_His') . '.csv';

        switch($type) {
            case 'sensor':
                return $this->exportSensorData($startDate, $endDate, $filename);
            case 'irrigation':
                return $this->exportIrrigationData($startDate, $endDate, $filename);
            default:
                return redirect()->back()->with('error', 'Invalid report type');
        }
    }

    /**
     * Export sensor data to CSV
     */
    private function exportSensorData($startDate, $endDate, $filename)
    {
        $data = SensorNodeData::whereBetween('received_at', [$startDate, $endDate])
            ->with('node')
            ->orderBy('received_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Node ID', 'Location', 'Date Time', 'Voltage (V)', 'Current (mA)', 'Temperature (°C)', 'Soil Moisture (%)']);
            
            // Data
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->node_id,
                    $row->node->lokasi ?? '-',
                    $row->received_at,
                    $row->voltage_v,
                    $row->current,
                    $row->temp_c,
                    $row->soil_pct,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export irrigation data to CSV
     */
    private function exportIrrigationData($startDate, $endDate, $filename)
    {
        $data = IrrigateLog::whereBetween('waktu_mulai', [$startDate, $endDate])
            ->orderBy('waktu_mulai', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Node ID', 'Session', 'Date Time', 'Duration (s)', 'Status']);
            
            // Data
            foreach ($data as $row) {
                fputcsv($file, [
                    // irrigate_logs uses `id_node` as the foreign key to nodes
                    $row->id_node ?? $row->node_id ?? '-',
                    $row->sesi_id_irrigate,
                    $row->waktu_mulai,
                    $row->waktu_akhir - $row->waktu_mulai,
                    $row->node_sukses,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
