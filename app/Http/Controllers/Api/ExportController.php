<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExportController extends Controller
{
    protected $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * Export data to various formats
     * GET /api/v1/export
     * 
     * Query parameters:
     * - format: csv, json, sql (default: json)
     * - table: all, sensor_node_data, sensor_weather_data, node_logs, getdata_logs, irrigate_logs, valve_logs
     * - sesi: session ID
     * - start: start date (Y-m-d)
     * - end: end date (Y-m-d)
     * - limit: max records (default: 1000)
     */
    public function export(Request $request)
    {
        try {
            $format = strtolower($request->query('format', 'json'));
            $table = $request->query('table', 'all');
            $sesiId = $request->query('sesi');
            $startDate = $request->query('start');
            $endDate = $request->query('end');
            $limit = $request->query('limit', 1000);

            // Validate format
            if (!in_array($format, ['csv', 'json', 'sql'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid format. Allowed: csv, json, sql',
                    'timestamp' => now()->toDateTimeString()
                ], 400);
            }

            // Validate table
            $allowedTables = [
                'all',
                'sensor_node_data',
                'sensor_weather_data',
                'node_logs',
                'getdata_logs',
                // 'irrigate_logs',
                // 'valve_logs'
            ];

            if (!in_array($table, $allowedTables)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid table name',
                    'allowed_tables' => $allowedTables,
                    'timestamp' => now()->toDateTimeString()
                ], 400);
            }

            // Log export request
            Log::info('Export request', [
                'format' => $format,
                'table' => $table,
                'sesi_id' => $sesiId,
                'ip' => $request->ip()
            ]);

            // Export data based on format
            switch ($format) {
                case 'csv':
                    return $this->exportService->exportCsv($table, [
                        'sesi_id' => $sesiId,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'limit' => $limit
                    ]);

                case 'json':
                    $data = $this->exportService->exportJson($table, [
                        'sesi_id' => $sesiId,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'limit' => $limit
                    ]);

                    return response()->json([
                        'success' => true,
                        'format' => 'json',
                        'table' => $table,
                        'count' => count($data),
                        'data' => $data,
                        'timestamp' => now()->toDateTimeString()
                    ]);

                case 'sql':
                    return $this->exportService->exportSql($table, [
                        'sesi_id' => $sesiId,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'limit' => $limit
                    ]);
            }

        } catch (\Exception $e) {
            Log::error('Export failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * Download exported file
     * GET /api/v1/export/download/{filename}
     */
    public function download($filename)
    {
        try {
            $filePath = storage_path('app/exports/' . $filename);

            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found',
                    'timestamp' => now()->toDateTimeString()
                ], 404);
            }

            return response()->download($filePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Download failed: ' . $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * List available export files
     * GET /api/v1/export/list
     */
    public function list()
    {
        try {
            $exportPath = storage_path('app/exports');
            
            if (!is_dir($exportPath)) {
                mkdir($exportPath, 0755, true);
            }

            $files = array_diff(scandir($exportPath), ['.', '..']);
            $fileList = [];

            foreach ($files as $file) {
                $filePath = $exportPath . '/' . $file;
                $fileList[] = [
                    'filename' => $file,
                    'size' => filesize($filePath),
                    'size_human' => $this->formatBytes(filesize($filePath)),
                    'modified' => date('Y-m-d H:i:s', filemtime($filePath)),
                    'download_url' => url('/api/v1/export/download/' . $file)
                ];
            }

            return response()->json([
                'success' => true,
                'files' => $fileList,
                'count' => count($fileList),
                'timestamp' => now()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to list files: ' . $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
