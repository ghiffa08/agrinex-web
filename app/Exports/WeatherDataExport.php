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

class WeatherDataExport implements FromArray, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
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
                $row['timestamp'] ?? '-',
                $row['location'] ?? '-',
                $row['temperature_c'] ?? '-',
                $row['humidity_pct'] ?? '-',
                $row['rainfall_mm'] ?? '-',
                $row['wind_speed_ms'] ?? '-',
                $row['wind_direction'] ?? '-',
                $row['light_intensity_pct'] ?? '-',
                $row['water_level_cm'] ?? '-',
            ];
        }, $this->data);
    }

    public function headings(): array
    {
        return [
            'Timestamp',
            'Lokasi',
            'Suhu (°C)',
            'Kelembapan (%)',
            'Curah Hujan (mm)',
            'Kecepatan Angin (m/s)',
            'Arah Angin',
            'Intensitas Cahaya (%)',
            'Ketinggian Air (cm)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3b82f6'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Data Cuaca';
    }
}
