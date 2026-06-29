@extends('layouts.admin')

@section('title', 'Getdata Log Details')

@section('content')
{{-- Page Breadcrumb --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.getdata-logs.index') }}"
            class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-white/[0.05] dark:hover:text-gray-200 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Getdata Log #{{ $log->id }}</h2>
            <nav>
                <ol class="flex items-center gap-1.5">
                    <li>
                        <a class="text-xs text-gray-500 dark:text-gray-400" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="text-xs text-gray-400">/</li>
                    <li>
                        <a class="text-xs text-gray-500 dark:text-gray-400" href="{{ route('admin.getdata-logs.index') }}">Getdata Logs</a>
                    </li>
                    <li class="text-xs text-gray-400">/</li>
                    <li class="text-xs text-gray-800 dark:text-white/90">Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="flex items-center gap-3">
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
        <a href="{{ route('admin.getdata-logs.edit', $log->id) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-warning-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-warning-600 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            Edit
        </a>
        @endif
        <a href="{{ route('admin.getdata-logs.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] transition-colors">
            Back
        </a>
    </div>
</div>

@if(session('success'))
<div class="mb-6 rounded-lg bg-success-50 p-4 text-theme-sm text-success-800 dark:bg-success-500/10 dark:text-success-400 flex items-center gap-3">
    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    {{-- Session Info --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Informasi Sesi</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">ID</span>
                    <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-theme-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-400">{{ $log->id }}</span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">Sesi ID Getdata</span>
                    <span class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">{{ $log->sesi_id_getdata ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">Waktu Mulai</span>
                    <span class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $log->waktu_mulai ? $log->waktu_mulai->format('d-m-Y H:i:s') : '-' }}</span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">Waktu Selesai</span>
                    <span class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $log->waktu_selesai ? $log->waktu_selesai->format('d-m-Y H:i:s') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Statistik Sesi</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">Node Sukses</span>
                    <span class="rounded-full bg-success-50 px-2.5 py-0.5 text-theme-xs font-semibold text-success-600 dark:bg-success-500/15 dark:text-success-400">{{ $log->node_sukses ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">Node Gagal</span>
                    <span class="rounded-full bg-error-50 px-2.5 py-0.5 text-theme-xs font-semibold text-error-600 dark:bg-error-500/15 dark:text-error-400">{{ $log->node_gagal ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">Status</span>
                    <span class="text-theme-sm font-semibold text-brand-600 dark:text-brand-400">{{ $log->status ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-start pt-4 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">Keterangan</span>
                    <span class="text-theme-sm text-gray-800 dark:text-white/90 text-right max-w-xs">{{ $log->keterangan ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete section --}}
@if(auth()->user()->role === 'admin')
<div class="rounded-2xl border border-error-200 bg-error-50/50 p-6 dark:border-error-500/20 dark:bg-error-500/5">
    <h3 class="text-lg font-semibold text-error-800 dark:text-error-400 mb-2">Danger Zone</h3>
    <p class="text-theme-sm text-error-700 dark:text-error-300/80 mb-5">Once you delete this session log, there is no going back. Please be certain.</p>
    <form action="{{ route('admin.getdata-logs.destroy', $log->id) }}" method="POST" 
          onsubmit="return confirm('Hapus log getdata ini?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="rounded-lg bg-error-600 px-5 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-error-700 transition-colors">
            Hapus Log Getdata
        </button>
    </form>
</div>
@endif
@endsection
