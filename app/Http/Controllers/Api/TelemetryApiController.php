<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DataSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelemetryApiController extends Controller
{
    /**
     * Handle incoming telemetry data from ESP32
     */
    public function store(Request $request)
    {
        // ESP32 usually sends JSON payload
        try {
            // Validate basic payload
            $validated = $request->validate([
                'device_id' => 'required',
                'session_id' => 'nullable|integer', 
                'battery_pct' => 'nullable|numeric',
                'temperature' => 'nullable|numeric',
                'soil_moisture' => 'nullable|numeric',
                'flow_rate' => 'nullable|numeric',
                'rssi' => 'nullable|numeric',
                'voltage_v' => 'nullable|numeric',
                'current_ma' => 'nullable|numeric',
                'power_mw' => 'nullable|numeric',
                'soil_adc' => 'nullable|integer',
                'light_lux' => 'nullable|numeric',
                'rain_pct' => 'nullable|numeric',
                'rain_adc' => 'nullable|integer',
                'wind_speed' => 'nullable|numeric',
                'humidity_pct' => 'nullable|numeric',
                'is_weather_node' => 'nullable|boolean',
                'ai_valve_decision' => 'nullable|string',
                'adaptive_sleep_duration' => 'nullable|integer',
                'total_volume_l' => 'nullable|numeric'
            ]);

            // 1. Verify Device exists
            $device = Device::find($validated['device_id']);
            
            if (!$device) {
                Log::warning("Unauthorized ESP32 telemetry attempt. Device ID: {$validated['device_id']}");
                // Return 401 Unauthorized for security
                return response()->json(['status' => 'error', 'message' => 'Unauthorized device'], 401);
            }

            // 2. Find or Create Data Session
            $session = DataSession::firstOrCreate(
                ['session_id' => $validated['session_id']],
                ['started_at' => now()]
            );

            // 3. Store Telemetry Data
            if (!empty($validated['is_weather_node']) && $validated['is_weather_node']) {
                // Save to weather_data
                $record = $device->weatherData()->create([
                    'data_session_id' => $session->id,
                    'voltage_v' => $validated['voltage_v'] ?? null,
                    'current_ma' => $validated['current_ma'] ?? null,
                    'power_mw' => $validated['power_mw'] ?? null,
                    'light_lux' => $validated['light_lux'] ?? null,
                    'rain_pct' => $validated['rain_pct'] ?? null,
                    'rain_adc' => $validated['rain_adc'] ?? null,
                    'wind_speed' => $validated['wind_speed'] ?? null,
                    'humidity_pct' => $validated['humidity_pct'] ?? null,
                    'temp_c' => $validated['temp_c'] ?? null,
                ]);
            } else {
                // Save to sensor_data (Soil node)
                $record = $device->sensorData()->create([
                    'data_session_id' => $session->id,
                    'voltage_v' => $validated['voltage_v'] ?? null,
                    'battery_pct' => $validated['battery_pct'] ?? null,
                    'current_ma' => $validated['current_ma'] ?? null,
                    'power_mw' => $validated['power_mw'] ?? null,
                    'temperature' => $validated['temperature'] ?? ($validated['temp_c'] ?? null),
                    'soil_moisture' => $validated['soil_moisture'] ?? ($validated['soil_pct'] ?? null),
                    'flow_rate' => $validated['flow_rate'] ?? null,
                    'total_volume_l' => $validated['total_volume_l'] ?? null,
                    'soil_adc' => $validated['soil_adc'] ?? null,
                    'ai_valve_decision' => $validated['ai_valve_decision'] ?? null,
                    'adaptive_sleep_duration' => $validated['adaptive_sleep_duration'] ?? null,
                    'rssi' => $validated['rssi'] ?? null,
                ]);
            }

            // Log the communication
            $device->logs()->create([
                'is_active' => true,
                'session_type' => 'telemetry',
                'signal_quality' => 'Good', // Optional: accept from request if ESP32 sends RSSI
                'remarks' => 'Data received via HTTP API'
            ]);

            // 4. Return extremely lightweight JSON for ESP32 memory efficiency
            return response()->json([
                'status' => 'success',
                'message' => 'Data & Edge Metrics saved'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => 'Invalid data format'], 422);
        } catch (\Exception $e) {
            Log::error("ESP32 Telemetry Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Server error'], 500);
        }
    }
}
