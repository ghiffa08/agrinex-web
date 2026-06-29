@extends('layouts.admin')

@section('title', $title)

@section('content')
{{-- Page Breadcrumb --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('alerts.index') }}"
            class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-white/[0.05] dark:hover:text-gray-200 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90 font-bold">{{ $title }}</h2>
            <nav>
                <ol class="flex items-center gap-1.5">
                    <li>
                        <a class="text-xs text-gray-500 dark:text-gray-400" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="text-xs text-gray-400">/</li>
                    <li>
                        <a class="text-xs text-gray-500 dark:text-gray-400" href="{{ route('alerts.index') }}">Alerts</a>
                    </li>
                    <li class="text-xs text-gray-400">/</li>
                    <li class="text-xs text-gray-800 dark:text-white/90 font-medium">{{ ucfirst($type) }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <a href="{{ route('alerts.index') }}"
        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] transition-colors">
        Back to All Alerts
    </a>
</div>

{{-- Quick Filter Buttons --}}
<div class="flex flex-wrap gap-2.5 mb-6">
    <a href="{{ route('alerts.by-type', 'moisture') }}" 
       class="rounded-lg px-4 py-2 text-theme-sm font-medium shadow-theme-xs transition-colors {{ $type === 'moisture' ? 'bg-error-500 text-white hover:bg-error-600' : 'border border-error-300 bg-white text-error-750 hover:bg-error-50 dark:border-error-800/30 dark:bg-gray-900 dark:text-error-400' }}">
        Low Moisture
    </a>
    <a href="{{ route('alerts.by-type', 'temperature') }}" 
       class="rounded-lg px-4 py-2 text-theme-sm font-medium shadow-theme-xs transition-colors {{ $type === 'temperature' ? 'bg-warning-500 text-gray-900 hover:bg-warning-600' : 'border border-warning-300 bg-white text-warning-750 hover:bg-warning-50 dark:border-warning-800/30 dark:bg-gray-900 dark:text-warning-400' }}">
        High Temperature
    </a>
    <a href="{{ route('alerts.by-type', 'voltage') }}" 
       class="rounded-lg px-4 py-2 text-theme-sm font-medium shadow-theme-xs transition-colors {{ $type === 'voltage' ? 'bg-warning-500 text-gray-900 hover:bg-warning-600' : 'border border-warning-300 bg-white text-warning-750 hover:bg-warning-50 dark:border-warning-800/30 dark:bg-gray-900 dark:text-warning-400' }}">
        Low Voltage
    </a>
    <a href="{{ route('alerts.by-type', 'communication') }}" 
       class="rounded-lg px-4 py-2 text-theme-sm font-medium shadow-theme-xs transition-colors {{ $type === 'communication' ? 'bg-brand-500 text-white hover:bg-brand-600' : 'border border-sky-200 bg-white text-sky-600 hover:bg-sky-50 dark:border-sky-800/30 dark:bg-gray-900 dark:text-sky-400' }}">
        Communication Issues
    </a>
</div>

