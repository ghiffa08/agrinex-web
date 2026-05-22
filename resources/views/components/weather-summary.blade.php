<div>
    <!-- Skeleton Load -->
    <div x-show="loadingWeather" class="animate-pulse bg-[#43722a] rounded-[2rem] p-6 shadow-lg h-56 flex flex-col justify-between">
        <div class="flex justify-between items-start">
            <div class="h-4 bg-white/20 rounded w-1/2"></div>
            <div class="h-8 w-8 bg-white/20 rounded-full"></div>
        </div>
        <div class="flex justify-between items-center mt-6">
            <div class="h-16 bg-white/20 rounded w-24"></div>
            <div class="flex flex-col items-end gap-2">
                <div class="h-4 bg-white/20 rounded w-16"></div>
                <div class="h-3 bg-white/20 rounded w-24"></div>
            </div>
        </div>
        <div class="flex justify-between mt-8">
            <div class="h-8 bg-white/20 rounded w-1/4"></div>
            <div class="h-8 bg-white/20 rounded w-1/4"></div>
            <div class="h-8 bg-white/20 rounded w-1/4"></div>
        </div>
    </div>

    <!-- Actual Content -->
    <div x-show="!loadingWeather" style="display: none;" class="bg-gradient-to-br from-[#3c6b24] to-[#4f8330] rounded-[2rem] p-6 shadow-xl text-white relative overflow-hidden">
        
        <!-- Decorative subtle background curves -->
        <div class="absolute top-[-20%] right-[-10%] w-48 h-48 bg-white opacity-5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-[-20%] left-[-10%] w-32 h-32 bg-white opacity-5 rounded-full blur-xl"></div>

        <!-- Top Section: Location & Icon -->
        <div class="flex justify-between items-start relative z-10">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#a3d18c]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-sm font-medium tracking-wide">Kecamatan Kuningan, ID</span>
            </div>
            <div>
                <!-- Cloud Icon or Dynamic Icon -->
                <template x-if="weatherSummary && weatherSummary.icon">
                    <img :src="weatherSummary.icon" alt="Weather" class="h-8 w-8 filter brightness-0 invert opacity-90" />
                </template>
                <template x-if="!(weatherSummary && weatherSummary.icon)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                    </svg>
                </template>
            </div>
        </div>

        <!-- Middle Section: Temp & Date -->
        <div class="flex justify-between items-end mt-6 relative z-10">
            <div class="flex items-start">
                <span class="text-6xl font-bold tracking-tighter" x-text="weatherSummary ? weatherSummary.temp : '-'"></span>
                <span class="text-2xl font-medium mt-2 ml-1">°C</span>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold" x-text="weatherSummary ? weatherSummary.label : 'Loading...'"></div>
                <div class="text-[11px] text-[#bce0a8] mt-1 tracking-wider" x-text="clock.dateLong + ' | ' + clock.time"></div>
            </div>
        </div>

        <!-- Bottom Section: Stats -->
        <div class="grid grid-cols-3 gap-1 sm:gap-2 mt-8 border-t border-white/20 pt-4 relative z-10">
            <!-- Kelembapan -->
            <div class="flex flex-col">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-1 sm:gap-1.5 text-[#bce0a8] mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-[8px] sm:text-[10px] font-medium uppercase tracking-tight sm:tracking-wider leading-tight break-words">Kelembapan</span>
                </div>
                <div class="text-xs sm:text-sm font-bold" x-text="weatherSummary ? (weatherSummary.humidity+'%') : '-'"></div>
            </div>

            <!-- Curah Hujan -->
            <div class="flex flex-col border-l border-white/20 pl-2 sm:pl-3">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-1 sm:gap-1.5 text-[#bce0a8] mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    <span class="text-[8px] sm:text-[10px] font-medium uppercase tracking-tight sm:tracking-wider leading-tight break-words">Curah Hujan</span>
                </div>
                <div class="text-xs sm:text-sm font-bold" x-text="weatherSummary ? (weatherSummary.rain+' mm') : '-'"></div>
            </div>

            <!-- Kecepatan Angin -->
            <div class="flex flex-col border-l border-white/20 pl-2 sm:pl-3">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-1 sm:gap-1.5 text-[#bce0a8] mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-[8px] sm:text-[10px] font-medium uppercase tracking-tight sm:tracking-wider leading-tight break-words">Kecepatan Angin</span>
                </div>
                <div class="text-xs sm:text-sm font-bold" x-text="weatherSummary ? (weatherSummary.wind_speed+' km/h') : '-'"></div>
            </div>
        </div>

    </div>
</div>
