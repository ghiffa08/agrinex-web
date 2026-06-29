<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\SensorNodeData;
use App\Models\NodeLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NodesController extends Controller
{
  public function index()
  {
    // Get all nodes
    $nodes = Node::with(['sensorData' => function($query) {
        $query->latest('received_at')->limit(1);
    }])->orderBy('id')->get();

    // Get latest log for each node
    $latestLogs = NodeLog::select('node_logs.*')
      ->from('node_logs')
      ->join(
        \DB::raw('(SELECT node_id, MAX(waktu) as max_waktu FROM node_logs GROUP BY node_id) as latest'),
        function ($join) {
          $join->on('node_logs.node_id', '=', 'latest.node_id')
            ->on('node_logs.waktu', '=', 'latest.max_waktu');
        }
      )
      ->get();

    // Attach latest log to each node
    foreach ($nodes as $node) {
      $node->latestLog = $latestLogs->firstWhere('node_id', $node->node_id);
      $node->is_online = $node->latestLog && 
                         $node->latestLog->status === 'Aktif' &&
                         now()->diffInHours($node->latestLog->waktu) < 24;
      $node->latestSensorData = $node->sensorData->first();
    }

    // Calculate statistics based on latest logs only
    $stats = [
      'total' => $nodes->count(),
      'active' => $latestLogs->where('status', 'Aktif')->count(),
      'online' => $latestLogs->where('status', 'Aktif')->count(),
      'offline' => $latestLogs->where('status', 'Non Aktif')->count(),
      'alerts' => $latestLogs->whereNotIn('signal_quality', ['Good', 'Excellent'])->count(),
    ];

    return view('nodes.index', compact('nodes', 'stats'));
  }

  public function show($id)
  {
    $node = Node::findOrFail($id);

    // Get sensor data for the last 24 hours
    $sensorData = SensorNodeData::where('node_id', $node->node_id)
      ->where('received_at', '>=', now()->subDay())
      ->orderBy('received_at', 'asc')
      ->get();

    // Get communication logs for the last 7 days
    $logs = NodeLog::where('node_id', $node->node_id)
      ->where('waktu', '>=', now()->subDays(7))
      ->orderBy('waktu', 'desc')
      ->limit(50)
      ->get();

    $latestSensorData = SensorNodeData::where('node_id', $node->node_id)
      ->latest('received_at')
      ->first();

    // Prepare chart data
    $chartData = [
      'labels' => $sensorData->pluck('received_at')->map(function ($date) {
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
    $node = Node::findOrFail($id);
    return view('nodes.edit', compact('node'));
  }

  /**
   * Update the specified node in storage
   */
  public function update(Request $request, $id)
  {
    $node = Node::findOrFail($id);

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
