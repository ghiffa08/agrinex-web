<section class="flex flex-col gap-4 mt-8">
    <!-- Header -->
    <div class="flex justify-between items-center px-1">
        <h2 class="text-xl font-bold text-gray-800 tracking-tight">Lahan Pantau</h2>
        <a href="#" class="text-sm font-semibold text-green-600 hover:text-green-700 transition">Lihat Semua</a>
    </div>

    <!-- Filter Chips (Static for now, can be made dynamic later) -->
    <div class="flex overflow-x-auto hide-scrollbar gap-3 pb-2 px-1 -mx-1" x-show="devices.length > 0">
        <button class="whitespace-nowrap px-5 py-2 rounded-full bg-[#7cbd4a] text-white font-medium text-sm shadow-sm">Semua</button>
        <template x-for="device in devices" :key="'filter-'+device.id">
            <button class="whitespace-nowrap px-5 py-2 rounded-full bg-white text-gray-600 font-medium text-sm shadow-sm border border-gray-100 hover:bg-gray-50 transition" x-text="device.group ? 'Zona ' + device.group : device.device_name"></button>
        </template>
    </div>

    <!-- 🖥️ DESKTOP UI: Skeleton Loader (Hidden on Mobile) -->
    <div x-show="loadingDevices || loadingAll" class="hidden md:grid md:grid-cols-3 lg:grid-cols-4 gap-4 pb-4">
        <template x-for="i in 4">
            <div class="w-full bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 animate-pulse flex flex-col">
                <div class="h-36 bg-gray-200"></div>
                <div class="p-4 flex flex-col gap-2">
                    <div class="h-5 bg-gray-200 rounded w-2/3"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                </div>
            </div>
        </template>
    </div>

    <!-- 📱 MOBILE UI: Skeleton Loader (Hidden on Desktop) -->
    <div x-show="loadingDevices || loadingAll" class="md:hidden flex overflow-x-auto hide-scrollbar gap-4 pb-4 px-1 -mx-1 snap-x w-full">
        <template x-for="i in 3">
            <div class="min-w-[240px] w-[240px] bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 animate-pulse flex flex-col flex-shrink-0 snap-center">
                <div class="h-36 bg-gray-200"></div>
                <div class="p-4 flex flex-col gap-2">
                    <div class="h-5 bg-gray-200 rounded w-2/3"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="!loadingDevices && !loadingAll && devices.length === 0" style="display: none;" class="w-full bg-white rounded-2xl border border-gray-100 p-8 text-center shadow-sm">
        <div class="text-gray-400 mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800 mb-1">Tidak Ada Data Lahan</h3>
        <p class="text-sm text-gray-500">Belum ada node/lahan yang terdaftar di database.</p>
    </div>

    <!-- Lahan Cards -->
    <div x-show="!loadingDevices && !loadingAll && devices.length > 0" style="display: none;" class="flex overflow-x-auto md:grid md:grid-cols-3 lg:grid-cols-4 hide-scrollbar gap-4 pb-4 px-1 -mx-1 md:mx-0 snap-x">
        <template x-for="(device, index) in devices" :key="device.id">
            <div class="min-w-[240px] md:min-w-0 md:w-full w-[240px] bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 snap-center flex-shrink-0 flex flex-col hover:shadow-md transition cursor-pointer" @click="selectedDevice = device; showDeviceModal = true">
                <!-- Image Area -->
                <div class="h-36 relative bg-gray-100">
                    <!-- Gunakan placeholder image atau gradient jika tidak ada foto dari database -->
                    <div class="w-full h-full bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                        <svg class="w-12 h-12 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="absolute top-3 left-3 bg-black/40 backdrop-blur-md text-white text-xs font-medium px-3 py-1.5 rounded-full flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full shadow-[0_0_5px]" 
                              :class="{
                                  'bg-[#7cbd4a] shadow-[#7cbd4a]': device.status === 'normal' && device.connection_status === 'online',
                                  'bg-yellow-400 shadow-yellow-400': device.status === 'warning' || device.connection_status === 'idle',
                                  'bg-red-500 shadow-red-500': device.status === 'no_data' || device.connection_status === 'offline'
                              }"></span>
                        <span x-text="device.connection_status === 'online' ? 'Aktif' : (device.connection_status === 'idle' ? 'Idle' : 'Offline')"></span>
                    </div>
                </div>
                <!-- Info Area -->
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 text-base mb-1" x-text="device.group ? 'Zona ' + device.group : device.device_name"></h3>
                    <p class="text-xs text-gray-500 font-medium truncate" x-text="'Perlakuan: ' + (device.treatment_description || '-')"></p>
                    <div class="mt-3 flex justify-between items-center">
                        <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded" x-show="device.soil_moisture_pct !== null" x-text="'Moisture: ' + device.soil_moisture_pct + '%'"></span>
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
