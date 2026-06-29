<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TelemetryApiController extends Controller
{
    /**
     * Handle incoming telemetry data from ESP32 LoRa Gateway.
     *
     * Real DB schema (u802160697_agrinew):
     *   - node             : device registry  (node_id = integer)
     *   - sensor_node_data : sensor readings
     *   - node_logs        : signal / session logs
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
            if (!empty($validated['node_id']) && preg_match('/\d+/', $validated['node_id'], $matches)) {
                $nodeId = (int) $matches[0];
            }
            $sessionId = (int) $validated['session_id'];

            // ── 2. Auto-register node if not yet known ──────────────────────
            $nodeExists = DB::table('node')->where('node_id', $nodeId)->exists();
            if (!$nodeExists) {
                Log::info("[Telemetry] Auto-registering new node_id={$nodeId}");
                DB::table('node')->insert([
                    'node_id'        => $nodeId,
                    'group'          => null,
                    'kode_perlakuan' => null,
                    'lokasi'         => 'Otomatis dari API',
                    'keterangan'     => "Node {$nodeId} didaftarkan otomatis oleh ESP32",
                    'waktu_dibuat'   => now(),
                    'waktu_update'   => now(),
                ]);
            }

            // ── 3. Insert into sensor_node_data (trigger will auto-log to node_logs) ──
            $recordId = DB::table('sensor_node_data')->insertGetId([
                'sesi_id_getdata'         => $sessionId,
                'node_id'                 => $nodeId,
                'voltage_v'               => $validated['voltage'] ?? null,
                'battery_pct'             => $validated['battery_pct'] ?? null,
                'current_ma'              => $validated['current_ma'] ?? null,
                'power_mw'                => $validated['power_mw'] ?? null,
                'flow_rate'               => $validated['flow_rate'] ?? null,
                'total_volume_l'          => $validated['total_volume'] ?? null,
                'temp_c'                  => $validated['temperature'] ?? null,
                'soil_pct'                => $validated['soil_moisture'] ?? null,
                'soil_adc'                => null,
                'ai_valve_decision'       => $validated['ai_valve_decision'] ?? null,
                'adaptive_sleep_duration' => $validated['adaptive_sleep_duration'] ?? null,
                'rssi'                    => $validated['rssi'] ?? null,
                'ts_counter'              => $validated['timestamp'] ?? null,
                'received_at'             => now(),
            ]);

            // ── 5. Lightweight ACK for ESP32 ─────────────────────────────────
            return response()->json([
                'status' => 'ok',
                'id'     => $recordId,
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
