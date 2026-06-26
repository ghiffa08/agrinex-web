<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\IrrigationLog;
use App\Models\ValveLog;
use App\Models\Device;
use Illuminate\Http\Request;

class IrrigationController extends Controller
{
    /**
     * Display irrigation dashboard
     */
    public function index()
    {
        // Get recent irrigation sessions
        $irrigationLogs = IrrigationLog::latest('started_at')
            ->paginate(20);

        // Get active irrigation (valve ON status)
        $activeIrrigation = ValveLog::where('valve_status', 'ON')
            ->with('device')
            ->latest('logged_at')
            ->get();

        // Calculate statistics
        $stats = [
            'total_events' => IrrigationLog::count(),
            'today_events' => IrrigationLog::whereDate('started_at', today())->count(),
            'active_valves' => $activeIrrigation->count(),
            'total_duration' => 0, // Obsolete metric
        ];

        // Get nodes with irrigation capability (exclude Node 65 - weather station)
        $nodes = Device::where('id', '!=', 65)->orderBy('id')->get();

        return view('irrigation.index', compact('irrigationLogs', 'activeIrrigation', 'stats', 'nodes'));
    }

    /**
     * Trigger manual irrigation
     */
    public function trigger(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|exists:devices,id',
            'duration' => 'required|integer|min:1|max:3600',
        ]);

        $sesiId = intval(now()->format('YmdHi')); // Store as integer now

        // Create irrigation session
        $irrigationLog = IrrigationLog::create([
            'session_id' => $sesiId,
            'started_at' => now(),
            'success_count' => 1,
            'failed_count' => 0,
            'valve_on_count' => 1,
        ]);

        // Create valve log entry
        ValveLog::create([
            'device_id' => $validated['device_id'],
            'irrigation_log_id' => $irrigationLog->id,
            'valve_status' => 'ON',
            'reason' => 'MANUAL_TRIGGER',
            'logged_at' => now(),
        ]);

        return redirect()->route('irrigation.index')
            ->with('success', 'Manual irrigation triggered successfully for Device ' . $validated['device_id']);
    }

    /**
     * Get irrigation history for specific session
     */
    public function history($sesiId)
    {
        $session = IrrigationLog::where('session_id', $sesiId)->firstOrFail();
        
        // Get valve logs for this session
        $valveLogs = ValveLog::where('irrigation_log_id', $session->id)
            ->with('device')
            ->orderBy('logged_at', 'asc')
            ->get();

        return view('irrigation.history', compact('session', 'valveLogs'));
    }
}
