{{-- Devices & Water Tank Section --}}
<section class="bg-white/60 dark:bg-slate-800/50 backdrop-blur-xl border border-white/60 dark:border-white/8 rounded-[2rem] p-6 shadow-lg dark:shadow-black/30 h-full flex flex-col">
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
            class="bg-white/80 hover:bg-white dark:bg-slate-800/60 dark:hover:bg-slate-700 text-secondary dark:text-slate-200 px-4 py-2 xl:px-5 xl:py-2.5 rounded-full flex items-center gap-2 transition-all shadow-sm font-medium text-xs xl:text-sm border border-white/50 dark:border-white/10">
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span x-text="t('refresh')">Refresh</span>
        </button>
    </div> <!-- restored closing div for header flex -->

    <!-- Devices Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" x-show="devices.length && !loadingDevices">
        <template x-for="d in devices" :key="d.device_id">
            <div class="card !p-0 shadow-lg hover:shadow-xl hover:bg-white/5 dark:hover:bg-slate-800/50 transition-all duration-300 overflow-hidden group cursor-pointer hover:-translate-y-1 flex flex-col h-full"
                @click="window.location.href = '{{ url('/nodes') }}/' + d.device_id">
                
                <!-- Header with Node Name and Status -->
                <div class="bg-white/50 dark:bg-slate-800/50 px-5 py-4 border-b border-gray-200/50 dark:border-white/5 flex-shrink-0">
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="font-bold text-primary text-base" x-text="`Node ${d.plot_number}`">Node 1</h3>
                        <div class="flex items-center gap-1.5 px-3 py-1 bg-white dark:bg-slate-700 rounded-full shadow-sm border border-gray-100 dark:border-slate-600">
                            <div class="w-2 h-2 rounded-full animate-pulse" 
                                :class="d.connection_state === 'online' ? 'bg-emerald-500' : 'bg-gray-400 dark:bg-gray-500'"></div>
                            <span class="text-[10px] font-bold uppercase tracking-wider" 
                                :class="d.connection_state === 'online' ? 'text-emerald-600 dark:text-emerald-400' : 'text-secondary dark:text-gray-400'"
                                x-text="d.connection_state === 'online' ? 'Online' : 'Offline'">Online</span>
                        </div>
                    </div>
                    <!-- Treatment Description -->
                    <div class="text-[11px] text-secondary leading-tight mt-2 line-clamp-2" x-text="d.treatment_description || 'Irigasi saat kelembaban ≤ 80% FC'">
                    </div>
                </div>

                <!-- Main Content -->
                <div class="p-5 space-y-4 flex-1 flex flex-col">
                    <!-- Kelembaban & Suhu (Big Display) -->
                    <div class="grid grid-cols-2 gap-4 mb-auto">
                        <!-- Kelembaban -->
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="p-1.5 bg-[#C7E0ED]/50 dark:bg-blue-900/30 rounded-lg border border-white dark:border-white/5">
                                    <svg class="w-4 h-4 text-[#1c73a5] dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C12 2 6 9 6 13a6 6 0 0 0 12 0c0-4-6-11-6-11z" />
                                    </svg>
                                </div>
                                <div class="text-[10px] font-bold text-secondary uppercase tracking-wider truncate">Lembap</div>
                            </div>
                            <div class="text-2xl font-extrabold text-primary" x-text="d.soil_moisture_pct ? Math.round(d.soil_moisture_pct) + '%' : '-'">51%</div>
                        </div>

                        <!-- Suhu -->
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="p-1.5 bg-orange-50 dark:bg-orange-900/30 rounded-lg border border-white dark:border-white/5">
                                    <svg class="w-4 h-4 text-[#EB5011] dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div class="text-[10px] font-bold text-secondary uppercase tracking-wider truncate">Suhu</div>
                            </div>
                            <div class="text-2xl font-extrabold text-primary" x-text="d.air_temp_c ? Math.round(d.air_temp_c) + '°' : (d.soil_temp_c ? Math.round(d.soil_temp_c) + '°' : '-')">29°</div>
                        </div>
                    </div>

                    <!-- Treatment Details -->
                    <div class="bg-white/60 dark:bg-slate-800/60 rounded-2xl p-4 border border-white/60 dark:border-white/10 shadow-sm">
                        <div class="space-y-2">
                            <!-- FC Target -->
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-medium text-secondary">FC Target:</span>
                                <span class="text-sm font-bold text-primary" x-text="d.fc_target ? d.fc_target.toFixed(2) + '%' : '-'">80.00%</span>
                            </div>
                            <!-- Threshold -->
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-medium text-secondary">Threshold:</span>
                                <span class="text-sm font-bold text-primary" x-text="d.threshold ? d.threshold.toFixed(2) + '%' : '-'">17.70%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Update Time -->
                    <div class="text-[10px] text-secondary opacity-80 font-medium text-center pt-2 border-t border-gray-200/50 dark:border-white/5">
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
                <div class="card !p-0 shadow-sm overflow-hidden animate-pulse">
                    <div class="bg-gray-200 dark:bg-slate-700 h-20"></div>
                    <div class="p-4 space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-16 bg-gray-200 dark:bg-slate-700 rounded-lg"></div>
                            <div class="h-16 bg-gray-200 dark:bg-slate-700 rounded-lg"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="h-10 bg-gray-200 dark:bg-slate-700 rounded-lg"></div>
                            <div class="h-10 bg-gray-200 dark:bg-slate-700 rounded-lg"></div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>

    <!-- Empty State -->
    <div x-show="!devices.length && !loadingDevices" x-cloak class="flex flex-col items-center justify-center flex-1 py-12">
        <div class="inline-flex items-center justify-center p-4 bg-gray-100 dark:bg-slate-800 rounded-full mb-4">
            <svg class="w-12 h-12 text-secondary opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-primary mb-1">Tidak Ada Data Perangkat</h3>
        <p class="text-sm text-secondary">Belum ada device yang terdaftar atau sedang offline</p>
    </div>
</section>
