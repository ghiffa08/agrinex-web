<section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Light Intensity Chart -->
    <div class="bg-white border-2 border-gray-300 rounded-2xl p-6 shadow-xl">
        <div class="mb-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-gray-900 text-xl font-bold" x-text="t('lightIntensity')">Light Intensity</h3>
                <span id="lightChartBadge" class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                    Loading...
                </span>
            </div>
            <div class="flex gap-6 text-sm">
                <div class="chart-legend-item flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-cyan-400"></div>
                    <span class="text-gray-700 font-medium">LI2</span>
                </div>
                <div class="chart-legend-item flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-red-500"></div>
                    <span class="text-gray-700 font-medium">LI1</span>
                </div>
            </div>
        </div>
        <div class="relative bg-white border border-gray-200 rounded-lg p-4" style="height: 320px;">
            <div x-show="loadingAll" class="absolute inset-0 bg-white/90 backdrop-blur-sm z-10 p-4 animate-pulse rounded-lg flex items-center justify-center">
                <div class="w-full h-full border-b-2 border-l-2 border-gray-200 flex items-end justify-around p-2 gap-4 opacity-50">
                    <div class="w-full bg-gray-300 rounded-t" style="height: 30%"></div>
                    <div class="w-full bg-gray-300 rounded-t" style="height: 60%"></div>
                    <div class="w-full bg-gray-300 rounded-t" style="height: 40%"></div>
                    <div class="w-full bg-gray-300 rounded-t" style="height: 80%"></div>
                    <div class="w-full bg-gray-300 rounded-t" style="height: 50%"></div>
                </div>
            </div>
            <canvas id="lightIntensityChart"></canvas>
        </div>
        <div class="mt-3 text-center">
            <p class="text-xs text-gray-500">Data 7 hari terakhir, diperbarui otomatis setiap 10 menit</p>
        </div>
    </div>

    <!-- Water Level Chart -->
    <div class="bg-white border-2 border-gray-300 rounded-2xl p-6 shadow-xl">
        <div class="mb-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-gray-900 text-xl font-bold" x-text="t('waterLevel')">Water Level</h3>
                <span id="waterChartBadge" class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">
                    Loading...
                </span>
            </div>
            <div class="flex gap-6 text-sm">
                <div class="chart-legend-item flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-lime-500"></div>
                    <span class="text-gray-700 font-medium">WL</span>
                </div>
            </div>
        </div>
        <div class="relative bg-white border border-gray-200 rounded-lg p-4" style="height: 320px;">
            <div x-show="loadingAll" class="absolute inset-0 bg-white/90 backdrop-blur-sm z-10 p-4 animate-pulse rounded-lg flex items-center justify-center">
                <div class="w-full h-full border-b-2 border-l-2 border-gray-200 flex items-end justify-around p-2 gap-4 opacity-50">
                    <div class="w-full bg-gray-300 rounded-t" style="height: 30%"></div>
                    <div class="w-full bg-gray-300 rounded-t" style="height: 60%"></div>
                    <div class="w-full bg-gray-300 rounded-t" style="height: 40%"></div>
                    <div class="w-full bg-gray-300 rounded-t" style="height: 80%"></div>
                    <div class="w-full bg-gray-300 rounded-t" style="height: 50%"></div>
                </div>
            </div>
            <canvas id="waterLevelChart"></canvas>
        </div>
        <div class="mt-3 text-center">
            <p class="text-xs text-gray-500">Data 7 hari terakhir, diperbarui otomatis setiap 10 menit</p>
        </div>
    </div>
</section>

