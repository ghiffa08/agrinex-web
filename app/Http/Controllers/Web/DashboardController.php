<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\DashboardRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardRepositoryInterface $dashboardRepo
    ) {}

    /**
     * Admin dashboard - statistics view
     */
    public function index()
    {
        $devices = $this->dashboardRepo->getDevices();
        $tank    = $this->dashboardRepo->getTank();

        $stats = [
            'total_nodes'        => count($devices),
            'active_nodes'       => count(array_filter($devices, fn ($d) => $d['connection_state'] === 'online')),
            'total_plots'        => count(array_filter($devices, fn ($d) => !empty($d['kode_perlakuan']))),
            'active_alerts'      => 0,
            'ongoing_irrigation' => 0,
        ];

        return view('dashboard.index', compact('stats', 'devices', 'tank'));
    }

    /**
     * Chart data for a specific node - delegated to API
     */
    public function chartData(Request $request): JsonResponse
    {
        // Thin proxy - actual logic lives in DeviceService via DeviceController
        return response()->json([
            'message' => 'Use /api/devices/{id}/chart-data instead',
        ], 301);
    }

    /**
     * Realtime data - delegated to API
     */
    public function realtimeData(): JsonResponse
    {
        $devices = $this->dashboardRepo->getDevices();

        return response()->json([
            'nodes'     => array_map(fn ($d) => [
                'node_id'      => $d['id'],
                'soil_moisture' => $d['soil_moisture_pct'],
                'temperature'   => $d['temperature_c'],
                'last_reading'  => $d['last_seen'],
            ], $devices),
            'timestamp' => now(),
        ]);
    }

    /**
     * System monitor page
     */
    public function monitor()
    {
        return view('monitor');
    }
}
