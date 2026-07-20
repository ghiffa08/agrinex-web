<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportService $reportService;
    protected ReportRepositoryInterface $reportRepository;

    public function __construct(ReportService $reportService, ReportRepositoryInterface $reportRepository)
    {
        $this->middleware('auth');
        $this->reportService = $reportService;
        $this->reportRepository = $reportRepository;
    }

    /**
     * Show report generation page
     */
    public function index()
    {
        $reports = $this->reportService->getAvailableReports();
        $devices = \App\Models\Device::select('id', 'name', 'lokasi')->get();
        
        return view('reports.index', compact('reports', 'devices'));
    }

    /**
     * Generate report based on type
     */
    public function generate(Request $request, $reportType)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'device_id' => 'nullable|exists:devices,id',
        ]);

        $filters = $this->reportService->normalizeFilters($validated);

        try {
            return match ($reportType) {
                'sensor_data_excel' => $this->reportService->generateSensorDataExcel($filters),
                'weather_data_excel' => $this->reportService->generateWeatherDataExcel($filters),
                'irrigation_excel' => $this->reportService->generateIrrigationExcel($filters),
                'water_usage_excel' => $this->reportService->generateWaterUsageExcel($filters),
                'comprehensive_pdf' => $this->reportService->generateComprehensivePdf($filters),
                'irrigation_pdf' => $this->reportService->generateIrrigationPdf($filters),
                default => abort(404, 'Tipe laporan tidak valid'),
            };
        } catch (\Exception $e) {
            \Log::error('Report generation failed: ' . $e->getMessage(), [
                'report_type' => $reportType,
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Gagal generate laporan: ' . $e->getMessage());
        }
    }
}
