@extends('layouts.admin')

@section('title', 'Node ' . $node->id)

@section('content')
{{-- Page Breadcrumb --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('nodes.index') }}"
            class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-white/[0.05] dark:hover:text-gray-200 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Node {{ $node->id }}</h2>
                @php
                    $isOnline = $latestSensorData && 
                                $latestSensorData->received_at && 
                                $latestSensorData->received_at->diffInHours(now()) < 1;
                @endphp
                @if($isOnline)
                    <span class="flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-theme-xs font-medium bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">
                        <span class="w-1.5 h-1.5 rounded-full bg-success-500 animate-pulse"></span>
                        Online
                    </span>
                @else
                    <span class="flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-theme-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                        Offline
                    </span>
                @endif
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Group: <span class="font-medium text-gray-800 dark:text-white/90">{{ $node->group ?? 'N/A' }}</span> | 
                Treatment: <span class="font-medium text-gray-800 dark:text-white/90">{{ $node->kode_perlakuan ?? 'N/A' }}</span> | 
                Location: <span class="font-medium text-gray-800 dark:text-white/90">{{ $node->lokasi ?? 'N/A' }}</span>
            </p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        @can('role', ['admin', 'operator'])
            <a href="{{ route('nodes.edit', $node->id) }}"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit Node
            </a>
        @endcan
    </div>
</div>

