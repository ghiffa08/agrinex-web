<section class="flex flex-col gap-4 mt-8">
    <!-- Header -->
    <div class="flex justify-between items-center px-1">
        <h2 class="text-xl font-bold text-gray-800 tracking-tight">Lahan Pantau</h2>
        <a href="#" class="text-sm font-semibold text-green-600 hover:text-green-700 transition">Lihat Semua</a>
    </div>

    <!-- Filter Chips -->
    <div class="flex overflow-x-auto hide-scrollbar gap-3 pb-2 px-1 -mx-1">
        <button class="whitespace-nowrap px-5 py-2 rounded-full bg-[#7cbd4a] text-white font-medium text-sm shadow-sm">Semua</button>
        <button class="whitespace-nowrap px-5 py-2 rounded-full bg-white text-gray-600 font-medium text-sm shadow-sm border border-gray-100 hover:bg-gray-50 transition">Zona A (Kopi)</button>
        <button class="whitespace-nowrap px-5 py-2 rounded-full bg-white text-gray-600 font-medium text-sm shadow-sm border border-gray-100 hover:bg-gray-50 transition">Zona B</button>
        <button class="whitespace-nowrap px-5 py-2 rounded-full bg-white text-gray-600 font-medium text-sm shadow-sm border border-gray-100 hover:bg-gray-50 transition">Zona C</button>
    </div>

    <!-- 🖥️ DESKTOP UI: Skeleton Loader (Hidden on Mobile) -->
    <div x-show="loadingAll" class="hidden md:grid md:grid-cols-3 lg:grid-cols-4 gap-4 pb-4">
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
    <div x-show="loadingAll" class="md:hidden flex overflow-x-auto hide-scrollbar gap-4 pb-4 px-1 -mx-1 snap-x w-full">
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

    <!-- Lahan Cards -->
    <div x-show="!loadingAll" style="display: none;" class="flex overflow-x-auto md:grid md:grid-cols-3 lg:grid-cols-4 hide-scrollbar gap-4 pb-4 px-1 -mx-1 md:mx-0 snap-x">
        <!-- Card 1 -->
        <div class="min-w-[240px] md:min-w-0 md:w-full w-[240px] bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 snap-center flex-shrink-0 flex flex-col">
            <!-- Image Area -->
            <div class="h-36 relative">
                <img src="https://images.unsplash.com/photo-1495908333425-29a1e0918c5f?q=80&w=600&auto=format&fit=crop" alt="Lahan Kopi" class="w-full h-full object-cover">
                <!-- Status Badge -->
                <div class="absolute top-3 left-3 bg-black/40 backdrop-blur-md text-white text-xs font-medium px-3 py-1.5 rounded-full flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#7cbd4a] shadow-[0_0_5px_#7cbd4a]"></span>
                    Aktif
                </div>
            </div>
            <!-- Info Area -->
            <div class="p-4">
                <h3 class="font-bold text-gray-800 text-base mb-1">Zona A - Kopi</h3>
                <p class="text-xs text-gray-500 font-medium">Tertanam: 120 Pohon</p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="min-w-[240px] md:min-w-0 md:w-full w-[240px] bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 snap-center flex-shrink-0 flex flex-col">
            <!-- Image Area -->
            <div class="h-36 relative">
                <img src="https://images.unsplash.com/photo-1599940824399-b87987ceb72a?q=80&w=600&auto=format&fit=crop" alt="Greenhouse" class="w-full h-full object-cover">
                <!-- Status Badge -->
                <div class="absolute top-3 left-3 bg-black/40 backdrop-blur-md text-white text-xs font-medium px-3 py-1.5 rounded-full flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-yellow-400 shadow-[0_0_5px_yellow]"></span>
                    Terjadwal
                </div>
            </div>
            <!-- Info Area -->
            <div class="p-4">
                <h3 class="font-bold text-gray-800 text-base mb-1">Zona B - Sayuran</h3>
                <p class="text-xs text-gray-500 font-medium">Tertanam: Hydroponic</p>
            </div>
        </div>
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
