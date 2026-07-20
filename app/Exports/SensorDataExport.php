<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SensorDataExport implements FromArray, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
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
                $row['device'] ?? '-',
                $row['location'] ?? '-',
                $row['temperature_c'] ?? '-',
                $row['humidity_pct'] ?? '-',
                $row['soil_moisture_pct'] ?? '-',
                $row['light_lux'] ?? '-',
                $row['water_height_cm'] ?? '-',
                $row['battery_voltage_v'] ?? '-',
            ];
        }, $this->data);
    }

    public function headings(): array
    {
        return [
            'Timestamp',
            'Device',
            'Lokasi',
            'Suhu (°C)',
            'Kelembapan Udara (%)',
            'Kelembapan Tanah (%)',
            'Intensitas Cahaya (lux)',
            'Tinggi Air (cm)',
            'Voltase Baterai (V)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10b981'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Data Sensor';
    }
}
