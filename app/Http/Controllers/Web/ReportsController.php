<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SensorData;
use App\Models\IrrigationLog;
use App\Models\ValveLog;
use App\Models\Device;
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

        // Irrigation Statistics
        $irrigationStats = [
            'total_events' => IrrigationLog::whereBetween('started_at', [$startDate, $endDate])->count(),
            'total_duration' => 0, // Obsolete metric
            'avg_duration' => 0, // Obsolete metric
        ];

        // Sensor Data Statistics
        $sensorStats = [
            'total_readings' => SensorData::whereBetween('recorded_at', [$startDate, $endDate])->count(),
            'avg_moisture' => SensorData::whereBetween('recorded_at', [$startDate, $endDate])->avg('soil_pct') ?? 0,
            'avg_temp' => SensorData::whereBetween('recorded_at', [$startDate, $endDate])->avg('temp_c') ?? 0,
            'min_moisture' => SensorData::whereBetween('recorded_at', [$startDate, $endDate])->min('soil_pct') ?? 0,
            'max_temp' => SensorData::whereBetween('recorded_at', [$startDate, $endDate])->max('temp_c') ?? 0,
        ];

        // Node Activity
        $nodeActivity = SensorData::select(
                'device_id', 
                DB::raw('COUNT(*) as reading_count'),
                DB::raw('AVG(soil_pct) as avg_moisture'),
                DB::raw('AVG(temp_c) as avg_temp')
            )
            ->whereBetween('recorded_at', [$startDate, $endDate])
            ->groupBy('device_id')
            ->with('device')
            ->get();

        // Daily summary
        $dailySummary = SensorData::select(
                DB::raw('DATE(recorded_at) as date'),
                DB::raw('COUNT(*) as total_readings'),
                DB::raw('AVG(soil_pct) as avg_moisture'),
                DB::raw('AVG(temp_c) as avg_temp'),
                DB::raw('MIN(soil_pct) as min_moisture'),
                DB::raw('MAX(temp_c) as max_temp')
            )
            ->whereBetween('recorded_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(recorded_at)'))
            ->orderBy('date', 'desc')
            ->get();

        // Get all nodes for filters
        $nodes = Device::orderBy('id')->get();

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

        $node = Device::findOrFail($nodeId);

        // Sensor data for this node
        $sensorData = SensorData::where('device_id', $nodeId)
            ->whereBetween('recorded_at', [$startDate, $endDate])
            ->orderBy('recorded_at', 'desc')
            ->paginate(100);

        // Statistics for this node
        $stats = [
            'total_readings' => SensorData::where('device_id', $nodeId)
                ->whereBetween('recorded_at', [$startDate, $endDate])->count(),
            'avg_moisture' => SensorData::where('device_id', $nodeId)
                ->whereBetween('recorded_at', [$startDate, $endDate])->avg('soil_pct'),
            'avg_temp' => SensorData::where('device_id', $nodeId)
                ->whereBetween('recorded_at', [$startDate, $endDate])->avg('temp_c'),
            // Count irrigate sessions that involved this node by checking valve logs
            'irrigation_events' => ValveLog::where('device_id', $nodeId)
                ->whereBetween('logged_at', [$startDate, $endDate])->count(),
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
        $data = SensorData::whereBetween('recorded_at', [$startDate, $endDate])
            ->with('device')
            ->orderBy('recorded_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Device ID', 'Location', 'Date Time', 'Voltage (V)', 'Current (mA)', 'Temperature (°C)', 'Soil Moisture (%)']);
            
            // Data
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->device_id,
                    $row->device->lokasi ?? '-',
                    $row->recorded_at,
                    $row->voltage_v,
                    $row->current_ma,
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
        $data = ValveLog::whereBetween('logged_at', [$startDate, $endDate])
            ->with(['device', 'irrigationLog'])
            ->orderBy('logged_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Device ID', 'Session ID', 'Date Time', 'Valve Status', 'Reason']);
            
            // Data
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->device_id,
                    $row->irrigationLog->session_id ?? '-',
                    $row->logged_at,
                    $row->valve_status,
                    $row->reason,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
