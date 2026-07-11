<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DeviceService;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    public function __construct(protected DeviceService $deviceService) {}

    /**
     * GET /api/devices/{deviceId}/irrigation/sessions
     */
    public function getIrrigationSessions(int|string $deviceId): JsonResponse
    {
        $data = $this->deviceService->getIrrigationSessions($deviceId);

        return response()->json([
            'success'  => true,
            'sessions' => $data['sessions'],
            'summary'  => $data['summary'],
        ]);
    }

    /**
     * GET /api/devices/{deviceId}/usage-history
     */
    public function getUsageHistory(int|string $deviceId): JsonResponse
    {
        $data = $this->deviceService->getUsageHistory($deviceId);

        return response()->json([
            'success' => true,
            'history' => $data['history'],
        ]);
    }

    /**
     * GET /api/devices/{deviceId}/chart-data
     */
    public function getChartData(int|string $deviceId): JsonResponse
    {
        try {
            $data = $this->deviceService->getChartData($deviceId);

            return response()->json([
                'success'  => true,
                'labels'   => $data['labels'],
                'datasets' => $data['datasets'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching chart data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