@if ($latestSensorData)
    {{-- Metric Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6 mb-6">
        {{-- Temperature --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Temperature</span>
                <div class="flex items-center justify-center w-10 h-10 bg-error-50 rounded-xl dark:bg-error-500/[0.15]">
                    <svg class="text-error-500" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <h4 class="mb-1 font-bold text-gray-800 text-2xl dark:text-white/90">
                {{ number_format($latestSensorData->temp_ds18 ?? 0, 1) }}°C
            </h4>
            <span class="text-xs text-gray-400 dark:text-gray-500">Update: {{ $latestSensorData->received_at ? $latestSensorData->received_at->diffForHumans() : 'N/A' }}</span>
        </div>

        {{-- Moisture --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Soil Moisture</span>
                <div class="flex items-center justify-center w-10 h-10 bg-brand-50 rounded-xl dark:bg-brand-500/[0.15]">
                    <svg class="text-brand-500" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C12 2 6 9 6 13a6 6 0 0 0 12 0c0-4-6-11-6-11z" />
                    </svg>
                </div>
            </div>
            <h4 class="mb-1 font-bold text-2xl {{ ($latestSensorData->moist ?? 0) < 30 ? 'text-error-500' : 'text-brand-500' }}">
                {{ number_format($latestSensorData->moist ?? 0, 1) }}%
            </h4>
            <span class="text-xs text-gray-400 dark:text-gray-500">Update: {{ $latestSensorData->received_at ? $latestSensorData->received_at->diffForHumans() : 'N/A' }}</span>
        </div>

        {{-- Voltage --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">System Voltage</span>
                <div class="flex items-center justify-center w-10 h-10 bg-warning-50 rounded-xl dark:bg-warning-500/[0.15]">
                    <svg class="text-warning-500" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            <h4 class="mb-1 font-bold text-2xl {{ ($latestSensorData->volt ?? 0) < 3.0 ? 'text-warning-500' : 'text-gray-800 dark:text-white/90' }}">
                {{ number_format($latestSensorData->volt ?? 0, 2) }}V
            </h4>
            <span class="text-xs text-gray-400 dark:text-gray-500">Update: {{ $latestSensorData->received_at ? $latestSensorData->received_at->diffForHumans() : 'N/A' }}</span>
        </div>

        {{-- Current & Power --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Current & Power</span>
                <div class="flex items-center justify-center w-10 h-10 bg-success-50 rounded-xl dark:bg-success-500/[0.15]">
                    <svg class="text-success-500" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <h4 class="mb-1 font-bold text-gray-800 text-xl dark:text-white/90">
                {{ number_format($latestSensorData->current ?? 0, 0) }} mA <span class="text-sm font-normal text-gray-400">/</span> {{ number_format($latestSensorData->power_mw ?? 0, 0) }} mW
            </h4>
            <span class="text-xs text-gray-400 dark:text-gray-500">Update: {{ $latestSensorData->received_at ? $latestSensorData->received_at->diffForHumans() : 'N/A' }}</span>
        </div>
    </div>
@else
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] mb-6 flex items-center gap-3">
        <svg class="w-6 h-6 text-warning-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Node belum mengirimkan telemetry data sensor.</div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Chart section --}}
    <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Visualisasi Grafik (24 Jam)</h3>
            <div class="flex gap-4 text-theme-xs font-medium">
                <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400"><span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>Kelembaban (%)</span>
                <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400"><span class="w-2.5 h-2.5 rounded-full bg-error-500"></span>Suhu (°C)</span>
            </div>
        </div>
        <div class="p-6">
            <div class="relative w-full h-[320px]">
                @if ($sensorData->isNotEmpty())
                    <div id="nodeChart" class="w-full h-full"></div>
                @else
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                        <svg class="w-12 h-12 mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        <p class="text-sm font-medium">Tidak ada data visualisasi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Ringkasan Statistik</h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-800">
                <span class="text-theme-sm text-gray-500 dark:text-gray-400">Rata-rata Kelembaban</span>
                <span class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">{{ $stats['avg_moisture'] }}%</span>
            </div>
            <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-800">
                <span class="text-theme-sm text-gray-500 dark:text-gray-400">Kelembaban Min / Max</span>
                <span class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">{{ $stats['min_moisture'] }}% / {{ $stats['max_moisture'] }}%</span>
            </div>
            <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-800">
                <span class="text-theme-sm text-gray-500 dark:text-gray-400">Rata-rata Suhu</span>
                <span class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">{{ $stats['avg_temperature'] }}°C</span>
            </div>
            <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-800">
                <span class="text-theme-sm text-gray-500 dark:text-gray-400">Suhu Min / Max</span>
                <span class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">{{ $stats['min_temperature'] }}°C / {{ $stats['max_temperature'] }}°C</span>
            </div>
            <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-800">
                <span class="text-theme-sm text-gray-500 dark:text-gray-400">Rata-rata RSSI Sinyal</span>
                <span class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">{{ $stats['avg_rssi'] }} dBm</span>
            </div>
            
            <div class="pt-2 text-center">
                <span class="text-theme-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider font-medium">Total Pembacaan: {{ $stats['total_readings'] }} kali</span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Sensor Data History Table --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Riwayat Sensor Node (Terbaru)</h3>
        </div>
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Tanggal / Waktu</th>
                        <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Kelembaban</th>
                        <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Suhu</th>
                        <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Tegangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($sensorData->take(15) as $data)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">
                                {{ $data->received_at ? $data->received_at->format('d/m/Y H:i:s') : 'N/A' }}
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap text-right">
                                <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium {{ ($data->moist ?? 0) < 30 ? 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400' : 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400' }}">
                                    {{ number_format($data->moist ?? 0, 1) }}%
                                </span>
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300">
                                {{ number_format($data->temp_ds18 ?? 0, 1) }}°C
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300">
                                {{ number_format($data->volt ?? 0, 2) }}V
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-theme-sm text-gray-500 dark:text-gray-400">Belum ada pembacaan sensor node</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Transmission Logs Table --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Log Transmisi Gateway LoRa</h3>
        </div>
        <div class="max-w-full overflow-x-auto custom-scrollbar max-h-[500px] overflow-y-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 sticky top-0 z-10 backdrop-blur-md">
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                        <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">RSSI</th>
                        <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">SNR</th>
                        <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Sinyal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($logs->take(50) as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="py-3 px-6 text-theme-sm text-gray-700 dark:text-gray-300">
                                {{ $log->waktu ? $log->waktu->format('H:i:s') : 'N/A' }}
                                <div class="text-theme-xs text-gray-400 dark:text-gray-500 mt-0.5 line-clamp-1" title="{{ $log->keterangan }}">
                                    {{ $log->keterangan }}
                                </div>
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $log->rssi ?? 'N/A' }} dBm
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-500 dark:text-gray-400">
                                {{ $log->snr ?? 'N/A' }} dB
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap text-center">
                                @php
                                    $rssi = $log->rssi ?? -999;
                                    if ($rssi > -70) {
                                        $quality = 'EXCELLENT';
                                        $badgeClass = 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400';
                                    } elseif ($rssi > -85) {
                                        $quality = 'GOOD';
                                        $badgeClass = 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400';
                                    } elseif ($rssi > -100) {
                                        $quality = 'FAIR';
                                        $badgeClass = 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400';
                                    } else {
                                        $quality = 'POOR';
                                        $badgeClass = 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400';
                                    }
                                @endphp
                                <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium {{ $badgeClass }}">
                                    {{ $quality }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-theme-sm text-gray-500 dark:text-gray-400">Tidak ada log komunikasi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if ($sensorData->isNotEmpty())
    <script>
        document.addEventListener('alpine:init', () => {
            const rawSensorData = @json($sensorData);
            
            // Need to reverse to show chronologically from left to right usually, but depends on array sorting
            // Assuming array is newest first (take 20), we reverse it for chart
            const chartData = rawSensorData.slice().reverse();
            
            const labels = chartData.map(d => {
                const date = new Date(d.received_at);
                return date.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            });

            const soilMoistureData = chartData.map(d => d.soil_pct ?? d.moist ?? 0);
            const temperatureData = chartData.map(d => d.temp_c ?? d.temp_ds18 ?? 0);

            const options = {
                series: [
                    {
                        name: 'Kelembaban Tanah (%)',
                        data: soilMoistureData
                    },
                    {
                        name: 'Suhu (°C)',
                        data: temperatureData
                    }
                ],
                chart: {
                    type: 'area',
                    height: 320,
                    fontFamily: 'Outfit, sans-serif',
                    toolbar: { show: false },
                    background: 'transparent',
                },
                colors: ['#10b981', '#ff5724'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 2,
                },
                xaxis: {
                    categories: labels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            colors: '#9ca3af',
                            fontSize: '11px',
                        }
                    },
                    tickAmount: 6,
                },
                yaxis: [
                    {
                        title: { text: undefined },
                        min: 0,
                        max: 100,
                        labels: {
                            style: { colors: '#9ca3af' },
                            formatter: (value) => value.toFixed(0) + '%'
                        },
                    },
                    {
                        opposite: true,
                        title: { text: undefined },
                        min: 0,
                        max: 50,
                        labels: {
                            style: { colors: '#9ca3af' },
                            formatter: (value) => value.toFixed(0) + '°C'
                        },
                    }
                ],
                grid: {
                    borderColor: '#f3f4f6',
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                theme: {
                    mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                },
                legend: { show: false },
                tooltip: {
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                }
            };

            const chart = new ApexCharts(document.querySelector("#nodeChart"), options);
            chart.render();
            
            // Watch for theme changes
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class') {
                        const isDark = document.documentElement.classList.contains('dark');
                        chart.updateOptions({
                            theme: { mode: isDark ? 'dark' : 'light' },
                            grid: { borderColor: isDark ? '#1f2937' : '#f3f4f6' }
                        });
                    }
                });
            });
            observer.observe(document.documentElement, { attributes: true });
        });
    </script>
@endif
@endsection
