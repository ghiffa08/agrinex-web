@extends('layouts.admin')

@section('title', 'System Alerts')

@section('content')
{{-- Page Header --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90 font-bold">System Alerts</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Monitor system health and active warnings</p>
    </div>
</div>

{{-- Statistics Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="rounded-2xl border border-error-200 bg-white p-5 dark:border-error-800/30 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Critical</span>
                <h4 class="mt-2 text-2xl font-bold text-error-600 dark:text-error-450">{{ $stats['critical'] }}</h4>
                <span class="text-xs text-gray-400 mt-1.5 block">Requires immediate attention</span>
            </div>
            <div class="w-12 h-12 rounded-full bg-error-50 dark:bg-error-500/15 flex items-center justify-center text-error-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-warning-200 bg-white p-5 dark:border-warning-800/30 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Warning</span>
                <h4 class="mt-2 text-2xl font-bold text-warning-650 dark:text-warning-400">{{ $stats['warning'] }}</h4>
                <span class="text-xs text-gray-400 mt-1.5 block">Needs attention</span>
            </div>
            <div class="w-12 h-12 rounded-full bg-warning-50 dark:bg-warning-500/15 flex items-center justify-center text-warning-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-sky-100 bg-white p-5 dark:border-sky-500/30 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Info</span>
                <h4 class="mt-2 text-2xl font-bold text-brand-500 dark:text-brand-400">{{ $stats['info'] }}</h4>
                <span class="text-xs text-gray-400 mt-1.5 block">Informational</span>
            </div>
            <div class="w-12 h-12 rounded-full bg-sky-50 dark:bg-sky-500/15 flex items-center justify-center text-sky-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</span>
                <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">{{ $stats['total'] }}</h4>
                <span class="text-xs text-gray-400 mt-1.5 block">All alerts</span>
            </div>
            <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- Quick Filter Buttons --}}
<div class="flex flex-wrap gap-2.5 mb-6">
    <a href="{{ route('alerts.index') }}" class="rounded-lg bg-brand-500 px-4 py-2 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
        All Alerts
    </a>
    <a href="{{ route('alerts.by-type', 'moisture') }}" class="rounded-lg border border-error-300 bg-white px-4 py-2 text-theme-sm font-medium text-error-700 shadow-theme-xs hover:bg-error-50 transition-colors dark:border-error-800/30 dark:bg-gray-900 dark:text-error-400">
        Low Moisture
    </a>
    <a href="{{ route('alerts.by-type', 'temperature') }}" class="rounded-lg border border-warning-300 bg-white px-4 py-2 text-theme-sm font-medium text-warning-700 shadow-theme-xs hover:bg-warning-50 transition-colors dark:border-warning-800/30 dark:bg-gray-900 dark:text-warning-400">
        High Temperature
    </a>
    <a href="{{ route('alerts.by-type', 'voltage') }}" class="rounded-lg border border-warning-300 bg-white px-4 py-2 text-theme-sm font-medium text-warning-700 shadow-theme-xs hover:bg-warning-50 transition-colors dark:border-warning-800/30 dark:bg-gray-900 dark:text-warning-400">
        Low Voltage
    </a>
    <a href="{{ route('alerts.by-type', 'communication') }}" class="rounded-lg border border-sky-200 bg-white px-4 py-2 text-theme-sm font-medium text-sky-600 shadow-theme-xs hover:bg-sky-50 transition-colors dark:border-sky-800/30 dark:bg-gray-900 dark:text-sky-400">
        Communication
    </a>
</div>

{{-- Offline Nodes - Critical --}}
@if($offlineNodes->isNotEmpty())
<div class="rounded-2xl border border-error-200 bg-white dark:border-error-800/30 dark:bg-white/[0.03] overflow-hidden mb-6">
    <div class="px-6 py-4 bg-error-500 text-white flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <h3 class="text-lg font-semibold">Offline Nodes ({{ $offlineNodes->count() }})</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($offlineNodes as $node)
            <div class="rounded-xl border border-gray-150 p-4 dark:border-gray-800">
                <h4 class="font-bold text-gray-800 dark:text-white">Device {{ $node->id }}</h4>
                <div class="space-y-1 mt-2 text-xs text-gray-500 dark:text-gray-400">
                    <div><strong>Location:</strong> {{ $node->lokasi ?? 'N/A' }}</div>
                    <div><strong>Group:</strong> {{ $node->group ?? 'N/A' }}</div>
                    <div class="text-error-500 font-semibold mt-1.5 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        No data received in last 2 hours
                    </div>
                </div>
                <a href="{{ route('nodes.show', $node->id) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] mt-4">
                    View Details
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Low Moisture - Critical --}}
@if($lowMoisture->isNotEmpty())
<div class="rounded-2xl border border-error-200 bg-white dark:border-error-800/30 dark:bg-white/[0.03] overflow-hidden mb-6">
    <div class="px-6 py-4 bg-error-500 text-white flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
        </svg>
        <h3 class="text-lg font-semibold">Low Soil Moisture ({{ $lowMoisture->count() }})</h3>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Node ID</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Location</th>
                    <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Moisture Level</th>
                    <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Temperature</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Last Reading</th>
                    <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($lowMoisture as $data)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="py-3 px-6 whitespace-nowrap">
                        <strong class="text-theme-sm text-gray-800 dark:text-white">Device {{ $data->device_id }}</strong>
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">{{ $data->device->lokasi ?? 'N/A' }}</td>
                    <td class="py-3 px-6 whitespace-nowrap text-center">
                        <span class="rounded-full bg-error-50 px-2.5 py-0.5 text-theme-xs font-semibold text-error-600 dark:bg-error-500/15">
                            {{ number_format($data->soil_pct, 1) }}%
                        </span>
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300 font-semibold">{{ number_format($data->temp_c, 1) }}°C</td>
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">{{ $data->recorded_at->diffForHumans() }}</td>
                    <td class="py-3 px-6 whitespace-nowrap text-center">
                        <a href="{{ route('nodes.show', $data->device_id) }}" class="inline-flex items-center gap-1 text-brand-500 hover:text-brand-600 font-semibold text-theme-sm">
                            View Node
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- High Temperature - Warning --}}
@if($highTemp->isNotEmpty())
<div class="rounded-2xl border border-warning-200 bg-white dark:border-warning-800/30 dark:bg-white/[0.03] overflow-hidden mb-6">
    <div class="px-6 py-4 bg-warning-500 text-gray-900 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <h3 class="text-lg font-semibold">High Temperature ({{ $highTemp->count() }})</h3>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Node ID</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Location</th>
                    <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Temperature</th>
                    <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Moisture</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Last Reading</th>
                    <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($highTemp as $data)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm font-semibold text-gray-800 dark:text-white">Device {{ $data->device_id }}</td>
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">{{ $data->device->lokasi ?? 'N/A' }}</td>
                    <td class="py-3 px-6 whitespace-nowrap text-center">
                        <span class="rounded-full bg-warning-50 px-2.5 py-0.5 text-theme-xs font-semibold text-warning-650 dark:bg-warning-500/15">
                            {{ number_format($data->temp_c, 1) }}°C
                        </span>
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300 font-semibold">{{ number_format($data->soil_pct, 1) }}%</td>
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">{{ $data->recorded_at->diffForHumans() }}</td>
                    <td class="py-3 px-6 whitespace-nowrap text-center">
                        <a href="{{ route('nodes.show', $data->device_id) }}" class="inline-flex items-center gap-1 text-brand-500 hover:text-brand-600 font-semibold text-theme-sm">
                            View Node
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Low Voltage - Warning --}}
@if($lowVoltage->isNotEmpty())
<div class="rounded-2xl border border-warning-200 bg-white dark:border-warning-800/30 dark:bg-white/[0.03] overflow-hidden mb-6">
    <div class="px-6 py-4 bg-warning-500 text-gray-900 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <h3 class="text-lg font-semibold">Low Voltage ({{ $lowVoltage->count() }})</h3>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Node ID</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Location</th>
                    <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Voltage</th>
                    <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Current</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Last Reading</th>
                    <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($lowVoltage as $data)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm font-semibold text-gray-800 dark:text-white">Device {{ $data->device_id }}</td>
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">{{ $data->device->lokasi ?? 'N/A' }}</td>
                    <td class="py-3 px-6 whitespace-nowrap text-center">
                        <span class="rounded-full bg-warning-50 px-2.5 py-0.5 text-theme-xs font-semibold text-warning-650 dark:bg-warning-500/15">
                            {{ number_format($data->voltage_v, 2) }}V
                        </span>
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300 font-semibold">{{ number_format($data->current_ma, 0) }} mA</td>
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">{{ $data->recorded_at->diffForHumans() }}</td>
                    <td class="py-3 px-6 whitespace-nowrap text-center">
                        <a href="{{ route('nodes.show', $data->device_id) }}" class="inline-flex items-center gap-1 text-brand-500 hover:text-brand-600 font-semibold text-theme-sm">
                            View Node
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Communication Failures - Info --}}
@if($commFailures->isNotEmpty())
<div class="rounded-2xl border border-sky-100 bg-white dark:border-sky-850 dark:bg-white/[0.03] overflow-hidden mb-6">
    <div class="px-6 py-4 bg-sky-500 text-white flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-lg font-semibold">Communication Issues ({{ $commFailures->count() }})</h3>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Node ID</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Time</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">RSSI</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">SNR</th>
                    <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Signal Quality</th>
                    <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($commFailures->take(20) as $log)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm font-semibold text-gray-800 dark:text-white">Device {{ $log->device_id }}</td>
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">{{ $log->logged_at->format('d/m/Y H:i') }}</td>
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-700 dark:text-gray-300 font-semibold">{{ $log->rssi_dbm }} dBm</td>
                    <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-700 dark:text-gray-300 font-semibold">{{ $log->snr_db }} dB</td>
                    <td class="py-3 px-6 whitespace-nowrap text-center">
                        <span class="rounded-full bg-error-50 px-2.5 py-0.5 text-theme-xs font-semibold text-error-600 dark:bg-error-500/15">
                            {{ $log->signal_quality }}
                        </span>
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap text-center">
                        <a href="{{ route('nodes.show', $log->device_id) }}" class="inline-flex items-center gap-1 text-brand-500 hover:text-brand-600 font-semibold text-theme-sm">
                            View Node
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- No Alerts --}}
@if($offlineNodes->isEmpty() && $lowMoisture->isEmpty() && $highTemp->isEmpty() && $lowVoltage->isEmpty() && $commFailures->isEmpty())
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-10 text-center">
    <div class="w-16 h-16 bg-success-50 dark:bg-success-500/15 rounded-full flex items-center justify-center text-success-500 mx-auto mb-4">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <h3 class="text-lg font-bold text-gray-800 dark:text-white/90">All Systems Operational</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-md mx-auto">No alerts at this time. All nodes are operating normally.</p>
</div>
@endif
@endsection
