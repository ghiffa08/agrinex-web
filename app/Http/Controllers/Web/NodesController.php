<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\SensorData;
use App\Models\DeviceLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NodesController extends Controller
{
  public function index()
  {
    // Get all devices
    $nodes = Device::with(['sensorData' => function($query) {
        $query->latest('recorded_at')->limit(1);
    }])->orderBy('id')->get();

    // Get latest log for each device
    $latestLogs = DeviceLog::select('device_logs.*')
      ->from('device_logs')
      ->join(
        \DB::raw('(SELECT device_id, MAX(logged_at) as max_logged_at FROM device_logs GROUP BY device_id) as latest'),
        function ($join) {
          $join->on('device_logs.device_id', '=', 'latest.device_id')
            ->on('device_logs.logged_at', '=', 'latest.max_logged_at');
        }
      )
      ->get();

    // Attach latest log to each node
    foreach ($nodes as $node) {
      $node->latestLog = $latestLogs->firstWhere('device_id', $node->id);
      $node->is_online = $node->latestLog && 
                         $node->latestLog->is_active &&
                         now()->diffInHours($node->latestLog->logged_at) < 24;
      $node->latestSensorData = $node->sensorData->first();
    }

    // Calculate statistics based on latest logs only
    $stats = [
      'total' => $nodes->count(),
      'active' => $latestLogs->where('is_active', true)->count(),
      'online' => $latestLogs->where('is_active', true)->count(),
      'offline' => $latestLogs->where('is_active', false)->count(),
      'alerts' => $latestLogs->whereNotIn('signal_quality', ['Good', 'Excellent'])->count(),
    ];

    return view('nodes.index', compact('nodes', 'stats'));
  }

  public function show($id)
  {
    $node = Device::findOrFail($id);

    // Get sensor data for the last 24 hours
    $sensorData = SensorData::where('device_id', $id)
      ->where('recorded_at', '>=', now()->subDay())
      ->orderBy('recorded_at', 'asc')
      ->get();

    // Get communication logs for the last 7 days
    $logs = DeviceLog::where('device_id', $id)
      ->where('logged_at', '>=', now()->subDays(7))
      ->orderBy('logged_at', 'desc')
      ->limit(50)
      ->get();

    $latestSensorData = SensorData::where('device_id', $id)
      ->latest('recorded_at')
      ->first();

    // Prepare chart data
    $chartData = [
      'labels' => $sensorData->pluck('recorded_at')->map(function ($date) {
        return Carbon::parse($date)->format('H:i');
      }),
      'temperature' => $sensorData->pluck('temp_c'),
      'moisture' => $sensorData->pluck('soil_pct'),
    ];

    // Calculate statistics with default values if no data
    $stats = [
      'avg_temperature' => $sensorData->count() > 0 ? round($sensorData->avg('temp_c'), 1) : 0,
      'avg_moisture' => $sensorData->count() > 0 ? round($sensorData->avg('soil_pct'), 1) : 0,
      'min_temperature' => $sensorData->count() > 0 ? round($sensorData->min('temp_c'), 1) : 0,
      'max_temperature' => $sensorData->count() > 0 ? round($sensorData->max('temp_c'), 1) : 0,
      'min_moisture' => $sensorData->count() > 0 ? round($sensorData->min('soil_pct'), 1) : 0,
      'max_moisture' => $sensorData->count() > 0 ? round($sensorData->max('soil_pct'), 1) : 0,
      'avg_rssi' => $logs->count() > 0 ? round($logs->avg('rssi_dbm'), 1) : 0,
      'total_readings' => $sensorData->count(),
    ];

    return view('nodes.show', compact('node', 'sensorData', 'logs', 'chartData', 'stats', 'latestSensorData'));
  }

  /**
   * Show the form for editing the specified node
   */
  public function edit($id)
  {
    $node = Device::findOrFail($id);
    return view('nodes.edit', compact('node'));
  }

  /**
   * Update the specified node in storage
   */
  public function update(Request $request, $id)
  {
    $node = Device::findOrFail($id);

    $validated = $request->validate([
      'group' => 'nullable|string|max:50',
      'kode_perlakuan' => 'nullable|string|max:50',
      'lokasi' => 'nullable|string|max:255',
      'keterangan' => 'nullable|string',
    ]);

    $node->update($validated);

    return redirect()
      ->route('nodes.show', $id)
      ->with('success', 'Device information updated successfully!');
  }
}
