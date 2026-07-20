<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class WaterUsageSummaryExport implements FromArray, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return array_map(function ($row) {
            return [
                $row['device'] ?? '-',
                $row['location'] ?? '-',
                $row['total_sessions'] ?? 0,
                $row['total_water_liters'] ?? 0,
                $row['avg_water_per_session'] ?? 0,
                $row['total_duration_minutes'] ?? 0,
                $row['avg_duration_minutes'] ?? 0,
            ];
        }, $this->data);
    }

    public function headings(): array
    {
        return [
            'Device',
            'Lokasi',
            'Total Sesi',
            'Total Air (L)',
            'Rata-rata Air per Sesi (L)',
            'Total Durasi (menit)',
            'Rata-rata Durasi (menit)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '06b6d4'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Ringkasan Penggunaan Air';
    }
}
