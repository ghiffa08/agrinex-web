@extends('layouts.admin')

@section('title', 'Weather Station')

@section('content')
{{-- Page Header --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90 font-bold">Weather Station</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Weather monitoring from Node 65</p>
    </div>
    <a href="{{ route('admin.weather-data.index') }}"
        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        View History
    </a>
</div>

{{-- Current Weather Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Temperature</span>
                <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">{{ number_format($stats['current_temp'], 1) }}°C</h4>
                <span class="text-xs text-gray-400 mt-1.5 block">24h avg: {{ number_format($stats['avg_temp_24h'], 1) }}°C</span>
            </div>
            <div class="w-12 h-12 rounded-full bg-error-50 dark:bg-error-500/15 flex items-center justify-center text-error-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Humidity</span>
                <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">{{ number_format($stats['current_humidity'], 1) }}%</h4>
                <span class="text-xs text-gray-400 mt-1.5 block">24h avg: {{ number_format($stats['avg_humidity_24h'], 1) }}%</span>
            </div>
            <div class="w-12 h-12 rounded-full bg-sky-50 dark:bg-sky-500/15 flex items-center justify-center text-sky-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Light Level</span>
                <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">{{ number_format($stats['max_light_24h'], 0) }}</h4>
                <span class="text-xs text-gray-400 mt-1.5 block">24h maximum</span>
            </div>
            <div class="w-12 h-12 rounded-full bg-warning-50 dark:bg-warning-500/15 flex items-center justify-center text-warning-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rain Status</span>
                <h4 class="mt-2 text-xl font-bold text-gray-800 dark:text-white/90">
                    @if($stats['rain_status'] === 'Raining')
                        <span class="text-brand-500">Raining</span>
                    @else
                        <span class="text-success-500">No Rain</span>
                    @endif
                </h4>
                <span class="text-xs text-gray-400 mt-1.5 block">{{ $stats['rain_status'] }}</span>
            </div>
            <div class="w-12 h-12 rounded-full bg-brand-50 dark:bg-brand-500/15 flex items-center justify-center text-brand-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- Latest Reading --}}
@if($latestWeather)
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-5">Latest Weather Reading</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-3.5">
            <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Last Updated</span>
                <span class="font-semibold text-gray-800 dark:text-white">{{ $latestWeather->waktu_weather->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Temperature</span>
                <span class="font-semibold text-gray-800 dark:text-white">{{ number_format($latestWeather->temp_c, 1) }}°C</span>
            </div>
            <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Humidity</span>
                <span class="font-semibold text-gray-800 dark:text-white">{{ number_format($latestWeather->humidity_pct, 1) }}%</span>
            </div>
            <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Light Level</span>
                <span class="font-semibold text-gray-800 dark:text-white">{{ number_format($latestWeather->light_lux, 0) }}</span>
            </div>
        </div>
        <div class="space-y-3.5">
            <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Wind Speed</span>
                <span class="font-semibold text-gray-800 dark:text-white">{{ number_format($latestWeather->wind_speed, 1) }} km/h</span>
            </div>
            <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Rain Sensor</span>
                <span>
                    @if($latestWeather->rain_pct > 0)
                        <span class="rounded-full bg-brand-50 px-2 py-0.5 text-theme-xs font-semibold text-brand-600 dark:bg-brand-500/15">Raining ({{ $latestWeather->rain_pct }})</span>
                    @else
                        <span class="rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-semibold text-success-600 dark:bg-success-500/15">No Rain</span>
                    @endif
                </span>
            </div>
            <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Total Readings</span>
                <span class="font-semibold text-gray-800 dark:text-white">{{ number_format($stats['total_readings']) }}</span>
            </div>
            <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                <span class="text-gray-500 dark:text-gray-400">Reading Age</span>
                <span class="font-semibold text-gray-800 dark:text-white">{{ $latestWeather->waktu_weather->diffForHumans() }}</span>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Weather Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Temperature & Humidity --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-5">Temperature & Humidity (24h)</h3>
        <div id="tempHumidityChart"></div>
    </div>

    {{-- Light & Wind Speed --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-5">Light Level & Wind Speed (24h)</h3>
        <div id="lightWindChart"></div>
    </div>
</div>

{{-- 7-Day Averages --}}
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-5">7-Day Weather Summary</h3>
    <div id="weeklyChart"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = Alpine.store('theme').dark;
    
    // Config common dark mode options
    const getChartThemeOptions = () => ({
        theme: { mode: Alpine.store('theme').dark ? 'dark' : 'light' },
        chart: {
            foreColor: Alpine.store('theme').dark ? '#9ca3af' : '#4b5563',
            background: 'transparent',
            toolbar: { show: false }
        }
    });

    // Fetch 24h chart data
    fetch('{{ route('weather.chart-data') }}?period=24h')
        .then(response => response.json())
        .then(data => {
            // Temp & Humidity Chart
            const options1 = {
                series: [
                    { name: 'Temperature (°C)', data: data.temperature },
                    { name: 'Humidity (%)', data: data.humidity }
                ],
                chart: { type: 'line', height: 260 },
                stroke: { width: [2, 2], curve: 'smooth' },
                colors: ['#ef4444', '#3b82f6'],
                xaxis: { categories: data.labels },
                yaxis: [
                    { title: { text: 'Temperature (°C)' } },
                    { opposite: true, title: { text: 'Humidity (%)' } }
                ],
                ...getChartThemeOptions()
            };
            const chart1 = new ApexCharts(document.querySelector("#tempHumidityChart"), options1);
            chart1.render();

            // Light & Wind Speed Chart
            const options2 = {
                series: [
                    { name: 'Light Level', data: data.light },
                    { name: 'Wind Speed (km/h)', data: data.wind }
                ],
                chart: { type: 'line', height: 260 },
                stroke: { width: [2, 2], curve: 'smooth' },
                colors: ['#f59e0b', '#22c55e'],
                xaxis: { categories: data.labels },
                yaxis: [
                    { title: { text: 'Light Level' } },
                    { opposite: true, title: { text: 'Wind Speed (km/h)' } }
                ],
                ...getChartThemeOptions()
            };
            const chart2 = new ApexCharts(document.querySelector("#lightWindChart"), options2);
            chart2.render();

            // Watch dark mode
            const observer = new MutationObserver(() => {
                const mode = Alpine.store('theme').dark ? 'dark' : 'light';
                const foreColor = Alpine.store('theme').dark ? '#9ca3af' : '#4b5563';
                
                chart1.updateOptions({ theme: { mode }, chart: { foreColor } });
                chart2.updateOptions({ theme: { mode }, chart: { foreColor } });
            });
            observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        });

    // Fetch 7-day data
    fetch('{{ route('weather.chart-data') }}?period=7d')
        .then(response => response.json())
        .then(data => {
            const options3 = {
                series: [
                    { name: 'Avg Temperature (°C)', data: data.temperature },
                    { name: 'Avg Humidity (%)', data: data.humidity }
                ],
                chart: { type: 'bar', height: 240 },
                colors: ['#ef4444', '#3b82f6'],
                xaxis: { categories: data.labels },
                plotOptions: { bar: { borderRadius: 4 } },
                ...getChartThemeOptions()
            };
            const chart3 = new ApexCharts(document.querySelector("#weeklyChart"), options3);
            chart3.render();

            const observerBar = new MutationObserver(() => {
                const mode = Alpine.store('theme').dark ? 'dark' : 'light';
                const foreColor = Alpine.store('theme').dark ? '#9ca3af' : '#4b5563';
                chart3.updateOptions({ theme: { mode }, chart: { foreColor } });
            });
            observerBar.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        });
});
</script>
@endsection
