<div class="h-auto">
    <!-- Skeleton Load -->
    <template x-if="loadingWeather">
        <div class="animate-pulse bg-white/60 rounded-[2.5rem] p-6 shadow-sm border border-white h-auto flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div class="h-4 bg-gray-300 rounded w-1/2"></div>
                <div class="h-8 w-8 bg-gray-300 rounded-full"></div>
            </div>
            <div class="flex justify-between items-center mt-6">
                <div class="h-16 bg-gray-300 rounded w-24"></div>
                <div class="flex flex-col items-end gap-2">
                    <div class="h-4 bg-gray-300 rounded w-16"></div>
                    <div class="h-3 bg-gray-300 rounded w-24"></div>
                </div>
            </div>
            <div class="flex justify-between mt-8">
                <div class="h-8 bg-gray-300 rounded w-1/4"></div>
                <div class="h-8 bg-gray-300 rounded w-1/4"></div>
                <div class="h-8 bg-gray-300 rounded w-1/4"></div>
            </div>
        </div>
    </template>

    <!-- Actual Content -->
    <template x-if="!loadingWeather">
        <div class="bg-white/60 dark:bg-slate-800/50
                    backdrop-blur-xl
                    border border-white/60 dark:border-white/8
                    rounded-[2rem] p-6
                    shadow-lg dark:shadow-black/30
                    text-primary dark:text-slate-100
                    relative overflow-hidden h-auto flex flex-col justify-between
                    transition-all hover:bg-white/70 dark:hover:bg-slate-800/60">

        <!-- Top Section: Location & Icon -->
        <div class="flex justify-between items-start relative z-10">
            <div class="flex items-center gap-2 bg-white/90 dark:bg-slate-700/80 px-3 py-1.5 rounded-full shadow-sm border border-white/80 dark:border-white/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-xs font-semibold text-gray-700 dark:text-slate-200 tracking-wide">Kecamatan Kuningan, ID</span>
            </div>
            <div class="bg-white/90 dark:bg-slate-700/80 p-2 rounded-full shadow-sm border border-white/80 dark:border-white/10">
                <img x-show="weatherSummary && weatherSummary.icon" x-cloak :src="weatherSummary?.icon" alt="Weather" width="24" height="24" class="h-6 w-6 object-contain" />
                <svg x-show="!(weatherSummary && weatherSummary.icon)" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-secondary opacity-80 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                </svg>
            </div>
        </div>

        <!-- Middle Section: Temp & Date -->
        <div class="flex justify-between items-end mt-8 relative z-10">
            <div class="flex items-start flex-shrink-0">
                <span class="text-5xl sm:text-6xl font-extrabold tracking-tighter text-gray-900 dark:text-slate-50" x-text="weatherSummary ? weatherSummary.temp : '-'"></span>
                <span class="text-xl sm:text-2xl font-bold text-secondary opacity-80 dark:text-slate-500 mt-1 sm:mt-2 ml-1">°C</span>
            </div>
            <div class="text-right flex-1 min-w-0 pl-2">
                <div class="text-lg font-bold text-emerald-500 dark:text-emerald-400 truncate" x-text="weatherSummary ? weatherSummary.label : 'Loading...'"></div>
                <div class="text-[10px] sm:text-[11px] text-secondary dark:text-slate-400 mt-1 font-medium truncate" x-text="clock.dateLong + ' | ' + clock.time"></div>
            </div>
        </div>

        <!-- Bottom Section: Stats -->
        <div class="grid grid-cols-3 gap-2 mt-8 border-t border-gray-200/60 dark:border-white/8 pt-5 relative z-10">
            <!-- Kelembapan -->
            <div class="flex flex-col items-start bg-white/50 dark:bg-white/5 p-2 rounded-2xl">
                <div class="flex items-center gap-1.5 mb-1">
                    <div class="p-1 bg-blue-50 dark:bg-blue-900/30 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <span class="text-[9px] sm:text-[10px] font-bold text-secondary dark:text-slate-400 uppercase truncate">Lembap</span>
                </div>
                <div class="text-sm font-bold text-primary dark:text-slate-200 ml-1" x-text="weatherSummary ? (weatherSummary.humidity+'%') : '-'"></div>
            </div>

            <!-- Curah Hujan -->
            <div class="flex flex-col items-start bg-white/50 dark:bg-white/5 p-2 rounded-2xl">
                <div class="flex items-center gap-1.5 mb-1">
                    <div class="p-1 bg-sky-100 dark:bg-sky-900/30 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-sky-600 dark:text-sky-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </div>
                    <span class="text-[9px] sm:text-[10px] font-bold text-secondary dark:text-slate-400 uppercase truncate">Hujan</span>
                </div>
                <div class="text-sm font-bold text-primary dark:text-slate-200 ml-1" x-text="weatherSummary ? (weatherSummary.rain+' mm') : '-'"></div>
            </div>

            <!-- Kecepatan Angin -->
            <div class="flex flex-col items-start bg-white/50 dark:bg-white/5 p-2 rounded-2xl">
                <div class="flex items-center gap-1.5 mb-1">
                    <div class="p-1 bg-gray-100 dark:bg-slate-700 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-secondary dark:text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-[9px] sm:text-[10px] font-bold text-secondary dark:text-slate-400 uppercase truncate">Angin</span>
                </div>
                <div class="text-sm font-bold text-primary dark:text-slate-200 ml-1" x-text="weatherSummary ? (weatherSummary.wind_speed+' km/h') : '-'"></div>
            </div>
        </div>

    </template>
</div>
