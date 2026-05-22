{{-- Devices & Water Tank Section --}}
<section class="space-y-6">
    <!-- Header with total count -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div>
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Perangkat
                    <span class="text-sm font-medium px-3 py-1 bg-green-100 text-green-700 rounded-full" x-text="devices.length + ' Node'">12 Node</span>
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Monitoring real-time sensor nodes</p>
            </div>
        </div>
        <button @click="loadAll()" 
            class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span x-text="t('refresh')">Refresh</span>
        </button>
    </div> <!-- restored closing div for header flex -->

    <!-- Devices Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" x-show="devices.length && !loadingDevices">
        <template x-for="d in devices" :key="d.device_id">
            <div class="bg-white rounded-xl border-2 border-gray-200 hover:border-green-400 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group cursor-pointer"
                @click="openDeviceModal(d)">
                
                <!-- Header with Node Name and Status -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="font-bold text-gray-900 text-base" x-text="`Node ${d.plot_number}`">Node 1</h3>
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full animate-pulse" 
                                :class="d.connection_state === 'online' ? 'bg-green-500' : 'bg-gray-400'"></div>
                            <span class="text-xs font-semibold" 
                                :class="d.connection_state === 'online' ? 'text-green-600' : 'text-gray-500'"
                                x-text="d.connection_state === 'online' ? 'Online' : 'Offline'">Online</span>
                        </div>
                    </div>
                    <!-- Treatment Description -->
                    <div class="text-xs text-gray-600 leading-tight" x-text="d.treatment_description || 'Irigasi saat kelembaban ≤ 80% FC (678 ADC) - Perlakuan optimal'">
                        Irigasi saat kelembaban ≤ 80% FC (678 ADC) - Perlakuan optimal
                    </div>
                </div>

                <!-- Main Content -->
                <div class="p-4 space-y-3">
                    <!-- Kelembaban & Suhu (Big Display) -->
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Kelembaban -->
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-blue-50 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C12 2 6 9 6 13a6 6 0 0 0 12 0c0-4-6-11-6-11z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Kelembaban</div>
                                <div class="text-2xl font-bold text-blue-600" x-text="d.soil_moisture_pct ? Math.round(d.soil_moisture_pct) + '%' : '-'">51%</div>
                            </div>
                        </div>

                        <!-- Suhu (using air_temp_c now) -->
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-orange-50 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Suhu</div>
                                <div class="text-2xl font-bold text-orange-600" x-text="d.air_temp_c ? Math.round(d.air_temp_c) + '°' : (d.soil_temp_c ? Math.round(d.soil_temp_c) + '°' : '-')">29°</div>
                            </div>
                        </div>
                    </div>

                    <!-- Treatment Details -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200">
                        <div class="text-xs font-semibold text-gray-700 mb-2">Treatment Details:</div>
                        <div class="space-y-1.5">
                            <!-- FC Target -->
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">FC Target:</span>
                                <span class="text-sm font-bold text-gray-900" x-text="d.fc_target ? d.fc_target.toFixed(2) + '%' : '-'">80.00%</span>
                            </div>
                            <!-- Threshold -->
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">Threshold:</span>
                                <span class="text-sm font-bold text-gray-900" x-text="d.threshold ? d.threshold.toFixed(2) + '%' : '-'">17.70%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Update Time -->
                    <div class="text-xs text-gray-400 text-center pt-1 border-t border-gray-100">
                        <span x-text="'Update: ' + timeAgo(d.recorded_at || d.last_seen)">Update: 7 hours ago</span>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- 🖥️ DESKTOP UI: Loading State (Hidden on Mobile) -->
    <div x-show="loadingDevices" class="hidden md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <template x-for="i in 8" :key="'desktop-'+i">
            <div class="bg-white rounded-xl border-2 border-gray-200 shadow-sm overflow-hidden animate-pulse">
                <div class="bg-gray-200 h-20"></div>
                <div class="p-4 space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="h-16 bg-gray-200 rounded-lg"></div>
                        <div class="h-16 bg-gray-200 rounded-lg"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="h-10 bg-gray-200 rounded-lg"></div>
                        <div class="h-10 bg-gray-200 rounded-lg"></div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- 📱 MOBILE UI: Loading State (Hidden on Desktop) -->
    <div x-show="loadingDevices" class="grid md:hidden grid-cols-1 gap-4">
        <template x-for="i in 3" :key="'mobile-'+i">
            <div class="bg-white rounded-xl border-2 border-gray-200 shadow-sm overflow-hidden animate-pulse">
                <div class="bg-gray-200 h-20"></div>
                <div class="p-4 space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="h-16 bg-gray-200 rounded-lg"></div>
                        <div class="h-16 bg-gray-200 rounded-lg"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="h-10 bg-gray-200 rounded-lg"></div>
                        <div class="h-10 bg-gray-200 rounded-lg"></div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="!devices.length && !loadingDevices" class="text-center py-12">
        <div class="inline-block p-4 bg-gray-100 rounded-full mb-4">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-1">Tidak Ada Data Perangkat</h3>
        <p class="text-sm text-gray-500">Belum ada device yang terdaftar atau sedang offline</p>
    </div>
</section>
