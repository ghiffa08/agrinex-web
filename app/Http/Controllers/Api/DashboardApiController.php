<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChartDataService;
use App\Http\Resources\ChartDataResource;
use App\Repositories\Contracts\DashboardRepositoryInterface;
use App\Repositories\Contracts\JsonBackupRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    public function __construct(
        protected DashboardRepositoryInterface  $dashboardRepo,
        protected JsonBackupRepositoryInterface $backupRepo,
    ) {}

    /**
     * GET /api/v1/dashboard/devices
     */
    public function getDevices(): JsonResponse
    {
        try {
            $devices = $this->dashboardRepo->getDevices();

            return response()->json([
                'success'      => true,
                'data'         => $devices,
                'session_info' => ['total_nodes' => count($devices)],
            ]);
        } catch (\Exception $e) {
            return $this->serverError('Error fetching devices', $e);
        }
    }

    /**
     * GET /api/v1/dashboard/tank
     */
    public function getTank(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data'    => $this->dashboardRepo->getTank(),
            ]);
        } catch (\Exception $e) {
            // Return a safe default instead of 500
            return response()->json([
                'success' => true,
                'data'    => $this->emptyTank(),
                'note'    => 'No data available',
            ]);
        }
    }

    /**
     * GET /api/v1/dashboard/schedule
     */
    public function getSchedule(): JsonResponse
    {
        try {
            $schedule = $this->dashboardRepo->getSchedule();

            return response()->json([
                'success' => true,
                'data'    => [
                    'date'           => today()->format('Y-m-d'),
                    'total_sessions' => count($schedule),
                    'sessions'       => $schedule,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'data'    => ['date' => today()->format('Y-m-d'), 'total_sessions' => 0, 'sessions' => []],
                'note'    => 'No data available',
            ]);
        }
    }

    /**
     * GET /api/v1/dashboard/usage
     */
    public function getUsage(): JsonResponse
    {
        try {
            $usage = $this->dashboardRepo->getUsage();

            return response()->json([
                'success' => true,
                'data'    => $usage,
                'summary' => $this->usageSummary($usage, 'total_days', '30 days'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'data'    => [],
                'summary' => $this->usageSummary([], 'total_days', '30 days'),
            ]);
        }
    }

    /**
     * GET /api/v1/dashboard/usage/daily
     */
    public function getUsageDaily(): JsonResponse
    {
        try {
            $usage = $this->dashboardRepo->getUsageDaily();

            return response()->json([
                'success' => true,
                'data'    => $usage,
                'summary' => $this->usageSummary($usage, 'total_hours', '24 hours'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'data'    => [],
                'summary' => $this->usageSummary([], 'total_hours', '24 hours'),
            ]);
        }
    }

    /**
     * GET /api/v1/dashboard/charts
     */
    public function getChartData(Request $request, ChartDataService $chartService): JsonResponse
    {
        try {
            $type   = $request->input('type', 'all');
            $days   = $request->input('days', 7);
            $limit  = $request->input('limit');

            $result = $chartService->getSessions($days, $limit);

            $formatted = (new ChartDataResource($result['sessions']))
                ->setType($type)
                ->resolve();

            return response()->json([
                'success' => true,
                'data'    => $formatted,
                'meta'    => [
                    'total_points'    => $result['sessions']->count(),
                    'time_range_days' => $days ?? ($limit ? 'limited' : 7),
                    'start_time'      => $result['start_time']->format('Y-m-d H:i:s'),
                    'end_time'        => now()->format('Y-m-d H:i:s'),
                ],
            ]);
        } catch (\Exception $e) {
            return $this->serverError('Error fetching chart data', $e);
        }
    }

    /**
     * GET /api/v1/dashboard/weather
     */
    public function getWeather(): JsonResponse
    {
        try {
            $weather = $this->dashboardRepo->getWeather();

            if (!$weather) {
                return response()->json(['success' => false, 'message' => 'No weather data available']);
            }

            return response()->json(['success' => true, 'data' => $weather]);
        } catch (\Exception $e) {
            return $this->serverError('Error fetching weather', $e);
        }
    }

    /**
     * GET /api/v1/dashboard/json-backup
     */
    public function getJsonBackup(Request $request): JsonResponse
    {
        try {
            $limit   = min((int) $request->get('limit', 50), 200);
            $filters = $request->only(['sesi_id_getdata', 'date_from', 'date_to']);

            $backups = $this->backupRepo->getBackups($filters, $limit);

            return response()->json([
                'success'  => true,
                'data'     => $backups,
                'metadata' => [
                    'total_records'   => $backups->count(),
                    'limit'           => $limit,
                    'filters_applied' => $filters,
                ],
            ]);
        } catch (\Exception $e) {
            return $this->serverError('Error fetching JSON backup', $e);
        }
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function usageSummary(array $usage, string $countKey, string $period): array
    {
        $total = array_sum(array_column($usage, 'total_l'));
        $count = count($usage);

        return [
            $countKey    => $count,
            'total_usage_l'   => $total,
            'average_usage_l' => $count > 0 ? round($total / $count, 2) : 0,
            'period'          => $period,
        ];
    }

    private function emptyTank(): array
    {
        return [
            'id'                    => null,
            'tank_name'             => 'Tangki Air Utama',
            'name'                  => 'Tangki Air Utama',
            'capacity'              => 0,
            'capacity_liters'       => 0,
            'current_volume_liters' => 0,
            'water_level_cm'        => 0,
            'percentage'            => 0,
            'status'                => 'no_data',
            'updated_at'            => null,
        ];
    }

    private function serverError(string $message, \Exception $e): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message . ': ' . $e->getMessage(),
        ], 500);
    }
}
