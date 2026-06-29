@extends('layouts.admin')

@section('title', 'Irrigate Logs')

@section('content')
{{-- Page Breadcrumb --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Irrigation Session Logs</h2>
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
            <li class="text-sm text-gray-800 dark:text-white/90 font-medium">Irrigate Logs</li>
        </ol>
    </nav>
</div>

<div class="space-y-6">
    <div x-data="{ showFilter: false }" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
                Irrigate Session Logs
            </h3>
            <div class="flex items-center gap-3">
                <button @click="showFilter = !showFilter" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.irrigate-logs.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
                    </svg>
                    Reset
                </a>
            </div>
        </div>
        
        {{-- Filter Form --}}
        <div x-show="showFilter" x-transition class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-900/30 p-6" style="display: none;">
            <form method="GET" action="{{ route('admin.irrigate-logs.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                </div>
                <div>
                    <label class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search
                    </button>
                </div>
            </form>
        </div>
        
        {{-- Table --}}
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">ID</th>
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Sesi ID</th>
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Waktu Mulai</th>
                        <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Waktu Akhir</th>
                        <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Node Sukses</th>
                        <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Node Gagal</th>
                        <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Valve ON Akhir</th>
                        <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                        <td class="py-3 px-6 whitespace-nowrap">
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-theme-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">{{ $log->id }}</span>
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap text-theme-sm font-semibold text-gray-800 dark:text-white">{{ $log->sesi_id_irrigate }}</td>
                        <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($log->waktu_mulai)->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">
                            {{ $log->waktu_akhir ? \Carbon\Carbon::parse($log->waktu_akhir)->format('Y-m-d H:i:s') : '-' }}
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap text-center">
                            <span class="rounded-full bg-success-50 px-2.5 py-0.5 text-theme-xs font-semibold text-success-600 dark:bg-success-500/15">{{ $log->node_sukses }}</span>
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap text-center">
                            <span class="rounded-full bg-error-50 px-2.5 py-0.5 text-theme-xs font-semibold text-error-600 dark:bg-error-500/15">{{ $log->node_gagal }}</span>
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap text-center">
                            <span class="rounded-full bg-warning-50 px-2.5 py-0.5 text-theme-xs font-semibold text-warning-600 dark:bg-warning-500/15">{{ $log->valve_on_akhir }}</span>
                        </td>
                        <td class="py-3 px-6 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.irrigate-logs.show', $log->id) }}" class="text-brand-500 hover:text-brand-600 transition-colors" title="View">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
                                <a href="{{ route('admin.irrigate-logs.edit', $log->id) }}" class="text-warning-500 hover:text-warning-600 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                @endif
                                @if(auth()->user()->role === 'admin')
                                <form action="{{ route('admin.irrigate-logs.destroy', $log->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error-500 hover:text-error-600 transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
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
        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Showing <span class="font-medium text-gray-800 dark:text-white/90">{{ $logs->firstItem() ?? 0 }}</span> to <span class="font-medium text-gray-800 dark:text-white/90">{{ $logs->lastItem() ?? 0 }}</span> of <span class="font-medium text-gray-800 dark:text-white/90">{{ $logs->total() }}</span> entries
                </div>
                <div>
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
