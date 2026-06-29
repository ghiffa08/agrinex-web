<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GetdataLog;
use App\Models\IrrigateLog;
use App\Models\Node;
use App\Models\NodeLog;
use App\Models\SensorNodeData;
use App\Models\SensorWeatherData;
use App\Models\ValveLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the dashboard
     */
    public function index()
    {
        // Get all devices (formerly nodes)
        $nodes = Node::all();

        // If there are no nodes, show the setup wizard
        if ($nodes->isEmpty()) {
            return view('dashboard.setup');
        }
        
        // Statistics
        $stats = [
            'total_nodes' => $nodes->count(),
            'active_nodes' => $this->getActiveNodes(),
            'total_plots' => $nodes->whereNotNull('kode_perlakuan')->count(),
            'active_alerts' => $this->getActiveAlerts(),
            'ongoing_irrigation' => IrrigateLog::whereNull('waktu_akhir')->count(),
        ];

        // Get latest sensor readings for each device
        $nodesWithData = $nodes->map(function ($node) {
            $latestData = SensorNodeData::where('node_id', $node->node_id)
                ->latest('received_at')
                ->first();
            
            $latestLog = NodeLog::where('node_id', $node->node_id)
                ->latest('waktu')
                ->first();
            
            $node->latestReading = $latestData;
            $node->lastCommunication = $latestLog?->waktu;
            $node->is_active = $latestLog && Carbon::parse($latestLog->waktu)->gt(Carbon::now()->subHours(24));
            
            return $node;
        });

        // Get latest weather data (Assuming Device ID 65 is still weather)
        $weather = SensorWeatherData::where('node_id', 65)
            ->latest('received_at')
            ->first();

        // Get recent alerts (from node logs with status issues)
        $recentAlerts = $this->getRecentAlerts();

        // Get today's irrigation events
        $todayIrrigation = IrrigateLog::whereDate('waktu_mulai', Carbon::today())
            ->latest('waktu_mulai')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'nodes',
            'nodesWithData',
            'weather',
            'recentAlerts',
            'todayIrrigation'
        ));
    }

    /**
     * Get chart data for specific node
     */
    public function chartData(Request $request)
    {
        $nodeId = $request->input('node_id');
        $hours = $request->input('hours', 24);
        
        $startTime = Carbon::now()->subHours($hours);
        
        // Get sensor node data
        $sensorData = SensorNodeData::where('node_id', $nodeId)
            ->where('received_at', '>=', $startTime)
            ->orderBy('received_at')
            ->get();
        
        // Get weather data
        $weatherData = SensorWeatherData::where('node_id', 65)
            ->where('received_at', '>=', $startTime)
            ->orderBy('received_at')
            ->get();
        
        $labels = $sensorData->pluck('received_at')->map(function ($date) {
            return Carbon::parse($date)->format('H:i');
        });
        
        return response()->json([
            'labels' => $labels,
            'soil_moisture' => $sensorData->pluck('soil_pct'),
            'soil_temperature' => $sensorData->pluck('temp_c'),
            'air_temperature' => $weatherData->pluck('temp_dht'),
            'air_humidity' => $weatherData->pluck('humidity'),
        ]);
    }

    /**
     * Get realtime data for dashboard refresh
     */
    public function realtimeData()
    {
        $nodes = Node::all()->map(function ($node) {
            $latestData = SensorNodeData::where('node_id', $node->node_id)
                ->latest('received_at')
                ->first();
            
            return [
                'node_id' => $node->node_id,
                'node_code' => $node->node_id,
                'soil_moisture' => $latestData?->soil_pct,
                'temperature' => $latestData?->temp_c,
                'last_reading' => $latestData?->received_at,
            ];
        });

        return response()->json([
            'nodes' => $nodes,
            'active_alerts' => $this->getActiveAlerts(),
            'timestamp' => now(),
        ]);
    }

    /**
     * Show the monitor page
     */
    public function monitor()
    {
        return view('monitor');
    }

    /**
     * Show the standalone Web Serial Flasher page
     */
    public function flasher()
    {
        return view('dashboard.flasher');
    }

    /**
     * Get available serial ports on the server
     */
    public function getPorts()
    {
        $ports = [];
        // Scan standard Linux USB-Serial device paths
        foreach (glob('/dev/ttyUSB*') as $port) {
            $ports[] = $port;
        }
        foreach (glob('/dev/ttyACM*') as $port) {
            $ports[] = $port;
        }
        
        // Fallback: query python serial ports scanner if available
        if (empty($ports)) {
            $pythonCmd = "python3 -c \"import serial.tools.list_ports; print([p.device for p in serial.tools.list_ports.comports()])\" 2>/dev/null";
            $output = shell_exec($pythonCmd);
            if ($output) {
                $decoded = json_decode(str_replace("'", '"', trim($output)));
                if (is_array($decoded)) {
                    $ports = $decoded;
                }
            }
        }
        
        return response()->json(['ports' => $ports]);
    }

    /**
     * Modify the config.h file inside the target PlatformIO project
     */
    private function updateConfigHeader($fwType, $ssid, $password, $override, $pinTemp, $pinFlow, $pinSoil)
    {
        $folders = [
            'tester' => 'agrinex_node_tester',
            'sender' => 'NODE01-Sender',
            'receiver' => 'NODE02-Receiver',
        ];
        
        $folder = $folders[$fwType] ?? 'agrinex_node_tester';
        $configPath = base_path('../' . $folder . '/include/config.h');
        
        if (!file_exists($configPath)) {
            return false;
        }
        
        $content = file_get_contents($configPath);
        
        // Inject SSID and Password
        if (preg_match('/#define WIFI_SSID\s+/', $content)) {
            $content = preg_replace('/#define WIFI_SSID\s+.*/', '#define WIFI_SSID       "' . $ssid . '"', $content);
        }
        if (preg_match('/#define WIFI_PASSWORD\s+/', $content)) {
            $content = preg_replace('/#define WIFI_PASSWORD\s+.*/', '#define WIFI_PASSWORD   "' . $password . '"', $content);
        }
        
        if ($override) {
            if (preg_match('/#define PIN_TEMP\s+/', $content)) {
                $content = preg_replace('/#define PIN_TEMP\s+.*/', '#define PIN_TEMP        ' . $pinTemp, $content);
            }
            if (preg_match('/#define PIN_FLOW_METER\s+/', $content)) {
                $content = preg_replace('/#define PIN_FLOW_METER\s+.*/', '#define PIN_FLOW_METER  ' . $pinFlow, $content);
            }
            if (preg_match('/#define PIN_SOIL\s+/', $content)) {
                $content = preg_replace('/#define PIN_SOIL\s+.*/', '#define PIN_SOIL        ' . $pinSoil, $content);
            }
        }
        
        file_put_contents($configPath, $content);
        return true;
    }

    /**
     * Run PlatformIO upload command and stream terminal log output
     */
    public function executeFlash(Request $request)
    {
        $firmware = $request->input('firmware', 'tester');
        $ssid = $request->input('ssid', 'AgriNex_Tester');
        $password = $request->input('password', '12345678');
        $port = $request->input('port');
        $clean = filter_var($request->input('clean'), FILTER_VALIDATE_BOOLEAN);
        $override = filter_var($request->input('override'), FILTER_VALIDATE_BOOLEAN);
        $pinTemp = $request->input('pin_temp', '2');
        $pinFlow = $request->input('pin_flow', '4');
        $pinSoil = $request->input('pin_soil', '1');
        
        // Update header config file
        $this->updateConfigHeader($firmware, $ssid, $password, $override, $pinTemp, $pinFlow, $pinSoil);
        
        $folders = [
            'tester' => 'agrinex_node_tester',
            'sender' => 'NODE01-Sender',
            'receiver' => 'NODE02-Receiver',
        ];
        $folder = $folders[$firmware] ?? 'agrinex_node_tester';
        $projectDir = base_path('../' . $folder);
        
        // Find pio binary
        $pioBin = exec('which pio') ?: trim(shell_exec('echo $HOME')) . '/.local/bin/pio';
        $pioBin = trim($pioBin);
        
        return response()->stream(function () use ($pioBin, $projectDir, $port, $clean) {
            // Set output headers
            if (connection_aborted()) return;
            
            // Build command
            $commands = [];
            if ($clean) {
                $commands[] = [$pioBin, 'run', '-t', 'clean', '-d', $projectDir];
            }
            
            if ($port) {
                $commands[] = [$pioBin, 'run', '-t', 'upload', '-d', $projectDir, '--upload-port', $port];
            } else {
                $commands[] = [$pioBin, 'run', '-d', $projectDir];
            }
            
            foreach ($commands as $cmdArr) {
                $cmd = implode(' ', array_map('escapeshellarg', $cmdArr));
                echo "event: log\n";
                echo "data: " . json_encode(['text' => '$ ' . implode(' ', $cmdArr), 'color' => 'text-sky-400']) . "\n\n";
                ob_flush();
                flush();
                
                $descriptorspec = [
                    0 => ["pipe", "r"], // stdin
                    1 => ["pipe", "w"], // stdout
                    2 => ["pipe", "w"]  // stderr
                ];
                
                $process = proc_open($cmd, $descriptorspec, $pipes);
                
                if (is_resource($process)) {
                    while ($line = fgets($pipes[1])) {
                        $line = trim($line);
                        if ($line === '') continue;
                        
                        $color = 'text-slate-300';
                        if (stripos($line, 'error') !== false) {
                            $color = 'text-rose-500';
                        } elseif (stripos($line, 'warning') !== false) {
                            $color = 'text-amber-500';
                        } elseif (strpos($line, '[') === 0 && strpos($line, ']') !== false) {
                            $color = 'text-sky-400';
                        } elseif (stripos($line, 'uploading') !== false || stripos($line, 'writing') !== false || stripos($line, 'success') !== false) {
                            $color = 'text-emerald-400';
                        }
                        
                        echo "event: log\n";
                        echo "data: " . json_encode(['text' => $line, 'color' => $color]) . "\n\n";
                        ob_flush();
                        flush();
                    }
                    
                    fclose($pipes[0]);
                    fclose($pipes[1]);
                    fclose($pipes[2]);
                    
                    $return_value = proc_close($process);
                    if ($return_value !== 0) {
                        echo "event: log\n";
                        echo "data: " . json_encode(['text' => 'Process terminated with non-zero exit code.', 'color' => 'text-rose-500']) . "\n\n";
                        ob_flush();
                        flush();
                        return;
                    }
                } else {
                    echo "event: log\n";
                    echo "data: " . json_encode(['text' => 'Failed to start flashing process.', 'color' => 'text-rose-500']) . "\n\n";
                    ob_flush();
                    flush();
                    return;
                }
            }
            
            echo "event: success\n";
            echo "data: " . json_encode(['message' => 'Flashing completed successfully.']) . "\n\n";
            ob_flush();
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no'
        ]);
    }

    /**
     * Get count of active nodes (communicated in last 24 hours)
     */
    private function getActiveNodes()
    {
        return NodeLog::where('status', 'Aktif')
            ->where('waktu', '>=', Carbon::now()->subHours(24))
            ->distinct('node_id')
            ->count();
    }

    /**
     * Get active alerts from node logs
     */
    private function getActiveAlerts()
    {
        return NodeLog::where('waktu', '>=', Carbon::now()->subHours(24))
            ->where(function ($query) {
                $query->where('status', 'Non Aktif')
                    ->orWhereRaw("LOWER(keterangan) LIKE '%error%'")
                    ->orWhereRaw("LOWER(keterangan) LIKE '%gagal%'")
                    ->orWhereRaw("LOWER(keterangan) LIKE '%timeout%'");
            })
            ->count();
    }

    /**
     * Get recent alerts
     */
    private function getRecentAlerts()
    {
        $alerts = [];
        
        // Check for nodes with communication issues
        $failedNodes = NodeLog::where('waktu', '>=', Carbon::now()->subHours(24))
            ->where('status', 'Non Aktif')
            ->latest('waktu')
            ->limit(5)
            ->get();
        
        foreach ($failedNodes as $log) {
            $alerts[] = (object)[
                'severity' => 'warning',
                'message' => "Node {$log->node_id} communication failed",
                'timestamp' => $log->waktu,
            ];
        }
        
        // Check for low soil moisture
        $lowMoisture = SensorNodeData::where('received_at', '>=', Carbon::now()->subHours(24))
            ->where('soil_pct', '<', 30)
            ->latest('received_at')
            ->limit(3)
            ->get();
        
        foreach ($lowMoisture as $reading) {
            $alerts[] = (object)[
                'severity' => 'critical',
                'message' => "Low soil moisture on Node {$reading->node_id} ({$reading->soil_pct}%)",
                'timestamp' => $reading->received_at,
            ];
        }
        
        // Sort by timestamp
        usort($alerts, function ($a, $b) {
            return Carbon::parse($b->timestamp)->timestamp - Carbon::parse($a->timestamp)->timestamp;
        });
        
        return array_slice($alerts, 0, 5);
    }

    /**
     * Proxy request to ESP32 AP sensor endpoint
     */
    public function proxySensor()
    {
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(3)->get('http://192.168.4.1/api/sensor');
            if ($response->successful()) {
                return response()->json($response->json());
            }
            return response()->json(['error' => 'ESP32 returned status ' . $response->status()], 502);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Connection to ESP32 failed: ' . $e->getMessage()], 502);
        }
    }

    /**
     * Proxy request to ESP32 AP relay control endpoint
     */
    public function proxyRelay(Request $request)
    {
        $state = $request->input('state', '0');
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(3)->get('http://192.168.4.1/api/relay', [
                'state' => $state
            ]);
            if ($response->successful()) {
                return response()->json($response->json());
            }
            return response()->json(['error' => 'ESP32 returned status ' . $response->status()], 502);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Connection to ESP32 failed: ' . $e->getMessage()], 502);
        }
    }

    /**
     * Stream serial logs from target server port using Python & SSE
     */
    public function streamSerial(Request $request)
    {
        $port = $request->input('port');
        $baud = $request->input('baud', 115200);

        if (!$port) {
            return response()->json(['error' => 'Port is required'], 400);
        }

        return response()->stream(function () use ($port, $baud) {
            if (connection_aborted()) return;

            // Start a python subprocess that reads from the serial port and flushes line by line
            // -u flag is for unbuffered binary stdout and stderr
            $pythonCmd = 'python3 -u -c ' . escapeshellarg('
import serial, sys, time
try:
    s = serial.Serial("' . $port . '", ' . intval($baud) . ', timeout=0.5)
    while True:
        line = s.readline()
        if line:
            sys.stdout.write(line.decode("utf-8", errors="ignore"))
            sys.stdout.flush()
        time.sleep(0.01)
except Exception as e:
    sys.stdout.write("ERROR: " + str(e) + "\n")
    sys.stdout.flush()
');

            $descriptorspec = [
                0 => ["pipe", "r"], // stdin
                1 => ["pipe", "w"], // stdout
                2 => ["pipe", "w"]  // stderr
            ];

            $process = proc_open($pythonCmd, $descriptorspec, $pipes);

            if (is_resource($process)) {
                // Set stream to non-blocking
                stream_set_blocking($pipes[1], 0);

                while (!connection_aborted()) {
                    $line = fgets($pipes[1]);
                    if ($line !== false && $line !== '') {
                        $line = trim($line);
                        echo "event: log\n";
                        echo "data: " . json_encode(['text' => $line]) . "\n\n";
                        ob_flush();
                        flush();
                    }
                    usleep(10000); // 10ms
                }

                // Close handles and terminate process
                fclose($pipes[0]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_terminate($process);
                proc_close($process);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no'
        ]);
    }
}
