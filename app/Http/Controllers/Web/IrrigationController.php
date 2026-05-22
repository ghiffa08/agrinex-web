<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\IrrigateLog;
use App\Models\ValveLog;
use App\Models\Node;
use Illuminate\Http\Request;

class IrrigationController extends Controller
{
    /**
     * Display irrigation dashboard
     */
    public function index()
    {
        // Get recent irrigation sessions
        $irrigationLogs = IrrigateLog::latest('waktu_mulai')
            ->paginate(20);

        // Get active irrigation (valve ON status)
        $activeIrrigation = ValveLog::where('status', 'ON')
            ->with('node')
            ->latest('waktu')
            ->get();

        // Calculate statistics
        $stats = [
            'total_events' => IrrigateLog::count(),
            'today_events' => IrrigateLog::whereDate('waktu_mulai', today())->count(),
            'active_valves' => $activeIrrigation->count(),
            'total_duration' => ValveLog::whereDate('waktu', today())->sum('durasi_detik') / 60, // Convert to minutes
        ];

        // Get nodes with irrigation capability (exclude Node 65 - weather station)
        $nodes = Node::where('node_id', '!=', 65)->orderBy('node_id')->get();

        return view('irrigation.index', compact('irrigationLogs', 'activeIrrigation', 'stats', 'nodes'));
    }

    /**
     * Trigger manual irrigation
     */
    public function trigger(Request $request)
    {
        $validated = $request->validate([
            'node_id' => 'required|exists:node,node_id',
            'duration' => 'required|integer|min:1|max:3600',
        ]);

        $sesiId = 'MANUAL_' . now()->format('YmdHis');

        // Create irrigation session
        IrrigateLog::create([
            'sesi_id_irrigate' => $sesiId,
            'waktu_mulai' => now(),
            'node_sukses' => $validated['node_id'],
            'node_gagal' => 0,
            'valve_on_akhir' => 0,
        ]);

        // Create valve log entry
        ValveLog::create([
            'node_id' => $validated['node_id'],
            'sesi_id_irrigate' => $sesiId,
            'durasi_detik' => $validated['duration'],
            'status' => 'ON',
            'waktu' => now(),
        ]);

        return redirect()->route('irrigation.index')
            ->with('success', 'Manual irrigation triggered successfully for Node ' . $validated['node_id']);
    }

    /**
     * Get irrigation history for specific session
     */
    public function history($sesiId)
    {
        $session = IrrigateLog::where('sesi_id_irrigate', $sesiId)->firstOrFail();
        
        // Get valve logs for this session
        $valveLogs = ValveLog::where('sesi_id_irrigate', $sesiId)
            ->with('node')
            ->orderBy('waktu', 'asc')
            ->get();

        return view('irrigation.history', compact('session', 'valveLogs'));
    }
}
