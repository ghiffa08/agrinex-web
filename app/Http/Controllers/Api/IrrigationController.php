<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\IrrigationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IrrigationController extends Controller
{
    protected $irrigationService;

    public function __construct(IrrigationService $irrigationService)
    {
        $this->irrigationService = $irrigationService;
    }

    /**
     * Receive irrigation data from Raspberry Pi
     * POST /api/v1/irrigation
     */
    public function store(Request $request)
    {
        try {
            // Log incoming request
            Log::info('Received irrigation data', [
                'size' => strlen($request->getContent()),
                'ip' => $request->ip()
            ]);

            // Validate request
            $validator = Validator::make($request->all(), [
                'metadata' => 'required|array',
                'metadata.sesi_id_irrigate' => 'required|integer|min:1',
                'metadata.timestamp' => 'required',
                'data' => 'required|array',
                // 'data.irrigate_logs' => 'nullable|array',
                // 'data.valve_logs' => 'nullable|array',
                'data.node_logs' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'timestamp' => now()->toDateTimeString(),
                    'server' => 'Laravel AgriNex API'
                ], 422);
            }

            // Process irrigation data
            $result = $this->irrigationService->processIrrigationData(
                $request->all()
            );

            return response()->json([
                'success' => true,
                'message' => 'Irrigation data saved successfully',
                'data' => $result,
                'timestamp' => now()->toDateTimeString(),
                'server' => 'Laravel AgriNex API'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Irrigation data processing failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Processing error: ' . $e->getMessage(),
                'timestamp' => now()->toDateTimeString(),
                'server' => 'Laravel AgriNex API'
            ], 500);
        }
    }

    /**
     * Get irrigation logs
     * GET /api/v1/irrigation
     */
    public function index(Request $request)
    {
        $sesiId = $request->query('sesi_id');
        $limit = $request->query('limit', 100);
        try {

            $data = $this->irrigationService->getIrrigationData($sesiId, $limit);

            return response()->json([
                'success' => true,
                'data' => $data,
                'count' => count($data),
                'timestamp' => now()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data: ' . $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * Get irrigation statistics
     * GET /api/v1/irrigation/statistics
     */
    public function statistics(Request $request)
    {
        try {
            $sesiId = $request->query('sesi_id');
            $stats = $this->irrigationService->getStatistics($sesiId);

            return response()->json([
                'success' => true,
                'statistics' => $stats,
                'timestamp' => now()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics: ' . $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }
}