{{-- Alerts Table --}}
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h3>
    </div>
    
    @if($alerts->isEmpty())
        <div class="text-center py-10 px-6">
            <div class="w-12 h-12 bg-success-50 dark:bg-success-500/15 rounded-full flex items-center justify-center text-success-500 mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h5 class="text-theme-sm font-bold text-gray-800 dark:text-white">No {{ ucfirst($type) }} Alerts</h5>
            <p class="text-xs text-gray-400 mt-1">Everything is working fine!</p>
        </div>
    @else
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Time</th>
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Node ID</th>
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Location</th>
                        @if($type === 'moisture')
                            <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Soil Moisture</th>
                            <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Severity</th>
                        @elseif($type === 'temperature')
                            <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Temperature</th>
                            <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Severity</th>
                        @elseif($type === 'voltage')
                            <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Voltage</th>
                            <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Battery %</th>
                            <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Severity</th>
                        @elseif($type === 'communication')
                            <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">RSSI</th>
                            <th class="py-3 px-6 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">SNR</th>
                            <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Signal Quality</th>
                            <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Status</th>
                        @endif
                        <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($alerts as $alert)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                        @if($type === 'communication')
                            <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">
                                {{ $alert->logged_at ? $alert->logged_at->format('Y-m-d H:i:s') : '-' }}
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap">
                                <a href="{{ route('nodes.show', $alert->device_id) }}" class="text-brand-500 hover:text-brand-600 transition-colors font-semibold text-theme-sm">
                                    Device {{ $alert->device_id }}
                                </a>
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-700 dark:text-gray-300 font-medium">{{ $alert->device->lokasi ?? '-' }}</td>
                            <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300 font-semibold">{{ $alert->rssi ?? '-' }} dBm</td>
                            <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300 font-semibold">{{ $alert->snr ?? '-' }} dB</td>
                            <td class="py-3 px-6 whitespace-nowrap text-center">
                                @php
                                    $quality = $alert->signal_quality ?? 'Unknown';
                                    $badgeClass = match($quality) {
                                        'Excellent', 'Good' => 'bg-success-50 text-success-600 dark:bg-success-500/15',
                                        'Fair' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15',
                                        'Poor' => 'bg-error-50 text-error-600 dark:bg-error-500/15',
                                        default => 'bg-gray-100 text-gray-600 dark:bg-gray-800'
                                    };
                                @endphp
                                <span class="rounded-full px-2.5 py-0.5 text-theme-xs font-semibold {{ $badgeClass }}">{{ $quality }}</span>
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap text-center">
                                @if($alert->status === 'Aktif')
                                    <span class="rounded-full bg-success-50 px-2.5 py-0.5 text-theme-xs font-semibold text-success-600 dark:bg-success-500/15">Active</span>
                                @else
                                    <span class="rounded-full bg-error-50 px-2.5 py-0.5 text-theme-xs font-semibold text-error-600 dark:bg-error-500/15">Inactive</span>
                                @endif
                            </td>
                        @else
                            <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">
                                {{ $alert->recorded_at ? $alert->recorded_at->format('Y-m-d H:i:s') : '-' }}
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap">
                                <a href="{{ route('nodes.show', $alert->device_id) }}" class="text-brand-500 hover:text-brand-600 transition-colors font-semibold text-theme-sm">
                                    Device {{ $alert->device_id }}
                                </a>
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-700 dark:text-gray-300 font-medium">{{ $alert->device->lokasi ?? '-' }}</td>
                            
                            @if($type === 'moisture')
                                <td class="py-3 px-6 whitespace-nowrap text-right">
                                    <span class="rounded-full bg-error-50 px-2.5 py-0.5 text-theme-xs font-semibold text-error-600 dark:bg-error-500/15">
                                        {{ $alert->moist ?? 0 }}%
                                    </span>
                                </td>
                                <td class="py-3 px-6 whitespace-nowrap text-center">
                                    @if($alert->moist < 20)
                                        <span class="rounded-full bg-error-50 px-2.5 py-0.5 text-theme-xs font-semibold text-error-600 dark:bg-error-500/15">Critical</span>
                                    @elseif($alert->moist < 30)
                                        <span class="rounded-full bg-warning-50 px-2.5 py-0.5 text-theme-xs font-semibold text-warning-600 dark:bg-warning-500/15">Warning</span>
                                    @else
                                        <span class="rounded-full bg-sky-50 text-sky-500 px-2.5 py-0.5 text-theme-xs font-semibold dark:bg-sky-500/15 dark:text-sky-400">Info</span>
                                    @endif
                                </td>
                            @elseif($type === 'temperature')
                                <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-800 dark:text-white font-semibold">
                                    {{ $alert->temp_ds18 ?? 0 }}°C
                                </td>
                                <td class="py-3 px-6 whitespace-nowrap text-center">
                                    @if($alert->temp_ds18 > 40)
                                        <span class="rounded-full bg-error-50 px-2.5 py-0.5 text-theme-xs font-semibold text-error-600 dark:bg-error-500/15">Critical</span>
                                    @elseif($alert->temp_ds18 > 35)
                                        <span class="rounded-full bg-warning-50 px-2.5 py-0.5 text-theme-xs font-semibold text-warning-650 dark:bg-warning-500/15">Warning</span>
                                    @else
                                        <span class="rounded-full bg-sky-50 text-sky-500 px-2.5 py-0.5 text-theme-xs font-semibold dark:bg-sky-500/15 dark:text-sky-400">Info</span>
                                    @endif
                                </td>
                            @elseif($type === 'voltage')
                                <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-800 dark:text-white font-semibold">
                                    {{ $alert->volt ?? 0 }}V
                                </td>
                                <td class="py-3 px-6 whitespace-nowrap text-right text-theme-sm text-gray-700 dark:text-gray-300">
                                    @php
                                        $batteryPct = (($alert->volt ?? 0) - 2.5) / (4.2 - 2.5) * 100;
                                        $batteryPct = max(0, min(100, $batteryPct));
                                    @endphp
                                    {{ number_format($batteryPct, 1) }}%
                                </td>
                                <td class="py-3 px-6 whitespace-nowrap text-center">
                                    @if($alert->volt < 3.0)
                                        <span class="rounded-full bg-error-50 px-2.5 py-0.5 text-theme-xs font-semibold text-error-600 dark:bg-error-500/15">Critical</span>
                                    @elseif($alert->volt < 3.3)
                                        <span class="rounded-full bg-warning-50 px-2.5 py-0.5 text-theme-xs font-semibold text-warning-600 dark:bg-warning-500/15">Warning</span>
                                    @else
                                        <span class="rounded-full bg-sky-50 text-sky-500 px-2.5 py-0.5 text-theme-xs font-semibold dark:bg-sky-500/15 dark:text-sky-400">Info</span>
                                    @endif
                                </td>
                            @endif
                        @endif
                        
                        <td class="py-3 px-6 whitespace-nowrap text-center">
                            <a href="{{ route('nodes.show', $alert->device_id) }}" 
                               class="inline-flex items-center gap-1 text-brand-500 hover:text-brand-600 font-semibold text-theme-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Node
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($alerts->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
            {{ $alerts->links() }}
        </div>
        @endif
    @endif
</div>
@endsection
