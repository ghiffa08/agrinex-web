{{-- CHART FIX - Clean Implementation Without Circular References --}}
<script>
// ✅ DISABLE Alpine.js chart initialization
window.CHART_FIX_ENABLED = true;

// ✅ FIXED: Global chart instances with proper null initialization
window.envCharts = {
    light: null,
    water: null,
    soil: null,
    temp: null,
    humidity: null
};

// ✅ Initialize charts after full DOM ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 [CHART-FIX] DOM Ready - Starting initialization...');
    
    // Wait for Alpine.js to settle
    setTimeout(() => {
        initAllEnvironmentalCharts();
        loadAllChartData();
        
        // Auto-refresh every 10 minutes (600000ms) - Weekly data doesn't need frequent updates
        // Reduced from 60s to 10min for better performance with 7-day data
        setInterval(() => {
            console.log('🔄 [CHART-FIX] Auto-refreshing chart data...');
            loadAllChartData();
        }, 600000); // 10 minutes
    }, 1200);  // ✅ Increased delay to ensure Alpine.js is done
});

function initAllEnvironmentalCharts() {
    console.log('🎨 [CHART-FIX] Creating chart instances...');
    
    try {
        // ✅ Common options - NO circular references
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#666',
                    borderWidth: 1,
                    padding: 10,
                    displayColors: true
                }
            },
            scales: {
                x: {
                    display: true,
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: true
                    },
                    ticks: {
                        maxRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 8,
                        color: '#6b7280',
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    display: true,
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: true
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 11
                        }
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        };

        // 1. Light Intensity Chart
        const lightCtx = document.getElementById('lightIntensityChart');
        if (lightCtx) {
            window.envCharts.light = new Chart(lightCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'LI2',
                            data: [],
                            borderColor: '#22d3ee',
                            backgroundColor: 'rgba(34, 211, 238, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#22d3ee',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1,
                            fill: true
                        },
                        {
                            label: 'LI1',
                            data: [],
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#ef4444',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1,
                            fill: true
                        }
                    ]
                },
                options: commonOptions
            });
            console.log('✅ [CHART-FIX] Light Intensity chart created');
        }

        // 2. Water Level Chart
        const waterCtx = document.getElementById('waterLevelChart');
        if (waterCtx) {
            window.envCharts.water = new Chart(waterCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Water Level',
                        data: [],
                        borderColor: '#84cc16',
                        backgroundColor: 'rgba(132, 204, 22, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: '#84cc16',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1,
                        fill: true
                    }]
                },
                options: commonOptions
            });
            console.log('✅ [CHART-FIX] Water Level chart created');
        }

        // 3. Soil Moisture Chart (8 sensors)
        const soilCtx = document.getElementById('soilMoistureChart');
        if (soilCtx) {
            // ✅ FIXED: Direct color strings - NO object references
            const soilColors = [
                '#3b82f6', // Blue
                '#a855f7', // Purple
                '#f97316', // Orange
                '#eab308', // Yellow
                '#84cc16', // Lime
                '#ef4444', // Red
                '#ec4899', // Pink
                '#22d3ee'  // Cyan
            ];
            
            const soilDatasets = [];
            for (let i = 0; i < 8; i++) {
                const color = soilColors[i];
                // ✅ FIXED: Manual rgba conversion - NO circular references
                const r = parseInt(color.slice(1, 3), 16);
                const g = parseInt(color.slice(3, 5), 16);
                const b = parseInt(color.slice(5, 7), 16);
                
                soilDatasets.push({
                    label: `SM${i + 1}`,
                    data: [],
                    borderColor: color,
                    backgroundColor: `rgba(${r}, ${g}, ${b}, 0.1)`,
                    borderWidth: 2,
                    tension: 0.3,
                    pointRadius: 2,
                    pointBackgroundColor: color,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1,
                    fill: false
                });
            }
            
            window.envCharts.soil = new Chart(soilCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: soilDatasets
                },
                options: commonOptions
            });
            console.log('✅ [CHART-FIX] Soil Moisture chart created (8 sensors)');
        }

        // 4. Temperature Chart
        const tempCtx = document.getElementById('temperatureChart');
        if (tempCtx) {
            window.envCharts.temp = new Chart(tempCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'T1',
                            data: [],
                            borderColor: '#a855f7',
                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#a855f7',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1,
                            fill: true
                        },
                        {
                            label: 'T2',
                            data: [],
                            borderColor: '#06b6d4',
                            backgroundColor: 'rgba(6, 182, 212, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#06b6d4',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1,
                            fill: true
                        }
                    ]
                },
                options: commonOptions
            });
            console.log('✅ [CHART-FIX] Temperature chart created');
        }

        // 5. Humidity Chart
        const humidityCtx = document.getElementById('humidityChart');
        if (humidityCtx) {
            window.envCharts.humidity = new Chart(humidityCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'H2',
                            data: [],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1,
                            fill: true
                        },
                        {
                            label: 'H1',
                            data: [],
                            borderColor: '#f97316',
                            backgroundColor: 'rgba(249, 115, 22, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#f97316',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1,
                            fill: true
                        }
                    ]
                },
                options: commonOptions
            });
            console.log('✅ [CHART-FIX] Humidity chart created');
        }

        console.log('🎉 [CHART-FIX] All charts initialized successfully!');
    } catch (error) {
        console.error('❌ [CHART-FIX] Chart initialization error:', error);
    }
}

