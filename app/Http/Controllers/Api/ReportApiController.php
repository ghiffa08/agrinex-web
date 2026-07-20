<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportApiController extends Controller
{
    protected ReportRepositoryInterface $reportRepository;
    protected ReportService $reportService;

    public function __construct(ReportRepositoryInterface $reportRepository, ReportService $reportService)
    {
        $this->reportRepository = $reportRepository;
        $this->reportService = $reportService;
    }

    /**
     * Get preview summary for report filters
     */
    public function preview(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'device_id' => 'nullable|exists:devices,id',
        ]);

        $filters = $this->reportService->normalizeFilters($validated);

        try {
            $summary = $this->reportRepository->getDashboardSummary($filters);
            
            return response()->json([
                'success' => true,
                'summary' => $summary
            ]);
        } catch (\Exception $e) {
            \Log::error('Report preview failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat preview: ' . $e->getMessage()
            ], 500);
        }
    }
}
