@extends('layouts.admin')

@section('title', 'Sensor Nodes')

@section('content')
{{-- Page Breadcrumb --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Sensor Nodes</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li>
                <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                    href="{{ route('dashboard') }}">
                    Home
                    <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none">
                        <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="" stroke-width="1.2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </li>
            <li class="text-sm text-gray-800 dark:text-white/90">Sensor Nodes</li>
        </ol>
    </nav>
</div>

{{-- Metric Cards --}}
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6 mb-6">
    {{-- Total Nodes --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex items-center justify-center w-12 h-12 bg-brand-50 rounded-xl dark:bg-brand-500/[0.15]">
            <svg class="fill-brand-500 dark:fill-brand-400" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0"
                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"
                    fill="currentColor" />
            </svg>
        </div>
        <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Total Nodes</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
                {{ $stats['total'] }}
            </h4>
        </div>
    </div>

    {{-- Active Nodes --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex items-center justify-center w-12 h-12 bg-success-50 rounded-xl dark:bg-success-500/[0.15]">
            <svg class="text-success-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Active Nodes</span>
            <h4 class="mt-2 font-bold text-success-500 text-title-sm dark:text-success-400">
                {{ $stats['active'] }}
            </h4>
        </div>
    </div>

    {{-- Offline Nodes --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex items-center justify-center w-12 h-12 bg-error-50 rounded-xl dark:bg-error-500/[0.15]">
            <svg class="text-error-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Offline Nodes</span>
            <h4 class="mt-2 font-bold text-error-500 text-title-sm dark:text-error-400">
                {{ $stats['offline'] }}
            </h4>
        </div>
    </div>

    {{-- Alerts --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex items-center justify-center w-12 h-12 bg-warning-50 rounded-xl dark:bg-warning-500/[0.15]">
            <svg class="text-warning-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </div>
        <div class="mt-5">
            <span class="text-sm text-gray-500 dark:text-gray-400">Alerts</span>
            <h4 class="mt-2 font-bold text-warning-500 text-title-sm dark:text-warning-400">
                {{ $stats['alerts'] }}
            </h4>
        </div>
    </div>
</div>

{{-- Nodes Table --}}
<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">All Sensor Nodes</h3>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Node ID</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Group</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Treatment Code</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Location</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Latest Reading</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Temperature</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Soil Moisture</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Voltage</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Signal</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($nodes as $node)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="py-3 px-6 whitespace-nowrap">
                        <span class="font-semibold text-gray-800 text-theme-sm dark:text-white">{{ $node->id }}</span>
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap text-gray-500 text-theme-sm dark:text-gray-400">
                        {{ $node->group ?? '-' }}
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap text-gray-500 text-theme-sm dark:text-gray-400">
                        {{ $node->kode_perlakuan ?? '-' }}
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap text-gray-500 text-theme-sm dark:text-gray-400">
                        {{ $node->lokasi ?? '-' }}
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap text-gray-500 text-theme-sm dark:text-gray-400">
                        @if($node->latestSensorData)
                            {{ $node->latestSensorData->received_at->format('d/m/Y H:i') }}
                        @else
                            No data
                        @endif
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap">
                        @if($node->latestSensorData)
                            <span class="text-theme-sm text-error-500 dark:text-error-400">
                                {{ number_format($node->latestSensorData->temp_ds18, 1) }}°C
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap">
                        @if($node->latestSensorData)
                            @php
                                $moist = $node->latestSensorData->moist;
                                $colorClass = $moist < 30 ? 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400' : 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400';
                            @endphp
                            <span class="rounded-full px-2.5 py-0.5 text-theme-xs font-medium {{ $colorClass }}">
                                {{ number_format($moist, 1) }}%
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap">
                        @if($node->latestSensorData)
                            @php
                                $volt = $node->latestSensorData->volt;
                                $colorClass = $volt < 3.0 ? 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400' : 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400';
                            @endphp
                            <span class="rounded-full px-2.5 py-0.5 text-theme-xs font-medium {{ $colorClass }}">
                                {{ number_format($volt, 2) }}V
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap">
                        @if($node->logs->isNotEmpty())
                            @php
                                $latestLog = $node->logs->first();
                                $signalQuality = $latestLog->signal_quality ?? 'UNKNOWN';
                                $badgeClass = match($signalQuality) {
                                    'GOOD' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
                                    'FAIR' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                                    default => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400'
                                };
                            @endphp
                            <span class="rounded-full px-2.5 py-0.5 text-theme-xs font-medium {{ $badgeClass }}">
                                {{ $signalQuality }}
                            </span>
                        @else
                            <span class="rounded-full px-2.5 py-0.5 text-theme-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap">
                        @php
                            $isOnline = $node->latestSensorData && 
                                        $node->latestSensorData->received_at->diffInHours(now()) < 1;
                        @endphp
                        @if($isOnline)
                            <span class="flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-theme-xs font-medium bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 w-fit">
                                <span class="w-1.5 h-1.5 rounded-full bg-success-500 animate-pulse"></span>
                                Online
                            </span>
                        @else
                            <span class="flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-theme-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 w-fit">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                Offline
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-6 whitespace-nowrap">
                        <a href="{{ route('nodes.show', $node->id) }}" 
                           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-theme-xs font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="py-10 px-6 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-700 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No sensor nodes found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
