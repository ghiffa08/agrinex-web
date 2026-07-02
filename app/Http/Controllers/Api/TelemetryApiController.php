<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use App\Models\SensorData;
use App\Models\DeviceLog;
use App\Models\DataSession;

class TelemetryApiController extends Controller
{
    /**
     * Handle incoming telemetry data from ESP32 LoRa Gateway.
     */
    public function store(Request $request)
    {
        try {
            // ── 1. Validate incoming ESP32 payload ──────────────────────────
            $validated = $request->validate([
                'node_id'                 => 'nullable|string|max:32',   // e.g. "SENDER_01"
                'device_id'               => 'required|integer',          // integer node ID
                'session_id'              => 'required|integer',
                'timestamp'               => 'nullable|integer',
                'battery_pct'             => 'nullable|numeric',
                'voltage'                 => 'nullable|numeric',
                'current_ma'              => 'nullable|numeric',
                'power_mw'                => 'nullable|numeric',
                'temperature'             => 'nullable|numeric',
                'soil_moisture'           => 'nullable|numeric',
                'flow_rate'               => 'nullable|numeric',
                'total_volume'            => 'nullable|numeric',
                'rssi'                    => 'nullable|numeric',
                'ai_valve_decision'       => 'nullable|string|max:16',
                'adaptive_sleep_duration' => 'nullable|integer',
            ]);

            $nodeId    = (int) $validated['device_id'];
            $sessionId = (int) $validated['session_id'];

            // ── 2. Auto-register device if not yet known ────────────────────
            $deviceExists = Device::where('id', $nodeId)->exists();
            if (!$deviceExists) {
                Log::info("[Telemetry] Auto-registering new device_id={$nodeId}");
                Device::create([
                    'id'             => $nodeId,
                    'group'          => 'A',
                    'kode_perlakuan' => 'P' . $nodeId,
                    'lokasi'         => 'Otomatis dari API',
                    'keterangan'     => "Device {$nodeId} didaftarkan otomatis oleh ESP32",
                ]);
            }

            // ── 2b. Auto-register session if not yet known ──────────────────
            $session = DataSession::firstOrCreate(
                ['session_id' => $sessionId],
                [
                    'started_at'    => now(),
                    'success_count' => 0,
                    'failed_count'  => 0,
                ]
            );
            $sessionDbId = $session->id;

            // ── 3. Insert into sensor_data ─────────────────────────────
            $sensorRecord = SensorData::create([
                'data_session_id'         => $sessionDbId,
                'device_id'               => $nodeId,
                'voltage_v'               => $validated['voltage'] ?? null,
                'battery_pct'             => $validated['battery_pct'] ?? null,
                'current_ma'              => $validated['current_ma'] ?? null,
                'power_mw'                => $validated['power_mw'] ?? null,
                'temperature'             => $validated['temperature'] ?? null,
                'soil_moisture'           => $validated['soil_moisture'] ?? null,
                'flow_rate'               => $validated['flow_rate'] ?? null,
                'total_volume_l'          => $validated['total_volume'] ?? null,
                'rssi'                    => $validated['rssi'] ?? null,
                'ai_valve_decision'       => $validated['ai_valve_decision'] ?? null,
                'adaptive_sleep_duration' => $validated['adaptive_sleep_duration'] ?? null,
                'recorded_at'             => now(),
            ]);

            // ── 4. Log into device_logs ────────────────────────────────────────
            DeviceLog::create([
                'device_id'      => $nodeId,
                'rssi_dbm'       => $validated['rssi'] ?? null,
                'snr_db'         => null,
                'signal_quality' => $this->rssiToQuality($validated['rssi'] ?? null),
                'is_active'      => true,
                'session_type'   => 'telemetry',
                'session_ref_id' => (string) $sessionId,
                'remarks'        => sprintf(
                    'LoRa node=%s valve=%s sleep=%ss',
                    $validated['node_id'] ?? 'unknown',
                    $validated['ai_valve_decision'] ?? '-',
                    $validated['adaptive_sleep_duration'] ?? '-'
                ),
                'logged_at'      => now(),
            ]);

            // ── 5. Lightweight ACK for ESP32 ─────────────────────────────────
            return response()->json([
                'status' => 'ok',
                'id'     => $sensorRecord->id,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('[Telemetry] Validation failed: ' . json_encode($e->errors()));
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid data format',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('[Telemetry] Exception: ' . $e->getMessage()
                . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response()->json(['status' => 'error', 'message' => 'Server error'], 500);
        }
    }

    /**
     * Convert RSSI dBm to a human-readable quality label.
     */
    private function rssiToQuality(?float $rssi): string
    {
        if ($rssi === null)  return 'Unknown';
        if ($rssi >= -70)   return 'Excellent';
        if ($rssi >= -85)   return 'Good';
        if ($rssi >= -100)  return 'Fair';
        return 'Poor';
    }
}
