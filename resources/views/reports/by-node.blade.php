@extends('layouts.admin')

@section('title', 'Report for Device #' . $node->id)

@section('content')
{{-- Page Breadcrumb --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('reports.index') }}"
            class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-white/[0.05] dark:hover:text-gray-200 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90 font-bold">Report for Device #{{ $node->id }}</h2>
            <nav>
                <ol class="flex items-center gap-1.5">
                    <li>
                        <a class="text-xs text-gray-500 dark:text-gray-400" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="text-xs text-gray-400">/</li>
                    <li>
                        <a class="text-xs text-gray-500 dark:text-gray-400" href="{{ route('reports.index') }}">Reports</a>
                    </li>
                    <li class="text-xs text-gray-400">/</li>
                    <li class="text-xs text-gray-800 dark:text-white/90">Device #{{ $node->id }} Report</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('reports.export', ['type' => 'sensor', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
            Export Sensor CSV
        </a>
        <a href="{{ route('reports.export', ['type' => 'irrigation', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 transition-colors">
            Export Irrigation CSV
        </a>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    {{-- Statistics Card --}}
    <div>
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Statistics</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-theme-sm text-gray-500 dark:text-gray-400">Total readings</span>
                        <span class="text-theme-sm font-semibold text-gray-800 dark:text-white">{{ number_format($stats['total_readings'] ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-800">
                        <span class="text-theme-sm text-gray-500 dark:text-gray-400">Average moisture</span>
                        <span class="text-theme-sm font-semibold text-gray-800 dark:text-white">{{ number_format($stats['avg_moisture'] ?? 0, 2) }}%</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-800">
                        <span class="text-theme-sm text-gray-500 dark:text-gray-400">Average temperature</span>
                        <span class="text-theme-sm font-semibold text-gray-800 dark:text-white">{{ number_format($stats['avg_temp'] ?? 0, 2) }}°C</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-800">
                        <span class="text-theme-sm text-gray-500 dark:text-gray-400">Irrigation events</span>
                        <span class="text-theme-sm font-semibold text-gray-800 dark:text-white">{{ number_format($stats['irrigation_events'] ?? 0) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sensor Data Table --}}
    <div class="xl:col-span-2">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Sensor data ({{ $sensorData->total() }} rows)</h3>
            </div>
            
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                            <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">ID</th>
                            <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Received at</th>
                            <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Soil %</th>
                            <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Temp (°C)</th>
                            <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Voltage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($sensorData as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">{{ $row->id }}</td>
                            <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">{{ $row->recorded_at }}</td>
                            <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300 font-semibold">{{ $row->soil_pct }}%</td>
                            <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300 font-semibold">{{ $row->temp_c }}°C</td>
                            <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300 font-semibold">{{ $row->voltage_v ?? '-' }}V</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $sensorData->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection