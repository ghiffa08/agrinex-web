<?php

namespace App\Repositories\Contracts;

interface ReportRepositoryInterface
{
    /**
     * Get sensor data for reporting with date range and device filter
     */
    public function getSensorDataReport(array $filters): array;

    /**
     * Get weather data for reporting with date range
     */
    public function getWeatherDataReport(array $filters): array;

    /**
     * Get irrigation logs for reporting with date range and device filter
     */
    public function getIrrigationReport(array $filters): array;

    /**
     * Get device activity summary for reporting
     */
    public function getDeviceActivityReport(array $filters): array;

    /**
     * Get water usage summary by device and date range
     */
    public function getWaterUsageSummary(array $filters): array;

    /**
     * Get comprehensive dashboard summary for PDF report
     */
    public function getDashboardSummary(array $filters): array;
}
