<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DeviceService;
use App\Services\SensorDataService;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardPollingController extends Controller
{
    protected DeviceService $deviceService;
    protected SensorDataService $sensorDataService;
    protected CacheService $cacheService;

    public function __construct(
        DeviceService $deviceService,
        SensorDataService $sensorDataService,
        CacheService $cacheService
    ) {
        $this->deviceService = $deviceService;
        $this->sensorDataService = $sensorDataService;
        $this->cacheService = $cacheService;
    }

    /**
     * Polling endpoint untuk dashboard
     * GET /api/v1/dashboard/poll
     */
    public function poll(Request $request)
    {
        try {
            $lastClientUpdate = (int) $request->query('last_update', 0);
            $serverLastUpdate = $this->cacheService->getDashboardLastUpdate();

            // Check if there are changes
            if ($lastClientUpdate >= $serverLastUpdate) {
                return response()->json([
                    'success' => true,
                    'has_changes' => false,
                    'last_update' => $serverLastUpdate,
                ]);
            }

            // Ada perubahan, kirim data lengkap
            $devicesData = $this->deviceService->getAllDevicesWithLatestData();
            $weatherData = $this->sensorDataService->getLatestWeatherData();

            return response()->json([
                'success' => true,
                'has_changes' => true,
                'last_update' => $serverLastUpdate ?: now()->timestamp,
                'data' => [
                    'devices' => $devicesData,
                    'weather' => $weatherData,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Dashboard polling error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Polling failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Polling ringan untuk status devices saja
     * GET /api/v1/dashboard/poll-status
     */
    public function pollStatus(Request $request)
    {
        try {
            $lastClientUpdate = (int) $request->query('last_update', 0);
            $serverLastUpdate = $this->cacheService->getDashboardLastUpdate();

            if ($lastClientUpdate >= $serverLastUpdate) {
                return response()->json([
                    'success' => true,
                    'has_changes' => false,
                    'last_update' => $serverLastUpdate,
                ]);
            }

            // Hanya kirim status singkat tanpa data sensor lengkap
            $devices = $this->cacheService->remember(
                'dashboard_status_only',
                CacheService::TTL_SHORT,
                fn() => $this->deviceService->getDevicesStatusOnly()
            );

            return response()->json([
                'success' => true,
                'has_changes' => true,
                'last_update' => $serverLastUpdate ?: now()->timestamp,
                'data' => [
                    'devices' => $devices,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Status polling error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Status polling failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
