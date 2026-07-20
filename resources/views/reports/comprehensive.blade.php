<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $report_title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; padding: 20px 0; border-bottom: 2px solid #10b981; margin-bottom: 20px; }
        .header h1 { font-size: 18px; color: #10b981; margin-bottom: 5px; }
        .header p { font-size: 9px; color: #666; }
        .section { margin-bottom: 20px; }
        .section-title { background: #10b981; color: white; padding: 8px 10px; font-size: 12px; font-weight: bold; margin-bottom: 10px; }
        .info-grid { display: table; width: 100%; margin-bottom: 15px; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; width: 30%; padding: 5px; background: #f3f4f6; font-weight: bold; border: 1px solid #e5e7eb; }
        .info-value { display: table-cell; width: 70%; padding: 5px; border: 1px solid #e5e7eb; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background: #10b981; color: white; padding: 6px; text-align: left; font-size: 9px; }
        td { padding: 5px; border: 1px solid #e5e7eb; font-size: 9px; }
        tr:nth-child(even) { background: #f9fafb; }
        .summary-box { background: #ecfdf5; border: 1px solid #10b981; padding: 10px; margin-bottom: 15px; }
        .summary-item { margin-bottom: 5px; }
        .summary-label { font-weight: bold; color: #047857; }
        .footer { text-align: center; font-size: 8px; color: #999; margin-top: 30px; padding-top: 10px; border-top: 1px solid #e5e7eb; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $report_title }}</h1>
        <p>Periode: {{ $summary['report_period']['start_date'] }} s/d {{ $summary['report_period']['end_date'] }}</p>
        <p>Generated: {{ $generated_at }}</p>
    </div>

    <!-- Summary Section -->
    <div class="section">
        <div class="section-title">Ringkasan Sistem</div>
        <div class="summary-box">
            <div class="summary-item">
                <span class="summary-label">Total Device:</span> {{ $summary['devices']['total'] }} ({{ $summary['devices']['active'] }} aktif)
            </div>
            <div class="summary-item">
                <span class="summary-label">Total Pembacaan Sensor:</span> {{ number_format($summary['readings']['total']) }} data
            </div>
            <div class="summary-item">
                <span class="summary-label">Total Sesi Irigasi:</span> {{ number_format($summary['irrigation']['total_sessions']) }} sesi
            </div>
            <div class="summary-item">
                <span class="summary-label">Total Penggunaan Air:</span> {{ number_format($summary['irrigation']['total_water_liters'], 2) }} Liter
            </div>
        </div>
    </div>

    <!-- Environmental Conditions -->
    <div class="section">
        <div class="section-title">Kondisi Lingkungan Rata-rata</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Suhu</div>
                <div class="info-value">{{ $summary['environment']['avg_temperature_c'] }}°C</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kelembapan</div>
                <div class="info-value">{{ $summary['environment']['avg_humidity_pct'] }}%</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kelembapan Tanah</div>
                <div class="info-value">{{ $summary['environment']['avg_soil_moisture_pct'] }}%</div>
            </div>
            <div class="info-row">
                <div class="info-label">Cahaya</div>
                <div class="info-value">{{ number_format($summary['environment']['avg_light_lux']) }} lux</div>
            </div>
        </div>
    </div>

    <!-- Device Activity -->
    <div class="section">
        <div class="section-title">Aktivitas Device</div>
        <table>
            <thead>
                <tr>
                    <th>Device</th>
                    <th>Lokasi</th>
                    <th>Total Data</th>
                    <th>Total Irigasi</th>
                    <th>Pembacaan Terakhir</th>
                    <th>Suhu</th>
                    <th>Kelembapan Tanah</th>
                    <th>Baterai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($device_activity as $device)
                <tr>
                    <td>{{ $device['device_name'] }}</td>
                    <td>{{ $device['location'] }}</td>
                    <td>{{ number_format($device['total_readings']) }}</td>
                    <td>{{ number_format($device['total_irrigations']) }}</td>
                    <td>{{ $device['last_reading'] ? \Carbon\Carbon::parse($device['last_reading'])->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $device['last_temperature'] ? number_format($device['last_temperature'], 1) . '°C' : '-' }}</td>
                    <td>{{ $device['last_soil_moisture'] ? number_format($device['last_soil_moisture'], 1) . '%' : '-' }}</td>
                    <td>{{ $device['last_battery'] ? number_format($device['last_battery'], 2) . 'V' : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- Water Usage Summary -->
    <div class="section">
        <div class="section-title">Ringkasan Penggunaan Air per Device</div>
        <table>
            <thead>
                <tr>
                    <th>Device</th>
                    <th>Lokasi</th>
                    <th>Total Sesi</th>
                    <th>Total Air (L)</th>
                    <th>Rata-rata per Sesi (L)</th>
                    <th>Total Durasi (menit)</th>
                    <th>Rata-rata Durasi (menit)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($water_usage as $usage)
                <tr>
                    <td>{{ $usage['device'] }}</td>
                    <td>{{ $usage['location'] }}</td>
                    <td>{{ number_format($usage['total_sessions']) }}</td>
                    <td>{{ number_format($usage['total_water_liters'], 2) }}</td>
                    <td>{{ number_format($usage['avg_water_per_session'], 2) }}</td>
                    <td>{{ number_format($usage['total_duration_minutes']) }}</td>
                    <td>{{ number_format($usage['avg_duration_minutes']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Recent Sensor Data -->
    <div class="section">
        <div class="section-title">Data Sensor Terbaru (50 Data Terakhir)</div>
        <table>
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Device</th>
                    <th>Suhu (°C)</th>
                    <th>Kelembapan (%)</th>
                    <th>Kelembapan Tanah (%)</th>
                    <th>Cahaya (lux)</th>
                    <th>Tinggi Air (cm)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent_sensors as $sensor)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($sensor['timestamp'])->format('d/m/Y H:i') }}</td>
                    <td>{{ $sensor['device'] }}</td>
                    <td>{{ $sensor['temperature_c'] ? number_format($sensor['temperature_c'], 1) : '-' }}</td>
                    <td>{{ $sensor['humidity_pct'] ? number_format($sensor['humidity_pct'], 1) : '-' }}</td>
                    <td>{{ $sensor['soil_moisture_pct'] ? number_format($sensor['soil_moisture_pct'], 1) : '-' }}</td>
                    <td>{{ $sensor['light_lux'] ? number_format($sensor['light_lux']) : '-' }}</td>
                    <td>{{ $sensor['water_height_cm'] ? number_format($sensor['water_height_cm'], 2) : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- Recent Irrigation -->
    <div class="section">
        <div class="section-title">Log Irigasi Terbaru (30 Sesi Terakhir)</div>
        <table>
            <thead>
                <tr>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Device</th>
                    <th>Air (L)</th>
                    <th>Durasi (menit)</th>
                    <th>Mode</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent_irrigation as $irrigation)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($irrigation['start_time'])->format('d/m/Y H:i') }}</td>
                    <td>{{ $irrigation['end_time'] ? \Carbon\Carbon::parse($irrigation['end_time'])->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $irrigation['device'] }}</td>
                    <td>{{ number_format($irrigation['water_used_liters'], 2) }}</td>
                    <td>{{ number_format($irrigation['duration_minutes']) }}</td>
                    <td>{{ ucfirst($irrigation['mode']) }}</td>
                    <td>{{ ucfirst($irrigation['status']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>AgriNex SmartDrip Irrigation System | Generated by Report Module</p>
        <p>© {{ date('Y') }} AgriNex - All Rights Reserved</p>
    </div>
</body>
</html>