<!-- Additional Environmental Charts -->
<section class="grid grid-cols-1 gap-6">
    <!-- Soil Moisture Chart -->
    <div class="bg-white border-2 border-gray-300 rounded-2xl p-6 shadow-xl">
        <div class="mb-4">
            <div class="flex items-center justify-between">
                <h3 class="text-gray-900 text-xl font-bold" x-text="t('soilMoisture')">Soil Moisture</h3>
                <span id="soilChartBadge" class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-700">
                    Loading...
                </span>
            </div>
            <div class="flex flex-wrap gap-3 text-xs">
                <template x-for="(sensor, idx) in soilMoistureSensors" :key="sensor.id">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded" :style="'background-color: ' + sensor.color"></div>
                        <span class="text-gray-700 font-medium" x-text="sensor.label"></span>
                    </div>
                </template>
            </div>
        </div>
        <div class="relative bg-white border border-gray-200 rounded-lg p-4" style="height: 320px;">
            <div x-show="loadingAll" class="absolute inset-0 bg-white/90 backdrop-blur-sm z-10 p-4 animate-pulse rounded-lg flex items-center justify-center">
                <div class="w-full h-full border-b-2 border-l-2 border-gray-200 flex items-end justify-around p-2 gap-4 opacity-50">
                    <div class="w-full bg-gray-300 rounded-t" style="height: 30%"></div>
                    <div class="w-full bg-gray-300 rounded-t" style="height: 60%"></div>
                    <div class="w-full bg-gray-300 rounded-t" style="height: 40%"></div>
                    <div class="w-full bg-gray-300 rounded-t" style="height: 80%"></div>
                    <div class="w-full bg-gray-300 rounded-t" style="height: 50%"></div>
                </div>
            </div>
            <canvas id="soilMoistureChart"></canvas>
        </div>
        <div class="mt-3 text-center">
            <p class="text-xs text-gray-500">Kelembapan tanah dari berbagai sensor (Data 7 hari terakhir)</p>
        </div>
    </div>

    <!-- Temperature and Humidity Charts (Side by Side) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Temperature Chart -->
        <div class="bg-white border-2 border-gray-300 rounded-2xl p-6 shadow-xl">
            <div class="mb-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-gray-900 text-xl font-bold" x-text="t('temperature')">Temperature</h3>
                    <span id="tempChartBadge" class="text-xs px-2 py-1 rounded-full bg-purple-100 text-purple-700">
                        Loading...
                    </span>
                </div>
                <div class="flex gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-purple-500"></div>
                        <span class="text-gray-700 font-medium">T1</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-cyan-500"></div>
                        <span class="text-gray-700 font-medium">T2</span>
                    </div>
                </div>
            </div>
            <div class="relative bg-white border border-gray-200 rounded-lg p-4" style="height: 280px;">
                <div x-show="loadingAll" class="absolute inset-0 bg-white/90 backdrop-blur-sm z-10 p-4 animate-pulse rounded-lg flex items-center justify-center">
                    <div class="w-full h-full border-b-2 border-l-2 border-gray-200 flex items-end justify-around p-2 gap-4 opacity-50">
                        <div class="w-full bg-gray-300 rounded-t" style="height: 30%"></div>
                        <div class="w-full bg-gray-300 rounded-t" style="height: 60%"></div>
                        <div class="w-full bg-gray-300 rounded-t" style="height: 40%"></div>
                        <div class="w-full bg-gray-300 rounded-t" style="height: 80%"></div>
                        <div class="w-full bg-gray-300 rounded-t" style="height: 50%"></div>
                    </div>
                </div>
                <canvas id="temperatureChart"></canvas>
            </div>
            <div class="mt-3 text-center">
                <p class="text-xs text-gray-500">Suhu dari sensor (Data 7 hari terakhir)</p>
            </div>
        </div>

        <!-- Humidity Chart -->
        <div class="bg-white border-2 border-gray-300 rounded-2xl p-6 shadow-xl">
            <div class="mb-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-gray-900 text-xl font-bold" x-text="t('humidity')">Humidity</h3>
                    <span id="humidityChartBadge" class="text-xs px-2 py-1 rounded-full bg-cyan-100 text-cyan-700">
                        Loading...
                    </span>
                </div>
                <div class="flex gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-blue-500"></div>
                        <span class="text-gray-700 font-medium">H2</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-orange-500"></div>
                        <span class="text-gray-700 font-medium">H1</span>
                    </div>
                </div>
            </div>
            <div class="relative bg-white border border-gray-200 rounded-lg p-4" style="height: 280px;">
                <div x-show="loadingAll" class="absolute inset-0 bg-white/90 backdrop-blur-sm z-10 p-4 animate-pulse rounded-lg flex items-center justify-center">
                    <div class="w-full h-full border-b-2 border-l-2 border-gray-200 flex items-end justify-around p-2 gap-4 opacity-50">
                        <div class="w-full bg-gray-300 rounded-t" style="height: 30%"></div>
                        <div class="w-full bg-gray-300 rounded-t" style="height: 60%"></div>
                        <div class="w-full bg-gray-300 rounded-t" style="height: 40%"></div>
                        <div class="w-full bg-gray-300 rounded-t" style="height: 80%"></div>
                        <div class="w-full bg-gray-300 rounded-t" style="height: 50%"></div>
                    </div>
                </div>
                <canvas id="humidityChart"></canvas>
            </div>
            <div class="mt-3 text-center">
                <p class="text-xs text-gray-500">Kelembapan dari sensor (Data 7 hari terakhir)</p>
            </div>
        </div>
    </div>
</section>
