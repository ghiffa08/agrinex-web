<section class="flex flex-col gap-4 mt-8">
    <!-- Header -->
    <div class="flex justify-between items-center px-1">
        <h2 class="section-title">Lahan Pantau</h2>
        <a href="#" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-500 dark:hover:text-emerald-400 transition">Lihat Semua</a>
    </div>

    <!-- Filter Chips (Static for now, can be made dynamic later) -->
    <div class="flex overflow-x-auto hide-scrollbar gap-3 pb-2 px-1 -mx-1" x-show="devices.length > 0">
        <button class="whitespace-nowrap px-5 py-2 rounded-full bg-emerald-500 text-white font-medium text-sm shadow-sm">Semua</button>
        <template x-for="device in devices" :key="'filter-'+device.id">
            <button class="whitespace-nowrap px-5 py-2 rounded-full bg-white/60 dark:bg-slate-800/60 backdrop-blur-md text-secondary dark:text-slate-200 font-medium text-sm shadow-sm border border-white/60 dark:border-white/10 hover:bg-white/80 dark:hover:bg-slate-700/60 transition" x-text="device.group ? 'Zona ' + device.group : device.device_name"></button>
        </template>
    </div>

    <!-- 🖥️ DESKTOP UI: Skeleton Loader (Hidden on Mobile) -->
    <template x-if="loadingDevices || loadingAll">
        <div class="hidden md:grid md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 pb-4">
            <template x-for="i in 5">
                <div class="w-full bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden animate-pulse flex flex-col">
                    <div class="p-5 flex flex-col gap-3 h-full">
                        <div class="flex justify-between items-center">
                            <div class="h-5 bg-gray-200 dark:bg-slate-700 rounded w-1/2"></div>
                            <div class="h-5 bg-gray-200 dark:bg-slate-700 rounded-full w-16"></div>
                        </div>
                        <div class="h-3 bg-gray-200 dark:bg-slate-700 rounded w-3/4"></div>
                        <div class="mt-auto h-6 bg-gray-200 dark:bg-slate-700 rounded w-1/3"></div>
                    </div>
                </div>
            </template>
        </div>
    </template>

    <!-- 📱 MOBILE UI: Skeleton Loader (Hidden on Desktop) -->
    <template x-if="loadingDevices || loadingAll">
        <div class="md:hidden flex overflow-x-auto hide-scrollbar gap-4 pb-4 px-1 -mx-1 snap-x w-full">
            <template x-for="i in 3">
                <div class="min-w-[240px] w-[240px] bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden animate-pulse flex flex-col flex-shrink-0 snap-center">
                    <div class="p-5 flex flex-col gap-3 h-full">
                        <div class="flex justify-between items-center">
                            <div class="h-5 bg-gray-200 dark:bg-slate-700 rounded w-1/2"></div>
                            <div class="h-5 bg-gray-200 dark:bg-slate-700 rounded-full w-16"></div>
                        </div>
                        <div class="h-3 bg-gray-200 dark:bg-slate-700 rounded w-3/4"></div>
                        <div class="mt-auto h-6 bg-gray-200 dark:bg-slate-700 rounded w-1/3"></div>
                    </div>
                </div>
            </template>
        </div>
    </template>

    <!-- Empty State -->
    <div x-show="!loadingDevices && !loadingAll && devices.length === 0" style="display: none;" class="w-full bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 shadow-sm rounded-2xl p-8 text-center">
        <div class="text-secondary opacity-80 dark:text-slate-500 mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-primary mb-1">Tidak Ada Data Lahan</h3>
        <p class="text-sm text-secondary">Belum ada node/lahan yang terdaftar di database.</p>
    </div>

    <!-- Lahan Cards -->
    <div x-show="!loadingDevices && !loadingAll && devices.length > 0" style="display: none;" class="flex overflow-x-auto md:grid md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 hide-scrollbar gap-4 pb-4 px-1 -mx-1 md:mx-0 snap-x">
        <template x-for="(device, index) in devices" :key="device.id">
            <div class="min-w-[240px] md:min-w-0 md:w-full w-[240px] bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden snap-center flex-shrink-0 flex flex-col transition-all duration-300 cursor-pointer hover:-translate-y-1" @click="window.location.href = '/nodes/' + device.id">
                <!-- Clean Card Content without Image -->
                <div class="p-5 flex-1 flex flex-col h-full transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-primary text-base" x-text="device.group ? 'Zona ' + device.group : device.device_name"></h3>
                        <div class="bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full" 
                                  :class="{
                                      'bg-emerald-500 animate-pulse': device.status === 'normal' && device.connection_status === 'online',
                                      'bg-yellow-400': device.status === 'warning' || device.connection_status === 'idle',
                                      'bg-gray-400': device.status === 'no_data' || device.connection_status === 'offline'
                                  }"></span>
                            <span class="text-secondary dark:text-gray-300 uppercase tracking-wider" x-text="device.connection_status === 'online' ? 'Online' : (device.connection_status === 'idle' ? 'Idle' : 'Offline')"></span>
                        </div>
                    </div>
                    
                    <p class="text-xs text-secondary leading-relaxed line-clamp-2 mb-4" x-text="device.treatment_description ? 'Perlakuan: ' + device.treatment_description : 'Belum ada perlakuan'"></p>
                    
                    <div class="mt-auto pt-3 flex justify-between items-center border-t border-gray-100 dark:border-slate-700/50">
                        <div class="flex items-center gap-1.5" x-show="device.soil_moisture_pct !== null">
                            <div class="p-1 rounded bg-[#C7E0ED]/50 dark:bg-blue-900/30">
                                <svg class="w-3.5 h-3.5 text-[#1c73a5] dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C12 2 6 9 6 13a6 6 0 0 0 12 0c0-4-6-11-6-11z" /></svg>
                            </div>
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300" x-text="device.soil_moisture_pct + '%'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</section>

<style>
/* Utility to hide scrollbar but keep functionality */
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
