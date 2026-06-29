@extends('layouts.admin')

@section('title', 'Irrigation Session Details')

@section('content')
{{-- Page Breadcrumb --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('irrigation.index') }}"
            class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-white/[0.05] dark:hover:text-gray-200 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90 font-bold">Irrigation Session Details</h2>
            <nav>
                <ol class="flex items-center gap-1.5">
                    <li>
                        <a class="text-xs text-gray-500 dark:text-gray-400" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="text-xs text-gray-400">/</li>
                    <li>
                        <a class="text-xs text-gray-500 dark:text-gray-400" href="{{ route('irrigation.index') }}">Irrigation</a>
                    </li>
                    <li class="text-xs text-gray-400">/</li>
                    <li class="text-xs text-gray-800 dark:text-white/90">Session {{ $session->session_id }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <a href="{{ route('irrigation.index') }}"
        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] transition-colors">
        Back to Irrigation
    </a>
</div>

{{-- Session Summary Card --}}
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-5">Session Information</h3>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div>
            <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block">Session ID</span>
            <span class="text-theme-sm font-semibold text-gray-800 dark:text-white mt-1 block">{{ $session->session_id }}</span>
        </div>
        <div>
            <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block">Started At</span>
            <span class="text-theme-sm font-medium text-gray-800 dark:text-white mt-1 block">{{ $session->started_at ? $session->started_at->format('Y-m-d H:i:s') : '-' }}</span>
        </div>
        <div>
            <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block">Successful Nodes</span>
            <span class="text-theme-sm font-bold text-success-600 dark:text-success-400 mt-1 block">{{ $session->success_count ?? 0 }}</span>
        </div>
        <div>
            <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block">Failed Nodes</span>
            <span class="text-theme-sm font-bold text-error-600 dark:text-error-400 mt-1 block">{{ $session->failed_count ?? 0 }}</span>
        </div>
        <div>
            <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider block">Valves Still On</span>
            <span class="text-theme-sm font-bold text-warning-600 dark:text-warning-400 mt-1 block">{{ $session->valve_on_count ?? 0 }}</span>
        </div>
    </div>
</div>

{{-- Valve Logs Table --}}
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-6">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Valve Operation Logs</h3>
    </div>
    
    @if($valveLogs->isEmpty())
        <div class="text-center py-10 px-6">
            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-700 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="text-gray-500 dark:text-gray-400 text-sm font-semibold">No Valve Logs Found</p>
            <p class="text-xs text-gray-400 mt-1">No valve operations recorded for this session.</p>
        </div>
    @else
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Timestamp</th>
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Node ID</th>
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Location</th>
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Duration</th>
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($valveLogs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                        <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">
                            {{ $log->logged_at ? $log->logged_at->format('Y-m-d H:i:s') : '-' }}
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap">
                            <a href="{{ route('nodes.show', $log->device_id) }}" class="text-brand-500 hover:text-brand-600 transition-colors font-semibold text-theme-sm">
                                Device {{ $log->device_id }}
                            </a>
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-700 dark:text-gray-300">{{ $log->node->lokasi ?? '-' }}</td>
                        <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-700 dark:text-gray-300">
                            @php
                                $duration = $log->durasi_detik ?? 0;
                                $minutes = floor($duration / 60);
                                $seconds = $duration % 60;
                            @endphp
                            @if($minutes > 0)
                                <strong>{{ $minutes }}</strong> min 
                            @endif
                            <strong>{{ $seconds }}</strong> sec
                            <div class="text-xs text-gray-400 mt-0.5">({{ $duration }} seconds)</div>
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap">
                            @if($log->status === 'ON')
                                <span class="rounded-full bg-success-50 px-2.5 py-0.5 text-theme-xs font-semibold text-success-600 dark:bg-success-500/15">ON</span>
                            @elseif($log->status === 'OFF')
                                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-theme-xs font-semibold text-gray-600 dark:bg-gray-850">OFF</span>
                            @else
                                <span class="rounded-full bg-warning-50 px-2.5 py-0.5 text-theme-xs font-semibold text-warning-600 dark:bg-warning-500/15">{{ $log->status ?? 'Unknown' }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap text-center">
                            <a href="{{ route('nodes.show', $log->device_id) }}" 
                               class="inline-flex items-center gap-1 text-brand-500 hover:text-brand-600 transition-colors font-medium text-theme-sm">
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
                <tfoot class="border-t border-gray-150 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                    <tr>
                        <td colspan="3" class="py-3 px-6 text-right font-medium text-gray-500 dark:text-gray-400">Total Duration:</td>
                        <td colspan="3" class="py-3 px-6 text-theme-sm text-gray-800 dark:text-white font-semibold">
                            @php
                                $totalSeconds = $valveLogs->sum('durasi_detik');
                                $totalMinutes = floor($totalSeconds / 60);
                                $remainingSeconds = $totalSeconds % 60;
                            @endphp
                            {{ $totalMinutes }} minutes {{ $remainingSeconds }} seconds
                            <span class="text-xs text-gray-400 font-normal">({{ $totalSeconds }} seconds total)</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>

{{-- Timeline Visualization --}}
@if($valveLogs->isNotEmpty())
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-5">Operation Timeline</h3>
    <div class="relative border-l border-gray-200 dark:border-gray-800 pl-6 ml-3 space-y-6">
        @foreach($valveLogs as $index => $log)
        <div class="relative">
            {{-- Marker --}}
            <span class="absolute -left-9 mt-1.5 flex h-6 w-6 items-center justify-center rounded-full bg-white dark:bg-gray-950 border-2 {{ $log->status === 'ON' ? 'border-success-500' : 'border-gray-300' }}">
                <span class="h-2 w-2 rounded-full {{ $log->status === 'ON' ? 'bg-success-500' : 'bg-gray-300' }}"></span>
            </span>
            
            <div class="flex flex-wrap items-center justify-between gap-3 p-4 rounded-xl border border-gray-100 bg-gray-50/30 dark:border-gray-850 dark:bg-gray-900/30">
                <div>
                    <h5 class="text-theme-sm font-bold text-gray-800 dark:text-white">
                        Device {{ $log->device_id }} - 
                        <span class="text-xs font-semibold px-2 py-0.5 rounded {{ $log->status === 'ON' ? 'bg-success-50 text-success-600 dark:bg-success-500/15' : 'bg-gray-100 text-gray-600 dark:bg-gray-800' }}">
                            {{ $log->status }}
                        </span>
                    </h5>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ $log->logged_at ? $log->logged_at->format('H:i:s') : '-' }} | Duration: {{ $log->durasi_detik ?? 0 }}s
                    </p>
                </div>
                <a href="{{ route('nodes.show', $log->device_id) }}" 
                   class="text-brand-500 hover:text-brand-600 text-xs font-semibold">
                    Details
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