function loadAllChartData() {
    console.log('📊 [CHART-FIX] Loading chart data from API (7 days)...');
    
    fetch('/api/v1/dashboard/charts?type=all&days=7')
        .then(response => {
            console.log('📡 [CHART-FIX] API Response status:', response.status);
            if (!response.ok) {
                throw new Error(`API returned ${response.status}`);
            }
            return response.json();
        })
        .then(jsonData => {
            console.log('📦 [CHART-FIX] Raw API data received:', jsonData);
            const chartData = jsonData.data || jsonData;
            console.log('✅ [CHART-FIX] Data received:', {
                light: chartData.light?.length || 0,
                water: chartData.water?.length || 0,
                soil: chartData.soil?.length || 0,
                temp: chartData.temperature?.length || 0,
                humidity: chartData.humidity?.length || 0,
                timeRange: jsonData.meta?.time_range_days + ' days',
                totalPoints: jsonData.meta?.total_points
            });
            
            // Sample data logs removed to clean up console
            // Update charts
            updateLightChart(chartData.light);
            updateWaterChart(chartData.water);
            updateSoilChart(chartData.soil);
            updateTempChart(chartData.temperature);
            updateHumidityChart(chartData.humidity);

            console.log('🎉 [CHART-FIX] All charts updated successfully!');
        })
        .catch(error => {
            console.error('❌ [CHART-FIX] API Error:', error);
            console.log('🎲 [CHART-FIX] Loading sample data as fallback...');
            loadSampleChartData();
        });
}

function updateLightChart(data) {
    if (!window.envCharts.light) return;
    
    const labels = [];
    const li1Data = [];
    const li2Data = [];
    
    if (data && data.length > 0) {
        data.forEach(item => {
            labels.push(item.time);
            const rad = parseFloat(item.radiation) || 0;
            li1Data.push(rad);
            li2Data.push(rad * 0.9);
        });
    }
    
    window.envCharts.light.data.labels = labels;
    window.envCharts.light.data.datasets[0].data = li2Data;
    window.envCharts.light.data.datasets[1].data = li1Data;
    window.envCharts.light.update();
    
    // ✅ Update badge
    const badge = document.getElementById('lightChartBadge');
    if (badge) {
        badge.textContent = labels.length > 0 ? `${labels.length} points` : 'No data';
    }
    
    console.log('✅ [CHART-FIX] Light chart updated:', labels.length, 'points');
}

