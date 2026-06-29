{{-- Device Detail Modal --}}
<div x-cloak x-show="showDeviceModal"
    class="fixed inset-0 z-[999] flex items-center justify-center p-4"
    @keydown.escape.window="closeDeviceModal()" style="display: none;">
    
    {{-- Backdrop --}}
    <div @click="closeDeviceModal()" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
    
    {{-- Modal Content --}}
    <div x-show="showDeviceModal" x-transition
        class="relative w-full max-w-3xl rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-950 border border-gray-100 dark:border-gray-850 flex flex-col max-h-[90vh]">
        
        {{-- Modal Header --}}
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-5">
            <div>
                <h3 class="text-lg font-semibold text-gray-850 dark:text-white" x-text="selectedDevice?.device_name || 'Device'"></h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="selectedDevice ? ('ID: '+selectedDevice.device_id) : ''"></p>
            </div>
            <button type="button" @click="closeDeviceModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-250">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="flex-grow overflow-y-auto space-y-6 pr-1 custom-scrollbar">
            
            <!-- Quick stats -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                <div class="rounded-xl border border-gray-150 p-4 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-900/30">
                    <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Suhu</span>
                    <h4 class="mt-2 text-xl font-bold text-gray-800 dark:text-white" x-text="fmt(selectedDevice?.temperature_c,'°C')"></h4>
                </div>
                <div class="rounded-xl border border-gray-150 p-4 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-900/30">
                    <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanah</span>
                    <h4 class="mt-2 text-xl font-bold text-gray-800 dark:text-white" x-text="fmt(selectedDevice?.soil_moisture_pct,'%')"></h4>
                </div>
                <div class="rounded-xl border border-gray-150 p-4 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-900/30">
                    <span class="text-theme-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Baterai</span>
                    <h4 class="mt-2 text-xl font-bold text-gray-800 dark:text-white" x-text="batteryDisplay(selectedDevice)"></h4>
                </div>
            </div>

            <!-- Sessions table -->
            <div>
                <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white/90 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a6.002 6.002 0 0 0 3.6-10.8c-.8-.8-2.6-2.9-3.6-4.2-1 1.3-2.8 3.4-3.6 4.2A6.002 6.002 0 0 0 12 21Z" />
                        </svg> 
                        Penggunaan Air per Sesi
                        <template x-if="loadingDeviceDetail">
                            <span class="text-xs text-gray-400 font-normal ml-1">(loading...)</span>
                        </template>
                    </h4>
                    <template x-if="deviceSessionsSummary">
                        <div class="flex flex-wrap items-center gap-2 text-theme-xs font-medium text-gray-500 dark:text-gray-400">
                            <span class="rounded bg-gray-100 dark:bg-gray-800 px-2 py-0.5" x-text="'Rencana: ' + fmt(deviceSessionsSummary.total_planned_l,' L')"></span>
                            <span class="rounded bg-gray-100 dark:bg-gray-800 px-2 py-0.5" x-text="'Aktual: ' + fmt(deviceSessionsSummary.total_actual_l,' L')"></span>
                            <span class="rounded bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-450 px-2 py-0.5" 
                                  x-text="'Efisiensi: ' + (deviceSessionsSummary.efficiency_pct!=null? deviceSessionsSummary.efficiency_pct+'%':'-')"></span>
                        </div>
                    </template>
                </div>
                
                <template x-if="!loadingDeviceDetail && !deviceSessions.length">
                    <p class="text-xs text-gray-400 py-4 text-center">Belum ada data sesi untuk device ini.</p>
                </template>
                <template x-if="deviceSessions.length">
                    <div class="overflow-hidden border border-gray-150 dark:border-gray-800 rounded-xl">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-150 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                                    <th class="py-2.5 px-4 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Sesi</th>
                                    <th class="py-2.5 px-4 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                                    <th class="py-2.5 px-4 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Rencana (L)</th>
                                    <th class="py-2.5 px-4 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Aktual (L)</th>
                                    <th class="py-2.5 px-4 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Efisiensi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                <template x-for="s in deviceSessions" :key="s.id || s.index">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                                        <td class="py-2.5 px-4 text-theme-sm font-semibold text-gray-800 dark:text-white" x-text="s.index || s.session || '-' "></td>
                                        <td class="py-2.5 px-4 text-theme-sm text-gray-500 dark:text-gray-400" x-text="s.time || s.start_time || '-' "></td>
                                        <td class="py-2.5 px-4 text-right text-theme-sm text-gray-700 dark:text-gray-300"
                                            x-text="s.planned_l ? s.planned_l.toFixed(1) : (s.planned_volume_l?.toFixed(1) || '-')">
                                        </td>
                                        <td class="py-2.5 px-4 text-right text-theme-sm text-gray-700 dark:text-gray-300"
                                            x-text="s.actual_l ? s.actual_l.toFixed(1) : (s.actual_volume_l?.toFixed(1) || '-')">
                                        </td>
                                        <td class="py-2.5 px-4 text-right text-theme-sm font-bold">
                                            <span class="rounded-full px-2 py-0.5 text-[10px]"
                                                  :class="(s.actual_l / (s.planned_l||1)) >= 0.95 ? 'bg-success-50 text-success-600 dark:bg-success-500/15' : 'bg-warning-50 text-warning-600 dark:bg-warning-500/15'"
                                                  x-text="(s.actual_l && s.planned_l) ? ((s.actual_l / (s.planned_l||1))*100).toFixed(0)+'%' : (s.efficiency_pct ? s.efficiency_pct+'%' : '-')"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </template>
            </div>

            <!-- Usage history table -->
            <div>
                <h4 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-warning-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg> 
                    Riwayat Penggunaan Air
                    <template x-if="loadingDeviceDetail">
                        <span class="text-xs text-gray-400 font-normal ml-1">(loading...)</span>
                    </template>
                </h4>
                
                <template x-if="!loadingDeviceDetail && !deviceUsageHistory.length">
                    <p class="text-xs text-gray-400 py-4 text-center">Belum ada data penggunaan sebelumnya.</p>
                </template>
                <template x-if="deviceUsageHistory.length">
                    <div class="overflow-hidden border border-gray-150 dark:border-gray-800 rounded-xl">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-150 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                                    <th class="py-2.5 px-4 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                    <th class="py-2.5 px-4 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Total (L)</th>
                                    <th class="py-2.5 px-4 text-right font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Sesi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                <template x-for="h in deviceUsageHistory" :key="h.date || h.id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                                        <td class="py-2.5 px-4 text-theme-sm text-gray-500 dark:text-gray-400" x-text="h.date || h.day || '-' "></td>
                                        <td class="py-2.5 px-4 text-right text-theme-sm text-gray-800 dark:text-white font-semibold"
                                            x-text="h.total_l ? h.total_l.toFixed(1) : (h.volume_l?.toFixed(1) || '-')">
                                        </td>
                                        <td class="py-2.5 px-4 text-right text-theme-sm text-gray-700 dark:text-gray-300 font-semibold"
                                            x-text="h.sessions || h.session_count || '-' "></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </template>
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800 mt-5">
            <button @click="closeDeviceModal()" class="rounded-lg border border-gray-300 bg-white px-5 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-850 dark:text-gray-400 transition-colors">Tutup</button>
        </div>
    </div>
</div>
