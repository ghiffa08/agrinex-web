<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    /**
     * Display a listing of the devices.
     */
    public function index(): JsonResponse
    {
        $devices = Device::all();

        return response()->json([
            'success' => true,
            'data' => $devices
        ]);
    }

    /**
     * Store a newly created device in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mac_address' => 'required|string|unique:devices,mac_address',
            'status' => 'nullable|string|max:50',
            'firmware_version' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $device = Device::create([
            'uuid' => (string) Str::uuid(),
            'name' => $request->name,
            'mac_address' => $request->mac_address,
            'status' => $request->input('status', 'offline'),
            'firmware_version' => $request->firmware_version,
            'last_seen_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Device created successfully',
            'data' => $device
        ], 210); // 201 Created
    }

    /**
     * Display the specified device.
     */
    public function show($id): JsonResponse
    {
        $device = Device::where('id', $id)->orWhere('uuid', $id)->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $device
        ]);
    }

    /**
     * Update the specified device in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $device = Device::where('id', $id)->orWhere('uuid', $id)->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'mac_address' => 'sometimes|required|string|unique:devices,mac_address,' . $device->id,
            'status' => 'sometimes|required|string|max:50',
            'firmware_version' => 'sometimes|nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $device->update($request->only(['name', 'mac_address', 'status', 'firmware_version']));

        return response()->json([
            'success' => true,
            'message' => 'Device updated successfully',
            'data' => $device
        ]);
    }

    /**
     * Remove the specified device from storage.
     */
    public function destroy($id): JsonResponse
    {
        $device = Device::where('id', $id)->orWhere('uuid', $id)->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found'
            ], 404);
        }

        $device->delete();

        return response()->json([
            'success' => true,
            'message' => 'Device deleted successfully'
        ]);
    }

    /**
     * Get irrigation sessions for a specific device (Legacy support)
     */
    public function getIrrigationSessions($deviceId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [],
            'note' => 'Legacy method. Use structured Device/Telemetry API endpoints.'
        ]);
    }

    /**
     * Get usage history for a specific device (Legacy support)
     */
    public function getUsageHistory($deviceId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [],
            'note' => 'Legacy method. Use structured Device/Telemetry API endpoints.'
        ]);
    }
}
