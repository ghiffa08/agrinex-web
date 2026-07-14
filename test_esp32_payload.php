<?php
/**
 * Mock ESP32 Payload Test
 * Test sensor data ingestion after database normalization
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\SensorDataService;
use Illuminate\Support\Facades\DB;

echo "=== ESP32 MOCK PAYLOAD TEST ===\n\n";

// Mock sensor data payload (dari ESP32)
$sessionId = time(); // Use integer timestamp
$mockPayload = [
    'metadata' => [
        'sesi_id_getdata' => $sessionId,
        'timestamp' => now()->toDateTimeString(),
    ],
    'data' => [
        'getdata_logs' => [
            [
                'sesi_id_getdata' => $sessionId,
                'waktu_mulai' => now()->toDateTimeString(),
                'waktu_selesai' => now()->addSeconds(30)->toDateTimeString(),
                'jumlah_node' => 1,
                'node_sukses' => 1,
                'node_gagal' => 0,
            ]
        ],
        'sensor_node_data' => [
            [
                'node_id' => 1, // Use existing device ID
                'voltage_v' => 3.7,
                'battery_pct' => 85,
                'current_ma' => 120,
                'power_mw' => 444,
                'temp_c' => 28.5,
                'soil_pct' => 45.2,
                'soil_adc' => 2048,
                'ts_counter' => 1001,
            ]
        ],
        'sensor_weather_data' => [
            [
                'node_id' => 1, // Use existing device ID (weather station)
                'temp_dht' => 30.2,
                'humidity' => 65.5,
                'light' => 850.0,
                'voltage' => 5.0,
                'arus' => 200,
                'power' => 1000,
            ]
        ],
        'node_logs' => [
            [
                'node_id' => 1,
                'rssi_dbm' => -65,
                'snr_db' => 8.5,
                'signal_quality' => 'good',
                'status' => 'success',
                'waktu' => now()->toDateTimeString(),
            ]
        ],
    ],
    'statistics' => [
        'node_status' => [
            'completeness_percentage' => 100
        ]
    ]
];

echo "📦 Mock Payload Created:\n";
echo "   - Session ID: " . $mockPayload['metadata']['sesi_id_getdata'] . "\n";
echo "   - Sensor data: 1 record\n";
echo "   - Weather data: 1 record\n";
echo "   - Node logs: 1 record\n\n";

try {
    $service = app(SensorDataService::class);
    
    echo "🚀 Processing payload...\n";
    
    DB::beginTransaction();
    $result = $service->processSensorData($mockPayload);
    DB::commit();
    
    echo "\n✅ SUCCESS! Data inserted:\n";
    echo "   - Session ID: " . $result['sesi_id_getdata'] . "\n";
    echo "   - Total records: " . $result['total_inserted'] . "\n";
    echo "   - Details: " . json_encode($result['inserted_records'], JSON_PRETTY_PRINT) . "\n";
    echo "\n✅ ESP32 payload processing WORKS!\n\n";
    
    echo "=== VERIFICATION ===\n";
    echo "Checking database records...\n";
    
    $sessionCount = DB::table('data_sessions')->where('session_id', $mockPayload['metadata']['sesi_id_getdata'])->count();
    $sessionRecord = DB::table('data_sessions')->where('session_id', $mockPayload['metadata']['sesi_id_getdata'])->first();
    $sessionIdPK = $sessionRecord ? $sessionRecord->id : null;
    
    $sensorCount = $sessionIdPK ? DB::table('sensor_data')->where('data_session_id', $sessionIdPK)->count() : 0;
    $weatherCount = $sessionIdPK ? DB::table('weather_data')->where('data_session_id', $sessionIdPK)->count() : 0;
    $deviceLogCount = DB::table('device_logs')->where('session_type', 'getdata')->latest('id')->limit(1)->count();
    
    echo "   - DataSession records: $sessionCount\n";
    echo "   - SensorData records: $sensorCount\n";
    echo "   - WeatherData records: $weatherCount\n";
    echo "   - DeviceLog records: $deviceLogCount\n";
    
    if ($sessionCount > 0 && $sensorCount > 0 && $weatherCount > 0 && $deviceLogCount > 0) {
        echo "\n✅ ALL RECORDS SAVED SUCCESSFULLY!\n";
        echo "✅ ESP32 data ingestion is WORKING after normalization!\n";
    } else {
        echo "\n❌ MISSING RECORDS - CHECK LOGS\n";
    }
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n=== TEST COMPLETE ===\n";
