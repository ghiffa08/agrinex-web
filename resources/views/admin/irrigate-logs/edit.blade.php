@extends('layouts.admin')

@section('title', 'Edit Irrigate Log')

@section('content')
{{-- Page Breadcrumb --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.irrigate-logs.show', $log->id) }}"
            class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-white/[0.05] dark:hover:text-gray-200 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit Irrigate Log #{{ $log->id }}</h2>
            <nav>
                <ol class="flex items-center gap-1.5">
                    <li>
                        <a class="text-xs text-gray-500 dark:text-gray-400" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="text-xs text-gray-400">/</li>
                    <li>
                        <a class="text-xs text-gray-500 dark:text-gray-400" href="{{ route('admin.irrigate-logs.index') }}">Irrigate Logs</a>
                    </li>
                    <li class="text-xs text-gray-400">/</li>
                    <li>
                        <a class="text-xs text-gray-500 dark:text-gray-400" href="{{ route('admin.irrigate-logs.show', $log->id) }}">Detail</a>
                    </li>
                    <li class="text-xs text-gray-400">/</li>
                    <li class="text-xs text-gray-800 dark:text-white/90">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@if($errors->any())
<div class="mb-6 rounded-lg bg-error-50 p-4 text-theme-sm text-error-800 dark:bg-error-500/10 dark:text-error-400">
    <ul class="list-disc pl-5 space-y-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    {{-- Edit Form --}}
    <div class="xl:col-span-2">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Edit Irrigate Log</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.irrigate-logs.update', $log->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label for="node_sukses" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Node Sukses</label>
                            <input type="number" id="node_sukses" name="node_sukses" min="0" value="{{ old('node_sukses', $log->node_sukses) }}"
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                        </div>

                        <div>
                            <label for="node_gagal" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Node Gagal</label>
                            <input type="number" id="node_gagal" name="node_gagal" min="0" value="{{ old('node_gagal', $log->node_gagal) }}"
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="valve_on_akhir" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Valve ON Akhir</label>
                        <input type="number" id="valve_on_akhir" name="valve_on_akhir" min="0" value="{{ old('valve_on_akhir', $log->valve_on_akhir) }}"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                        <a href="{{ route('admin.irrigate-logs.show', $log->id) }}"
                            class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="rounded-lg bg-brand-500 px-5 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar Info --}}
    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Info Sesi</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">ID</span>
                    <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-theme-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-400">{{ $log->id }}</span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400">Sesi ID Irigasi</span>
                    <span class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">{{ $log->sesi_id_irrigate ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-start pt-4 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">Waktu Mulai</span>
                    <span class="text-theme-sm font-medium text-gray-800 dark:text-white/90 text-right">{{ $log->waktu_mulai ? $log->waktu_mulai->format('d-m-Y H:i:s') : '-' }}</span>
                </div>
                <div class="flex justify-between items-start pt-4 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">Waktu Akhir</span>
                    <span class="text-theme-sm font-medium text-gray-800 dark:text-white/90 text-right">{{ $log->waktu_akhir ? $log->waktu_akhir->format('d-m-Y H:i:s') : '-' }}</span>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-brand-200 bg-brand-50 p-5 dark:border-brand-500/20 dark:bg-brand-500/5">
            <h4 class="flex items-center gap-2 text-theme-sm font-semibold text-brand-700 dark:text-brand-400 mb-2.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Tips
            </h4>
            <ul class="space-y-1.5 list-disc pl-5 text-theme-xs text-brand-600 dark:text-brand-300/80">
                <li>Make sure the counts for successful/failed nodes match the system dashboard telemetry.</li>
                <li>Valve ON Akhir represents the final status of active valves.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
