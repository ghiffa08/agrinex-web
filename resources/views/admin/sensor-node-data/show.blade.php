@extends('layouts.admin')

@section('title', 'Sensor Node Data Details')

@section('content')
{{-- Page Breadcrumb --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.sensor-node-data.index') }}"
            class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-white/[0.05] dark:hover:text-gray-200 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Sensor Node Data #{{ $data->id }}</h2>
            <nav>
                <ol class="flex items-center gap-1.5">
                    <li>
                        <a class="text-xs text-gray-500 dark:text-gray-400" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="text-xs text-gray-400">/</li>
                    <li>
                        <a class="text-xs text-gray-500 dark:text-gray-400" href="{{ route('admin.sensor-node-data.index') }}">Sensor Node Data</a>
                    </li>
                    <li class="text-xs text-gray-400">/</li>
                    <li class="text-xs text-gray-800 dark:text-white/90">Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="flex items-center gap-3">
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
        <a href="{{ route('admin.sensor-node-data.edit', $data->id) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-warning-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-warning-600 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            Edit
        </a>
        @endif
        <a href="{{ route('admin.sensor-node-data.index') }}"
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
    {{-- Basic Info --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Basic Information</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">ID</span>
                    <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-theme-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-400">{{ $data->id }}</span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">Sesi ID</span>
                    <span class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">{{ $data->sesi_id_getdata }}</span>
                </div>
                <div class="flex justify-between items-start pt-4 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">Node</span>
                    <div class="text-right">
                        <span class="rounded-full bg-brand-50 px-2.5 py-0.5 text-theme-xs font-semibold text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">Node {{ $data->node_id }}</span>
                        @if($data->node)
                            <div class="text-xs text-gray-500 mt-1">{{ $data->node->lokasi }}</div>
                        @endif
                    </div>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400 font-medium">Received At</span>
                    <span class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ \Carbon\Carbon::parse($data->received_at)->format('Y-m-d H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Power Info --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Power Measurements</h3>
        </div>
        <div class="p-6 flex items-center justify-around h-full py-10">
            <div class="text-center">
                <div class="w-14 h-14 mx-auto rounded-full bg-success-50 dark:bg-success-500/15 flex items-center justify-center text-success-500 mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-gray-800 dark:text-white/90">{{ number_format($data->voltage_v, 2) }}V</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wider font-semibold">Voltage</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 mx-auto rounded-full bg-brand-50 dark:bg-brand-500/15 flex items-center justify-center text-brand-500 mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-gray-800 dark:text-white/90">{{ number_format($data->current_ma, 1) }}mA</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wider font-semibold">Current</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 mx-auto rounded-full bg-warning-50 dark:bg-warning-500/15 flex items-center justify-center text-warning-500 mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-gray-800 dark:text-white/90">{{ number_format($data->power_mw, 0) }}mW</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wider font-semibold">Power</p>
            </div>
        </div>
    </div>
</div>

{{-- Sensor readings --}}
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-5">Sensor Readings</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <h5 class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Temperature</h5>
            <span class="inline-flex rounded-full px-3 py-1 text-theme-sm font-semibold {{ $data->temp_c > 30 ? 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400' : 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400' }}">
                {{ number_format($data->temp_c, 1) }}°C
            </span>
        </div>
        <div>
            <h5 class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Soil Moisture</h5>
            <span class="inline-flex rounded-full px-3 py-1 text-theme-sm font-semibold {{ $data->soil_pct < 30 ? 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400' : ($data->soil_pct < 60 ? 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400' : 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400') }}">
                {{ number_format($data->soil_pct, 1) }}%
            </span>
            <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700 mt-3 overflow-hidden">
                <div class="h-2 rounded-full {{ $data->soil_pct < 30 ? 'bg-error-500' : ($data->soil_pct < 60 ? 'bg-warning-500' : 'bg-success-500') }}" style="width: {{ $data->soil_pct }}%"></div>
            </div>
        </div>
        <div>
            <h5 class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Soil ADC Value</h5>
            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-theme-sm font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-400">{{ $data->soil_adc }}</span>
            <p class="text-xs text-gray-400 mt-2">Raw analog reading</p>
        </div>
    </div>
</div>

{{-- Delete section --}}
@if(auth()->user()->role === 'admin')
<div class="rounded-2xl border border-error-200 bg-error-50/50 p-6 dark:border-error-500/20 dark:bg-error-500/5">
    <h3 class="text-lg font-semibold text-error-800 dark:text-error-400 mb-2">Danger Zone</h3>
    <p class="text-theme-sm text-error-700 dark:text-error-300/80 mb-5">Once you delete this sensor data record, there is no going back. Please be certain.</p>
    <form action="{{ route('admin.sensor-node-data.destroy', $data->id) }}" method="POST" 
          onsubmit="return confirm('Are you sure you want to delete this sensor data record?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="rounded-lg bg-error-600 px-5 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-error-700 transition-colors">
            Delete Sensor Data
        </button>
    </form>
</div>
@endif
@endsection
