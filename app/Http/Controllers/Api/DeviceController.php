<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    /**
     * Get irrigation sessions for a specific device
     */
    public function getIrrigationSessions($deviceId): JsonResponse
    {
        // Mock data - implement when irrigate_logs table available
        return response()->json([
            'success' => true,
            'data' => [],
            'note' => 'irrigate_logs table not available'
        ]);
    }

    /**
     * Get usage history for a specific device
     */
    public function getUsageHistory($deviceId): JsonResponse
    {
        // Mock data - implement when irrigate_logs table available
        return response()->json([
            'success' => true,
            'data' => [],
            'note' => 'irrigate_logs table not available'
        ]);
    }
}
