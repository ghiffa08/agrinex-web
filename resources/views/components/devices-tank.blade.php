{{-- Devices & Water Tank Section --}}
<section class="h-full flex flex-col">
    <!-- Header with total count -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div>
                <h2 class="text-xl font-bold text-primary flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Perangkat
                    <span class="text-xs sm:text-sm font-medium px-2 sm:px-3 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full border border-emerald-200 dark:border-emerald-800/50" x-text="devices.length + ' Node'">12 Node</span>
                </h2>
                <p class="text-[10px] sm:text-xs text-secondary mt-0.5 truncate w-[180px] sm:w-auto">Monitoring real-time sensor nodes</p>
            </div>
        </div>
        <button @click="refreshDevices()" 
            class="bg-white hover:bg-gray-50 dark:bg-slate-800 dark:hover:bg-slate-700 text-secondary dark:text-slate-200 px-4 py-2 xl:px-5 xl:py-2.5 rounded-full flex items-center gap-2 transition-all shadow-sm border border-gray-200 dark:border-slate-700 font-medium text-xs xl:text-sm">
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
            </svg>
            <span x-text="t('refresh')">Refresh</span>
        </button>
    </div>

    <!-- Devices Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" x-show="devices.length && !loadingDevices">
        <template x-for="d in devices" :key="d.device_id">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-700/50 transition-all duration-300 overflow-hidden group cursor-pointer flex flex-col h-full"
                @click="window.location.href = '/nodes/' + d.id">
                
                <!-- Header with Node Name and Status -->
                <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-800 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-primary text-lg" x-text="`Node ${d.plot_number}`">Node 1</h3>
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                                    :class="d.connection_state === 'online' ? 'bg-emerald-400' : 'bg-gray-400'"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2"
                                    :class="d.connection_state === 'online' ? 'bg-emerald-500' : 'bg-gray-400 dark:bg-gray-500'"></span>
                            </span>
                            <span class="text-xs font-semibold uppercase tracking-wider" 
                                :class="d.connection_state === 'online' ? 'text-emerald-600 dark:text-emerald-400' : 'text-secondary'"
                                x-text="d.connection_state === 'online' ? 'Online' : 'Offline'">Offline</span>
                        </div>
                    </div>
                    <!-- Treatment Description -->
                    <div class="text-[11px] text-secondary leading-tight mt-1.5 truncate" x-text="d.treatment_description || 'Irigasi saat kelembaban ≤ 80% FC'">
                    </div>
                </div>

                <!-- Main Content -->
                <div class="p-5 space-y-5 flex-1 flex flex-col justify-between">
                    <!-- Kelembaban & Suhu (Clean Grid) -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Kelembaban -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-50/50 dark:bg-blue-950/30 text-blue-500 flex items-center justify-center shrink-0 border border-blue-100/50 dark:border-blue-900/20">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C12 2 6 9 6 13a6 6 0 0 0 12 0c0-4-6-11-6-11z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-secondary uppercase tracking-wider block">Lembap</span>
                                <span class="text-2xl font-black text-primary" x-text="d.soil_moisture_pct ? Math.round(d.soil_moisture_pct) + '%' : '-'">-</span>
                            </div>
                        </div>

                        <!-- Suhu -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-orange-50/50 dark:bg-orange-950/30 text-orange-500 flex items-center justify-center shrink-0 border border-orange-100/50 dark:border-orange-900/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-secondary uppercase tracking-wider block">Suhu</span>
                                <span class="text-2xl font-black text-primary" x-text="d.air_temp_c ? Math.round(d.air_temp_c) + '°' : (d.soil_temp_c ? Math.round(d.soil_temp_c) + '°' : '-')">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Clean List parameters (No inner cards) -->
                    <div class="space-y-2 pt-4 border-t border-gray-100 dark:border-slate-800/60">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-secondary font-medium">FC Target</span>
                            <span class="font-bold text-primary" x-text="d.fc_target ? d.fc_target.toFixed(2) + '%' : '-'">-</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-secondary font-medium">Threshold</span>
                            <span class="font-bold text-primary" x-text="d.threshold ? d.threshold.toFixed(2) + '%' : '-'">-</span>
                        </div>
                    </div>
 
                    <!-- Update Time -->
                    <div class="text-[10px] text-secondary opacity-60 font-medium text-center pt-2 border-t border-gray-100 dark:border-slate-800/60">
                        <span x-text="'Update: ' + timeAgo(d.recorded_at || d.last_seen)">Update: 7 hours ago</span>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Loading State -->
    <template x-if="loadingDevices">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
            <template x-for="i in 3" :key="'skeleton-'+i">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-4 space-y-3 animate-pulse">
                    <div class="bg-gray-100 dark:bg-slate-700 h-20 rounded-lg"></div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="h-16 bg-gray-100 dark:bg-slate-700 rounded-lg"></div>
                        <div class="h-16 bg-gray-100 dark:bg-slate-700 rounded-lg"></div>
                    </div>
                    <div class="h-10 bg-gray-100 dark:bg-slate-700 rounded-lg"></div>
                </div>
            </template>
        </div>
    </template>

    <!-- Empty State -->
    <div x-show="!devices.length && !loadingDevices" x-cloak class="flex flex-col items-center justify-center flex-1 py-12">
        <div class="inline-flex items-center justify-center p-4 bg-gray-50 dark:bg-slate-900 rounded-full mb-4">
            <svg class="w-12 h-12 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-primary mb-1">Tidak Ada Data Perangkat</h3>
        <p class="text-sm text-secondary">Belum ada device yang terdaftar atau sedang offline</p>
    </div>
</section>
