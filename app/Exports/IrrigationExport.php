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

class IrrigationExport implements FromArray, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
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
                $row['start_time'] ?? '-',
                $row['end_time'] ?? '-',
                $row['device'] ?? '-',
                $row['location'] ?? '-',
                $row['water_used_liters'] ?? 0,
                $row['duration_minutes'] ?? 0,
                $row['mode'] ?? '-',
                $row['status'] ?? '-',
            ];
        }, $this->data);
    }

    public function headings(): array
    {
        return [
            'Waktu Mulai',
            'Waktu Selesai',
            'Device',
            'Lokasi',
            'Air Terpakai (L)',
            'Durasi (menit)',
            'Mode',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0ea5e9'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Log Irigasi';
    }
}
