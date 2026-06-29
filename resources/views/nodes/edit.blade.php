@extends('layouts.admin')

@section('title', 'Edit Node ' . $node->id)

@section('content')
{{-- Page Breadcrumb --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('nodes.show', $node->id) }}"
            class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-white/[0.05] dark:hover:text-gray-200 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Edit Node {{ $node->id }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update node information and configuration</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    {{-- Edit Form --}}
    <div class="xl:col-span-2">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Node Information</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('nodes.update', $node->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <label for="node_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Node ID *</label>
                        <input type="text" id="node_id" value="{{ $node->id }}" disabled
                            class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-gray-500 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed">
                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Node ID cannot be changed</p>
                    </div>

                    <div class="mb-5">
                        <label for="group" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Group</label>
                        <input type="text" id="group" name="group" value="{{ old('group', $node->group) }}" placeholder="e.g., A, B, C"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90 dark:focus:border-brand-500 @error('group') border-error-500 focus:border-error-500 focus:ring-error-500 @enderror">
                        @error('group')
                            <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Group classification for this node</p>
                    </div>

                    <div class="mb-5">
                        <label for="kode_perlakuan" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Treatment Code</label>
                        <input type="text" id="kode_perlakuan" name="kode_perlakuan" value="{{ old('kode_perlakuan', $node->kode_perlakuan) }}" placeholder="e.g., T1, T2, Control"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90 dark:focus:border-brand-500 @error('kode_perlakuan') border-error-500 focus:border-error-500 focus:ring-error-500 @enderror">
                        @error('kode_perlakuan')
                            <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Treatment code or identifier</p>
                    </div>

                    <div class="mb-5">
                        <label for="lokasi" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $node->lokasi) }}" placeholder="e.g., Greenhouse A, Field 1, Plot 12"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90 dark:focus:border-brand-500 @error('lokasi') border-error-500 focus:border-error-500 focus:ring-error-500 @enderror">
                        @error('lokasi')
                            <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Physical location of the node</p>
                    </div>

                    <div class="mb-6">
                        <label for="keterangan" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Description / Notes</label>
                        <textarea id="keterangan" name="keterangan" rows="4" placeholder="Additional notes or description about this node..."
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90 dark:focus:border-brand-500 @error('keterangan') border-error-500 focus:border-error-500 focus:ring-error-500 @enderror">{{ old('keterangan', $node->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-1.5 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Any additional information or notes</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                        <a href="{{ route('nodes.show', $node->id) }}"
                            class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 transition-colors">
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

    {{-- Sidebars --}}
    <div class="space-y-6">
        {{-- Current Status --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Current Status</h3>
            </div>
            <div class="p-6">
                @php
                    $latestData = $node->latestSensorData;
                    $isOnline = $latestData && 
                                $latestData->received_at && 
                                $latestData->received_at->diffInHours(now()) < 1;
                @endphp
                
                <div class="space-y-4">
                    <div>
                        <span class="block text-theme-xs text-gray-500 dark:text-gray-400 mb-1">Connection Status</span>
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
                    </div>

                    @if($latestData)
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                            <span class="block text-theme-xs text-gray-500 dark:text-gray-400 mb-1">Last Communication</span>
                            <span class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $latestData->received_at->diffForHumans() }}</span>
                        </div>

                        <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                            <span class="block text-theme-xs text-gray-500 dark:text-gray-400 mb-1">Latest Temperature</span>
                            <span class="text-theme-sm font-bold text-gray-800 dark:text-white/90">{{ number_format($latestData->temp_ds18 ?? 0, 1) }}°C</span>
                        </div>

                        <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                            <span class="block text-theme-xs text-gray-500 dark:text-gray-400 mb-1">Latest Moisture</span>
                            <span class="text-theme-sm font-bold {{ ($latestData->moist ?? 0) < 30 ? 'text-error-500' : 'text-success-500' }}">
                                {{ number_format($latestData->moist ?? 0, 1) }}%
                            </span>
                        </div>

                        <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                            <span class="block text-theme-xs text-gray-500 dark:text-gray-400 mb-1">Battery Voltage</span>
                            <span class="text-theme-sm font-bold {{ ($latestData->volt ?? 0) < 3.0 ? 'text-warning-500' : 'text-success-500' }}">
                                {{ number_format($latestData->volt ?? 0, 2) }}V
                            </span>
                        </div>
                    @else
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-800 text-center py-3">
                            <svg class="w-8 h-8 mx-auto text-gray-400 dark:text-gray-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="text-theme-sm text-gray-500 dark:text-gray-400">No sensor data available</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Node Info --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Node Summary</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-theme-sm text-gray-500 dark:text-gray-400">Created</span>
                        <span class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $node->created_at ? $node->created_at->format('d M Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-800">
                        <span class="text-theme-sm text-gray-500 dark:text-gray-400">Last Updated</span>
                        <span class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $node->updated_at ? $node->updated_at->format('d M Y H:i') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-800">
                        <span class="text-theme-sm text-gray-500 dark:text-gray-400">Total Readings</span>
                        <span class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $node->sensorNodeData()->count() ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Help Info --}}
        <div class="rounded-2xl border border-brand-200 bg-brand-50 p-5 dark:border-brand-500/20 dark:bg-brand-500/5">
            <h4 class="flex items-center gap-2 text-theme-sm font-semibold text-brand-700 dark:text-brand-400 mb-2.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Editing Tips
            </h4>
            <ul class="space-y-1.5 list-disc pl-5 text-theme-xs text-brand-600 dark:text-brand-300/80">
                <li>Node ID is read-only and cannot be changed</li>
                <li>All fields are optional except where marked with *</li>
                <li>Changes will be saved immediately</li>
                <li>Sensor data will not be affected</li>
            </ul>
        </div>
    </div>
</div>
@endsection
