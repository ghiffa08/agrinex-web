@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
{{-- Page Header --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90 font-bold">Reports & Analytics</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Generate and export system reports</p>
    </div>
</div>

{{-- Date Range Filter --}}
<div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] mb-6">
    <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
        <div>
            <label for="start_date" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
            <input type="date" name="start_date" id="start_date" 
                   value="{{ request('start_date', now()->subDays(7)->format('Y-m-d')) }}"
                   class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
        </div>
        <div>
            <label for="end_date" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
            <input type="date" name="end_date" id="end_date" 
                   value="{{ request('end_date', now()->format('Y-m-d')) }}"
                   class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="inline-flex justify-center items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Apply
            </button>
            <a href="{{ route('reports.index') }}" class="inline-flex justify-center items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] transition-colors">
                Reset
            </a>
        </div>
        
        {{-- Export Dropdown using AlpineJS --}}
        <div x-data="{ dropdownOpen: false }" class="relative flex justify-end">
            <button @click="dropdownOpen = !dropdownOpen" type="button" 
                    class="inline-flex items-center gap-2 rounded-lg bg-success-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-success-600 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Data
                <svg class="w-4 h-4 ml-1 transition-transform" :class="dropdownOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" x-transition 
                 class="absolute right-0 bottom-full mb-2 z-10 w-56 rounded-lg border border-gray-200 bg-white p-1.5 shadow-lg dark:border-gray-800 dark:bg-gray-950" 
                 style="display: none;">
                <a class="flex items-center gap-2 rounded-md px-3 py-2 text-theme-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/[0.03] transition-colors" 
                   href="{{ route('reports.export', ['type' => 'sensor', 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}">
                    <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Sensor Data CSV
                </a>
                <a class="flex items-center gap-2 rounded-md px-3 py-2 text-theme-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/[0.03] transition-colors" 
                   href="{{ route('reports.export', ['type' => 'irrigation', 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}">
                    <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Irrigation Data CSV
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Summary Statistics --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Irrigation Summary --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-5 flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v13m0-13V6a2 2 0 112 2h-2m0-5a5 5 0 015 5v3a5 5 0 01-5 5m0-13v6m0-6H9" />
            </svg>
            Irrigation Summary
        </h3>
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <h4 class="text-xl font-bold text-brand-500">{{ $irrigationStats['total_events'] }}</h4>
                <p class="text-xs text-gray-500 mt-1 uppercase font-semibold">Total Events</p>
            </div>
            <div>
                <h4 class="text-xl font-bold text-sky-500">{{ number_format($irrigationStats['total_duration'], 0) }}</h4>
                <p class="text-xs text-gray-500 mt-1 uppercase font-semibold">Total Duration (min)</p>
            </div>
            <div>
                <h4 class="text-xl font-bold text-success-500">N/A</h4>
                <p class="text-xs text-gray-500 mt-1 uppercase font-semibold">Avg Duration (min)</p>
            </div>
        </div>
    </div>

    {{-- Sensor Data Summary --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-5 flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Sensor Data Summary
        </h3>
        <div class="grid grid-cols-4 gap-2 text-center">
            <div>
                <h4 class="text-lg font-bold text-brand-500">{{ number_format($sensorStats['total_readings']) }}</h4>
                <p class="text-[10px] text-gray-500 mt-1 uppercase font-semibold">Total Readings</p>
            </div>
            <div>
                <h4 class="text-lg font-bold text-success-500">{{ number_format($sensorStats['avg_moisture'], 1) }}%</h4>
                <p class="text-[10px] text-gray-500 mt-1 uppercase font-semibold">Avg Soil Moisture</p>
            </div>
            <div>
                <h4 class="text-lg font-bold text-error-500">{{ number_format($sensorStats['avg_temp'], 1) }}°C</h4>
                <p class="text-[10px] text-gray-500 mt-1 uppercase font-semibold">Avg Temp</p>
            </div>
            <div>
                <h4 class="text-lg font-bold text-warning-500">{{ number_format($sensorStats['min_moisture'], 1) }}%</h4>
                <p class="text-[10px] text-gray-500 mt-1 uppercase font-semibold">Min Moisture</p>
            </div>
        </div>
    </div>
</div>

{{-- Node Activity --}}
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-6">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Node Activity</h3>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Node ID</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Group</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Location</th>
                    <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Total Readings</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Avg Moisture</th>
                    <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Avg Temperature</th>
                    <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($nodeActivity as $activity)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="py-3 px-6 whitespace-nowrap">
                        <span class="text-theme-sm font-semibold text-gray-800 dark:text-white">Device {{ $activity->device_id }}</span>
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">{{ $activity->device->group ?? '-' }}</td>
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400 font-medium">{{ $activity->device->lokasi ?? '-' }}</td>
                    <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300">{{ number_format($activity->reading_count) }}</td>
                    <td class="py-3 px-6 whitespace-nowrap">
                        <span class="rounded-full px-2.5 py-0.5 text-theme-xs font-semibold {{ $activity->avg_moisture < 30 ? 'bg-error-50 text-error-600 dark:bg-error-500/15' : 'bg-success-50 text-success-600 dark:bg-success-500/15' }}">
                            {{ number_format($activity->avg_moisture, 1) }}%
                        </span>
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300">{{ number_format($activity->avg_temp, 1) }}°C</td>
                    <td class="py-3 px-6 whitespace-nowrap">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('reports.by-node', $activity->device_id) }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}" 
                               class="inline-flex items-center gap-1 text-brand-500 hover:text-brand-600 font-semibold text-theme-sm">
                                Detailed Report
                            </a>
                            <a href="{{ route('nodes.show', $activity->device_id) }}" 
                               class="inline-flex items-center gap-1 text-gray-500 hover:text-gray-700 font-semibold text-theme-sm">
                                View Node
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-10 px-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                        No data available for the selected period
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Daily Summary --}}
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daily Summary</h3>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Total Readings</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Avg Moisture</th>
                    <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Avg Temperature</th>
                    <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Min Moisture</th>
                    <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Max Temperature</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($dailySummary as $day)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-800 dark:text-white font-medium">
                        {{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300">{{ number_format($day->total_readings) }}</td>
                    <td class="py-3 px-6 whitespace-nowrap">
                        <span class="rounded-full px-2.5 py-0.5 text-theme-xs font-semibold {{ $day->avg_moisture < 30 ? 'bg-error-50 text-error-600 dark:bg-error-500/15' : 'bg-success-50 text-success-600 dark:bg-success-500/15' }}">
                            {{ number_format($day->avg_moisture, 1) }}%
                        </span>
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300">{{ number_format($day->avg_temp, 1) }}°C</td>
                    <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300">{{ number_format($day->min_moisture, 1) }}%</td>
                    <td class="py-3 px-6 whitespace-nowrap text-right">
                        <span class="rounded-full px-2.5 py-0.5 text-theme-xs font-semibold {{ $day->max_temp > 35 ? 'bg-warning-50 text-warning-600 dark:bg-warning-500/15' : 'bg-sky-50 text-sky-500 dark:bg-sky-500/15 dark:text-sky-400' }}">
                            {{ number_format($day->max_temp, 1) }}°C
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 px-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                        No data available for the selected period
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
