<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\SensorLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TelemetryController extends Controller
{
    /**
     * Ingest telemetry data from ESP32 devices.
     */
    public function ingest(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'mac_address' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'soil_moisture' => 'required|numeric',
            'battery_level' => 'required|numeric',
            'firmware_version' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Clean MAC address format
        $mac = strtoupper(trim($request->mac_address));

        // Find or create device by MAC address
        $device = Device::firstOrCreate(
            ['mac_address' => $mac],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'ESP32_' . substr(str_replace(':', '', $mac), -6),
                'status' => 'online',
                'firmware_version' => $request->firmware_version ?? '1.0.0',
                'last_seen_at' => now(),
            ]
        );

        // Update existing device parameters
        $device->update([
            'status' => 'online',
            'firmware_version' => $request->firmware_version ?? $device->firmware_version,
            'last_seen_at' => now(),
        ]);

        // Log telemetry
        $log = SensorLog::create([
            'device_id' => $device->id,
            'temperature' => $request->temperature,
            'humidity' => $request->humidity,
            'soil_moisture' => $request->soil_moisture,
            'battery_level' => $request->battery_level,
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Telemetry ingested successfully',
            'data' => [
                'device_id' => $device->id,
                'log_id' => $log->id,
                'timestamp' => $log->created_at->toDateTimeString()
            ]
        ]);
    }
}
