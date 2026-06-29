@extends('layouts.admin')

@section('title', 'Irrigation Management')

@section('content')
<div x-data="{ triggerModal: false }">
    {{-- Page Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90 font-bold">Irrigation Management</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Monitor and control irrigation system</p>
        </div>
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
        <button type="button" @click="triggerModal = true"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Trigger Irrigation
        </button>
        @endif
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-lg bg-success-50 p-4 text-theme-sm text-success-800 dark:bg-success-500/10 dark:text-success-400 flex items-center gap-3">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Events</span>
                    <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">{{ $stats['total_events'] }}</h4>
                    <span class="text-xs text-gray-400 mt-1.5 block">All time</span>
                </div>
                <div class="w-12 h-12 rounded-full bg-brand-50 dark:bg-brand-500/15 flex items-center justify-center text-brand-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v13m0-13V6a2 2 0 112 2h-2m0-5a5 5 0 015 5v3a5 5 0 01-5 5m0-13v6m0-6H9" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Today's Events</span>
                    <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">{{ $stats['today_events'] }}</h4>
                    <span class="text-xs text-gray-400 mt-1.5 block">Last 24 hours</span>
                </div>
                <div class="w-12 h-12 rounded-full bg-sky-50 dark:bg-sky-500/15 flex items-center justify-center text-sky-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active Valves</span>
                    <h4 class="mt-2 text-2xl font-bold text-success-600 dark:text-success-400">{{ $stats['active_valves'] }}</h4>
                    <span class="text-xs text-gray-400 mt-1.5 block">Currently running</span>
                </div>
                <div class="w-12 h-12 rounded-full bg-success-50 dark:bg-success-500/15 flex items-center justify-center text-success-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Duration</span>
                    <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">{{ number_format($stats['total_duration'], 0) }} min</h4>
                    <span class="text-xs text-gray-400 mt-1.5 block">Today</span>
                </div>
                <div class="w-12 h-12 rounded-full bg-warning-50 dark:bg-warning-500/15 flex items-center justify-center text-warning-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Irrigation Sessions --}}
    @if($activeIrrigation->isNotEmpty())
    <div class="mb-6 rounded-2xl border border-sky-100 bg-sky-50/50 p-6 dark:border-sky-800/30 dark:bg-sky-500/10">
        <h4 class="text-lg font-semibold text-brand-700 dark:text-brand-400 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Active Irrigation Sessions
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($activeIrrigation as $active)
            <div class="rounded-xl border border-sky-200 bg-white p-4 dark:bg-gray-950 dark:border-sky-800/30">
                <h5 class="text-theme-sm font-bold text-gray-800 dark:text-white mb-2">Device {{ $active->device_id }}</h5>
                <div class="space-y-1.5 text-xs text-gray-600 dark:text-gray-400">
                    <div><strong>Session:</strong> {{ $active->session_id }}</div>
                    <div><strong>Started:</strong> {{ $active->waktu ? $active->waktu->format('d/m/Y H:i') : '-' }}</div>
                    <div><strong>Duration:</strong> {{ floor(($active->durasi_detik ?? 0) / 60) }} min</div>
                    @if($active->volume_air)
                    <div><strong>Volume:</strong> {{ number_format($active->volume_air, 2) }} L</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Irrigation Logs --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Irrigation History</h3>
        </div>
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Session ID</th>
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Start Time</th>
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">End Time</th>
                        <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Success Nodes</th>
                        <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Failed Nodes</th>
                        <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Valves ON</th>
                        <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Duration</th>
                        <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($irrigationLogs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                        <td class="py-3 px-6 whitespace-nowrap text-theme-sm font-semibold text-gray-800 dark:text-white">{{ $log->session_id }}</td>
                        <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($log->started_at)->format('d/m/Y H:i') }}
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">
                            @if($log->ended_at)
                                {{ \Carbon\Carbon::parse($log->ended_at)->format('d/m/Y H:i') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap text-center">
                            <span class="rounded-full bg-success-50 px-2.5 py-0.5 text-theme-xs font-semibold text-success-600 dark:bg-success-500/15">{{ $log->success_count ?? 0 }}</span>
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap text-center">
                            @if($log->failed_count > 0)
                                <span class="rounded-full bg-error-50 px-2.5 py-0.5 text-theme-xs font-semibold text-error-600 dark:bg-error-500/15">{{ $log->failed_count }}</span>
                            @else
                                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-theme-xs font-semibold text-gray-600 dark:bg-gray-800">0</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap text-center">
                            <span class="rounded-full bg-brand-50 px-2.5 py-0.5 text-theme-xs font-semibold text-brand-600 dark:bg-brand-500/15">{{ $log->valve_on_count ?? 0 }}</span>
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap text-center">
                            @if($log->started_at && $log->ended_at)
                                @php
                                    $duration = \Carbon\Carbon::parse($log->started_at)->diffInMinutes(\Carbon\Carbon::parse($log->ended_at));
                                @endphp
                                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-theme-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">{{ $duration }} min</span>
                            @else
                                <span class="rounded-full bg-warning-50 px-2.5 py-0.5 text-theme-xs font-semibold text-warning-600 dark:bg-warning-500/15">Ongoing</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap text-center">
                            <a href="{{ route('irrigation.history', $log->session_id) }}" 
                               class="inline-flex items-center gap-1 text-brand-500 hover:text-brand-600 transition-colors font-medium text-theme-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-10 px-6 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-700 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No irrigation logs found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($irrigationLogs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
            {{ $irrigationLogs->links() }}
        </div>
        @endif
    </div>

    {{-- Trigger Irrigation Modal --}}
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
    <div x-show="triggerModal" class="fixed inset-0 z-[99] flex items-center justify-center p-4" style="display: none;" x-cloak>
        {{-- Backdrop --}}
        <div @click="triggerModal = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
        
        {{-- Modal Content --}}
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-950 border border-gray-100 dark:border-gray-850">
            <form method="POST" action="{{ route('irrigation.trigger') }}">
                @csrf
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-5">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v13m0-13V6a2 2 0 112 2h-2m0-5a5 5 0 015 5v3a5 5 0 01-5 5m0-13v6m0-6H9" />
                        </svg>
                        Trigger Irrigation
                    </h3>
                    <button type="button" @click="triggerModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-250">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label for="device_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Select Device</label>
                        <select name="device_id" id="device_id" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                            <option value="">-- Select Node --</option>
                            @foreach($nodes as $node)
                            <option value="{{ $node->id }}">
                                Device {{ $node->id }} - {{ $node->lokasi ?? 'N/A' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="duration" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (minutes)</label>
                        <input type="number" name="duration" id="duration" min="1" max="120" value="30" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                        <p class="text-xs text-gray-400 mt-1">Recommended: 15-60 minutes</p>
                    </div>

                    <div class="rounded-xl border border-warning-200 bg-warning-50 p-4 dark:border-warning-500/20 dark:bg-warning-500/5">
                        <div class="flex gap-2 text-warning-700 dark:text-warning-400 text-sm">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <strong>Note:</strong> This will manually trigger irrigation for the selected node.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800 mt-6">
                    <button type="button" @click="triggerModal = false"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-brand-500 px-5 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                        Start Irrigation
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
