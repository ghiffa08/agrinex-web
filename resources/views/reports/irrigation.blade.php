<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $report_title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #333; }
        .header { text-align: center; padding: 15px 0; border-bottom: 2px solid #3b82f6; margin-bottom: 15px; }
        .header h1 { font-size: 16px; color: #3b82f6; margin-bottom: 5px; }
        .header p { font-size: 8px; color: #666; }
        .section { margin-bottom: 15px; }
        .section-title { background: #3b82f6; color: white; padding: 6px 8px; font-size: 11px; font-weight: bold; margin-bottom: 10px; }
        .summary-box { background: #eff6ff; border: 1px solid #3b82f6; padding: 10px; margin-bottom: 15px; }
        .summary-item { margin-bottom: 5px; font-size: 9px; }
        .summary-label { font-weight: bold; color: #1e40af; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th { background: #3b82f6; color: white; padding: 5px; text-align: left; font-size: 8px; }
        td { padding: 4px; border: 1px solid #e5e7eb; font-size: 8px; }
        tr:nth-child(even) { background: #f9fafb; }
        .footer { text-align: center; font-size: 7px; color: #999; margin-top: 20px; padding-top: 10px; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $report_title }}</h1>
        <p>Periode: {{ $filters['start_date'] ?? '-' }} s/d {{ $filters['end_date'] ?? '-' }}</p>
        <p>Generated: {{ $generated_at }}</p>
    </div>

    <!-- Water Usage Summary -->
    <div class="section">
        <div class="section-title">Ringkasan Penggunaan Air</div>
        <div class="summary-box">
            @php
                $totalWater = collect($water_usage_summary)->sum('total_water_liters');
                $totalSessions = collect($water_usage_summary)->sum('total_sessions');
            @endphp
            <div class="summary-item">
                <span class="summary-label">Total Penggunaan Air:</span> {{ number_format($totalWater, 2) }} Liter
            </div>
            <div class="summary-item">
                <span class="summary-label">Total Sesi Irigasi:</span> {{ number_format($totalSessions) }} sesi
            </div>
            <div class="summary-item">
                <span class="summary-label">Rata-rata Air per Sesi:</span> {{ $totalSessions > 0 ? number_format($totalWater / $totalSessions, 2) : '0' }} Liter
            </div>
        </div>
    </div>

    <!-- Water Usage by Device -->
    <div class="section">
        <div class="section-title">Penggunaan Air per Device</div>
        <table>
            <thead>
                <tr>
                    <th>Device</th>
                    <th>Lokasi</th>
                    <th>Total Sesi</th>
                    <th>Total Air (L)</th>
                    <th>Rata-rata/Sesi (L)</th>
                    <th>Total Durasi (menit)</th>
                    <th>Rata-rata Durasi (menit)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($water_usage_summary as $usage)
                <tr>
                    <td>{{ $usage['device'] }}</td>
                    <td>{{ $usage['location'] }}</td>
                    <td>{{ number_format($usage['total_sessions']) }}</td>
                    <td><strong>{{ number_format($usage['total_water_liters'], 2) }}</strong></td>
                    <td>{{ number_format($usage['avg_water_per_session'], 2) }}</td>
                    <td>{{ number_format($usage['total_duration_minutes']) }}</td>
                    <td>{{ number_format($usage['avg_duration_minutes']) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #999;">Belum ada data irigasi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Irrigation Logs -->
    <div class="section">
        <div class="section-title">Log Irigasi Detail</div>
        <table>
            <thead>
                <tr>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Device</th>
                    <th>Lokasi</th>
                    <th>Air (L)</th>
                    <th>Durasi (min)</th>
                    <th>Mode</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($irrigation_logs as $log)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($log['start_time'])->format('d/m H:i') }}</td>
                    <td>{{ $log['end_time'] ? \Carbon\Carbon::parse($log['end_time'])->format('d/m H:i') : '-' }}</td>
                    <td>{{ $log['device'] }}</td>
                    <td>{{ $log['location'] }}</td>
                    <td>{{ number_format($log['water_used_liters'], 2) }}</td>
                    <td>{{ number_format($log['duration_minutes']) }}</td>
                    <td>{{ ucfirst($log['mode']) }}</td>
                    <td>{{ ucfirst($log['status']) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #999;">Belum ada log irigasi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>AgriNex SmartDrip - Laporan Irigasi | Generated by Report Module</p>
        <p>© {{ date('Y') }} AgriNex</p>
    </div>
</body>
</html>