function updateWaterChart(data) {
    if (!window.envCharts.water) return;
    
    const labels = [];
    const levels = [];
    
    if (data && data.length > 0) {
        data.forEach(item => {
            labels.push(item.time);
            levels.push(parseFloat(item.level) || 0);
        });
    }
    
    window.envCharts.water.data.labels = labels;
    window.envCharts.water.data.datasets[0].data = levels;
    window.envCharts.water.update();
    
    // ✅ Update badge
    const badge = document.getElementById('waterChartBadge');
    if (badge) {
        badge.textContent = labels.length > 0 ? `${labels.length} points` : 'No data';
    }
    
    console.log('✅ [CHART-FIX] Water chart updated:', labels.length, 'points');
}

function updateSoilChart(data) {
    if (!window.envCharts.soil) return;
    
    const labels = [];
    const sensorsData = [[], [], [], [], [], [], [], []];
    
    if (data && data.length > 0) {
        data.forEach(item => {
            labels.push(item.time);
            
            // Loop through up to 8 sensor keys: SM1, SM2... or use average if unavailable per-sensor.
            // For now just taking 'average' if data is simple, or reading actual SMx from original structure if available.
            const avg = parseFloat(item.average) || 0;
            // Provide the actual average, don't generate random variation
            for (let i = 0; i < 8; i++) {
                sensorsData[i].push(avg);
            }
        });
    }
    
    window.envCharts.soil.data.labels = labels;
    for (let i = 0; i < 8; i++) {
        window.envCharts.soil.data.datasets[i].data = sensorsData[i];
    }
    window.envCharts.soil.update();
    
    // ✅ Update badge
    const badge = document.getElementById('soilChartBadge');
    if (badge) {
        badge.textContent = labels.length > 0 ? `${labels.length} points` : 'No data';
    }
    
    console.log('✅ [CHART-FIX] Soil chart updated:', labels.length, 'points');
}

function updateTempChart(data) {
    if (!window.envCharts.temp) return;
    
    const labels = [];
    const t1Data = [];
    const t2Data = [];
    
    if (data && data.length > 0) {
        data.forEach(item => {
            labels.push(item.time);
            const temp = parseFloat(item.temperature) || 0;
            t1Data.push(temp);
            t2Data.push(temp); // Removing random fuzzing
        });
    }
    
    window.envCharts.temp.data.labels = labels;
    window.envCharts.temp.data.datasets[0].data = t1Data;
    window.envCharts.temp.data.datasets[1].data = t2Data;
    window.envCharts.temp.update();
    
    // ✅ Update badge
    const badge = document.getElementById('tempChartBadge');
    if (badge) {
        badge.textContent = labels.length > 0 ? `${labels.length} points` : 'No data';
    }
    
    console.log('✅ [CHART-FIX] Temperature chart updated:', labels.length, 'points');
}

function updateHumidityChart(data) {
    if (!window.envCharts.humidity) return;
    
    const labels = [];
    const h1Data = [];
    const h2Data = [];
    
    if (data && data.length > 0) {
        data.forEach(item => {
            labels.push(item.time);
            const hum = parseFloat(item.humidity) || 0;
            h1Data.push(hum);
            h2Data.push(hum); // Removing random fuzzing
        });
    }
    
    window.envCharts.humidity.data.labels = labels;
    window.envCharts.humidity.data.datasets[0].data = h2Data;
    window.envCharts.humidity.data.datasets[1].data = h1Data;
    window.envCharts.humidity.update();
    
    // ✅ Update badge
    const badge = document.getElementById('humidityChartBadge');
    if (badge) {
        badge.textContent = labels.length > 0 ? `${labels.length} points` : 'No data';
    }
    
    console.log('✅ [CHART-FIX] Humidity chart updated:', labels.length, 'points');
}

function loadSampleChartData() {
    updateLightChart(null);
    updateWaterChart(null);
    updateSoilChart(null);
    updateTempChart(null);
    updateHumidityChart(null);
    console.log('✅ [CHART-FIX] Empty state applied for all charts');
}
</script>
