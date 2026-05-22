<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class TestConnectionController extends Controller
{
    /**
     * Show test connection page
     */
    public function index()
    {
        $tests = [];

        // Test 1: PHP Version & Extensions
        $tests['php'] = [
            'version' => PHP_VERSION,
            'extensions' => [
                'mysqli' => extension_loaded('mysqli'),
                'pdo' => extension_loaded('pdo'),
                'pdo_mysql' => extension_loaded('pdo_mysql'),
                'mbstring' => extension_loaded('mbstring'),
                'json' => extension_loaded('json'),
                'openssl' => extension_loaded('openssl'),
                'curl' => extension_loaded('curl'),
            ],
            'status' => 'success'
        ];

        // Test 2: Database Connection
        try {
            $pdo = DB::connection()->getPdo();
            $tests['database'] = [
                'status' => 'success',
                'driver' => Config::get('database.default'),
                'host' => Config::get('database.connections.mysql.host'),
                'database' => Config::get('database.connections.mysql.database'),
                'message' => 'Database connection successful',
                'server_info' => $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION)
            ];
        } catch (\Exception $e) {
            $tests['database'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

        // Test 3: Test Tables
        $tables = [
            'getdata_logs',
            'sensor_node_data',
            'sensor_weather_data',
            'node_logs',
            'irrigate_logs',
            'valve_logs'
        ];

        $tests['tables'] = [];
        foreach ($tables as $table) {
            try {
                $count = DB::table($table)->count();
                $tests['tables'][$table] = [
                    'status' => 'success',
                    'exists' => true,
                    'records' => $count
                ];
            } catch (\Exception $e) {
                $tests['tables'][$table] = [
                    'status' => 'error',
                    'exists' => false,
                    'message' => $e->getMessage()
                ];
            }
        }

        // Test 4: Storage Permissions
        $paths = [
            'storage/app' => storage_path('app'),
            'storage/logs' => storage_path('logs'),
            'storage/framework' => storage_path('framework'),
            'public/exports' => public_path('exports'),
        ];

        $tests['storage'] = [];
        foreach ($paths as $name => $path) {
            $tests['storage'][$name] = [
                'path' => $path,
                'exists' => file_exists($path),
                'writable' => is_writable($path),
                'status' => (file_exists($path) && is_writable($path)) ? 'success' : 'warning'
            ];
        }

        // Test 5: Laravel Configuration
        $tests['config'] = [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'app_url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'status' => 'success'
        ];

        return view('test-connection', compact('tests'));
    }
}
