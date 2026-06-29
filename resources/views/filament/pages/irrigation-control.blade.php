<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Stats Widgets -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-filament::section>
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Total Events</span>
                        <div class="text-2xl font-extrabold mt-1 text-gray-900 dark:text-white" x-text="'{{ $stats['total_events'] }}'"></div>
                    </div>
                    <div class="p-3 bg-primary-50 dark:bg-primary-950/40 rounded-xl text-primary-500">
                        <x-filament::icon icon="heroicon-o-beaker" class="h-6 w-6 text-emerald-500" />
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Today's Events</span>
                        <div class="text-2xl font-extrabold mt-1 text-gray-900 dark:text-white" x-text="'{{ $stats['today_events'] }}'"></div>
                    </div>
                    <div class="p-3 bg-primary-50 dark:bg-primary-950/40 rounded-xl text-primary-500">
                        <x-filament::icon icon="heroicon-o-calendar" class="h-6 w-6 text-emerald-500" />
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Active Solenoids</span>
                        <div class="text-2xl font-extrabold mt-1 text-gray-900 dark:text-white" x-text="'{{ $stats['active_valves'] }}'"></div>
                    </div>
                    <div class="p-3 bg-primary-50 dark:bg-primary-950/40 rounded-xl text-primary-500">
                        <x-filament::icon icon="heroicon-o-bolt" class="h-6 w-6 text-emerald-500" />
                    </div>
                </div>
            </x-filament::section>
        </div>

        <!-- 2-Column Grid for Control Form and Active Valving -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Manual Trigger Form (2 cols) -->
            <x-filament::section class="lg:col-span-2">
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-filament::icon icon="heroicon-o-wrench-screwdriver" class="h-5 w-5 text-emerald-500" />
                        <span>Trigger Manual Irrigation</span>
                    </div>
                </x-slot>

                <form wire:submit.prevent="triggerIrrigation" class="space-y-4">
                    <div>
                        <label for="deviceId" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Select Target Node</label>
                        <select wire:model="deviceId" id="deviceId" required class="block w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">-- Select Target COM Node --</option>
                            @foreach($nodes as $node)
                                <option value="{{ $node->node_id }}">Node {{ $node->node_id }} ({{ $node->lokasi ?? 'No Location' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="duration" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Duration (Seconds)</label>
                        <input type="number" min="1" max="3600" wire:model="duration" id="duration" required class="block w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-emerald-500 focus:border-emerald-500">
                        <span class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 block">Maximum duration is 3600 seconds (1 hour).</span>
                    </div>

                    <div class="pt-2">
                        <x-filament::button type="submit" color="success" class="w-full">
                            Trigger Manual Irrigation
                        </x-filament::button>
                    </div>
                </form>
            </x-filament::section>

            <!-- Active Valves List (1 col) -->
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <span>Active Solenoids</span>
                    </div>
                </x-slot>

                <div class="space-y-4">
                    @forelse($activeIrrigation as $active)
                        <div class="p-3 bg-emerald-50/50 dark:bg-emerald-950/20 rounded-xl border border-emerald-100 dark:border-emerald-900/30 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-emerald-800 dark:text-emerald-400">Node {{ $active->node_id }}</span>
                                <span class="block text-[10px] text-gray-500 dark:text-gray-400">Location: {{ $active->node->lokasi ?? '-' }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $active->durasi_detik }}s</span>
                                <span class="block text-[10px] text-gray-500 dark:text-gray-400">{{ $active->waktu }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500 dark:text-gray-400 italic text-xs">
                            No solenoid valves currently active.
                        </div>
                    @endforelse
                </div>
            </x-filament::section>
        </div>

        <!-- Recent Logs Table -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-list-bullet" class="h-5 w-5 text-emerald-500" />
                    <span>Recent Irrigation Log Sessions</span>
                </div>
            </x-slot>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b dark:border-gray-800 text-gray-500 uppercase tracking-wider font-bold">
                            <th class="py-3 px-4">Session ID</th>
                            <th class="py-3 px-4">Start Time</th>
                            <th class="py-3 px-4">Nodes Success</th>
                            <th class="py-3 px-4">Nodes Failed</th>
                            <th class="py-3 px-4">Final Valve State</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($irrigationLogs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                <td class="py-3 px-4 font-mono font-bold text-gray-900 dark:text-white">{{ $log->sesi_id_irrigate }}</td>
                                <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ $log->waktu_mulai }}</td>
                                <td class="py-3 px-4 text-emerald-600 dark:text-emerald-400 font-semibold">{{ $log->node_sukses }}</td>
                                <td class="py-3 px-4 text-rose-500 font-semibold">{{ $log->node_gagal }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $log->valve_on_akhir ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-gray-105 text-gray-800 dark:bg-gray-800 dark:text-gray-400' }}">
                                        {{ $log->valve_on_akhir ? 'VALVE ON' : 'VALVE OFF' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-500 dark:text-gray-400 italic">No irrigation logs recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
