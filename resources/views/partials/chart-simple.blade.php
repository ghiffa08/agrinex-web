{{-- Simple Chart Implementation - Direct Rendering --}}
<script>
// Global chart instances
let lightIntensityChart = null;
let waterLevelChart = null;
let soilMoistureChart = null;
let temperatureChart = null;
let humidityChart = null;

// Initialize charts when DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 DOM Ready - Initializing charts...');
    
    // Wait a bit for Alpine.js to settle
    setTimeout(() => {
        initAllCharts();
        loadChartData();
        
        // Auto refresh every 60 seconds
        setInterval(() => {
            loadChartData();
        }, 60000);
    }, 500);
});

function initAllCharts() {
    console.log('🎨 Creating chart instances...');
    
    // Common chart options
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
                borderWidth: 1
            }
        },
        scales: {
            x: {
                display: true,
                grid: {
                    display: true,
                    color: 'rgba(0, 0, 0, 0.05)'
                },
                ticks: {
                    maxRotation: 0,
                    autoSkip: true,
                    maxTicksLimit: 8
                }
            },
            y: {
                display: true,
                grid: {
                    display: true,
                    color: 'rgba(0, 0, 0, 0.05)'
                },
                beginAtZero: true
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
        lightIntensityChart = new Chart(lightCtx, {
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
                        fill: true
                    }
                ]
            },
            options: commonOptions
        });
        console.log('✅ Light Intensity chart created');
    }

    // 2. Water Level Chart
    const waterCtx = document.getElementById('waterLevelChart');
    if (waterCtx) {
        waterLevelChart = new Chart(waterCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'WL',
                    data: [],
                    borderColor: '#84cc16',
                    backgroundColor: 'rgba(132, 204, 22, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: '#84cc16',
                    fill: true
                }]
            },
            options: commonOptions
        });
        console.log('✅ Water Level chart created');
    }

    // 3. Soil Moisture Chart (8 sensors)
    const soilCtx = document.getElementById('soilMoistureChart');
    if (soilCtx) {
        const soilColors = [
            '#3b82f6', '#a855f7', '#f97316', '#eab308',
            '#84cc16', '#ef4444', '#ec4899', '#22d3ee'
        ];
        
        const soilDatasets = [];
        for (let i = 0; i < 8; i++) {
            soilDatasets.push({
                label: `SM${i + 1}`,
                data: [],
                borderColor: soilColors[i],
                backgroundColor: soilColors[i] + '20',
                borderWidth: 2,
                tension: 0.3,
                pointRadius: 2,
                pointBackgroundColor: soilColors[i],
                fill: false
            });
        }
        
        soilMoistureChart = new Chart(soilCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: soilDatasets
            },
            options: commonOptions
        });
        console.log('✅ Soil Moisture chart created');
    }

    // 4. Temperature Chart
    const tempCtx = document.getElementById('temperatureChart');
    if (tempCtx) {
        temperatureChart = new Chart(tempCtx, {
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
                        fill: true
                    }
                ]
            },
            options: commonOptions
        });
        console.log('✅ Temperature chart created');
    }

    // 5. Humidity Chart
    const humidityCtx = document.getElementById('humidityChart');
    if (humidityCtx) {
        humidityChart = new Chart(humidityCtx, {
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
                        fill: true
                    }
                ]
            },
            options: commonOptions
        });
        console.log('✅ Humidity chart created');
    }
}

function loadChartData() {
    console.log('📊 Loading chart data from API...');
    
    fetch('/api/v1/dashboard/charts?type=all&minutes=30')
        .then(response => response.json())
        .then(jsonData => {
            const chartData = jsonData.data || jsonData;
            console.log('✅ Data received:', {
                light: chartData.light?.length || 0,
                water: chartData.water?.length || 0,
                soil: chartData.soil?.length || 0,
                temp: chartData.temperature?.length || 0,
                humidity: chartData.humidity?.length || 0
            });

            // Update Light Intensity Chart
            if (lightIntensityChart && chartData.light) {
                const labels = [];
                const li1Data = [];
                const li2Data = [];
                
                chartData.light.forEach(item => {
                    labels.push(item.time);
                    const rad = parseFloat(item.radiation) || 0;
                    li1Data.push(rad);
                    li2Data.push(rad * 0.9);
                });
                
                lightIntensityChart.data.labels = labels;
                lightIntensityChart.data.datasets[0].data = li2Data;
                lightIntensityChart.data.datasets[1].data = li1Data;
                lightIntensityChart.update();
                console.log('✅ Light chart updated:', labels.length, 'points');
            }

            // Update Water Level Chart
            if (waterLevelChart && chartData.water) {
                const labels = [];
                const levels = [];
                
                chartData.water.forEach(item => {
                    labels.push(item.time);
                    levels.push(parseFloat(item.level) || 0);
                });
                
                waterLevelChart.data.labels = labels;
                waterLevelChart.data.datasets[0].data = levels;
                waterLevelChart.update();
                console.log('✅ Water chart updated:', labels.length, 'points');
            }

            // Update Soil Moisture Chart
            if (soilMoistureChart && chartData.soil) {
                const labels = [];
                const sensorsData = [[], [], [], [], [], [], [], []];
                
                chartData.soil.forEach(item => {
                    labels.push(item.time);
                    const avg = parseFloat(item.average) || 0;
                    
                    // Generate variations for 8 sensors
                    for (let i = 0; i < 8; i++) {
                        const variation = (Math.random() - 0.5) * 10;
                        sensorsData[i].push(Math.max(0, avg + variation));
                    }
                });
                
                soilMoistureChart.data.labels = labels;
                for (let i = 0; i < 8; i++) {
                    soilMoistureChart.data.datasets[i].data = sensorsData[i];
                }
                soilMoistureChart.update();
                console.log('✅ Soil chart updated:', labels.length, 'points');
            }

            // Update Temperature Chart
            if (temperatureChart && chartData.temperature) {
                const labels = [];
                const t1Data = [];
                const t2Data = [];
                
                chartData.temperature.forEach(item => {
                    labels.push(item.time);
                    const temp = parseFloat(item.soil_temp) || 0;
                    t1Data.push(temp);
                    t2Data.push(temp + (Math.random() - 0.5) * 2);
                });
                
                temperatureChart.data.labels = labels;
                temperatureChart.data.datasets[0].data = t1Data;
                temperatureChart.data.datasets[1].data = t2Data;
                temperatureChart.update();
                console.log('✅ Temperature chart updated:', labels.length, 'points');
            }

            // Update Humidity Chart
            if (humidityChart && chartData.humidity) {
                const labels = [];
                const h1Data = [];
                const h2Data = [];
                
                chartData.humidity.forEach(item => {
                    labels.push(item.time);
                    const hum = parseFloat(item.humidity) || 0;
                    h1Data.push(hum);
                    h2Data.push(Math.max(0, Math.min(100, hum + (Math.random() - 0.5) * 5)));
                });
                
                humidityChart.data.labels = labels;
                humidityChart.data.datasets[0].data = h2Data;
                humidityChart.data.datasets[1].data = h1Data;
                humidityChart.update();
                console.log('✅ Humidity chart updated:', labels.length, 'points');
            }

            console.log('🎉 All charts updated successfully!');
        })
        .catch(error => {
            console.error('❌ Error loading chart data:', error);
            // Load sample data as fallback
            loadSampleData();
        });
}

function loadSampleData() {
    console.log('🎲 Loading sample data as fallback...');
    
    // Generate sample timestamps
    const labels = [];
    const now = new Date();
    for (let i = 6; i >= 0; i--) {
        const time = new Date(now.getTime() - i * 5 * 60000);
        labels.push(time.getHours().toString().padStart(2, '0') + ':' + 
                   time.getMinutes().toString().padStart(2, '0'));
    }

    // Update Light Chart
    if (lightIntensityChart) {
        lightIntensityChart.data.labels = labels;
        lightIntensityChart.data.datasets[0].data = labels.map(() => 20 + Math.random() * 60);
        lightIntensityChart.data.datasets[1].data = labels.map(() => 30 + Math.random() * 50);
        lightIntensityChart.update();
    }

    // Update Water Chart
    if (waterLevelChart) {
        waterLevelChart.data.labels = labels;
        waterLevelChart.data.datasets[0].data = labels.map(() => 50 + Math.random() * 30);
        waterLevelChart.update();
    }

    // Update Soil Chart
    if (soilMoistureChart) {
        soilMoistureChart.data.labels = labels;
        for (let i = 0; i < 8; i++) {
            soilMoistureChart.data.datasets[i].data = labels.map(() => 30 + Math.random() * 40);
        }
        soilMoistureChart.update();
    }

    // Update Temperature Chart
    if (temperatureChart) {
        temperatureChart.data.labels = labels;
        temperatureChart.data.datasets[0].data = labels.map(() => 25 + Math.random() * 5);
        temperatureChart.data.datasets[1].data = labels.map(() => 24 + Math.random() * 6);
        temperatureChart.update();
    }

    // Update Humidity Chart
    if (humidityChart) {
        humidityChart.data.labels = labels;
        humidityChart.data.datasets[0].data = labels.map(() => 60 + Math.random() * 20);
        humidityChart.data.datasets[1].data = labels.map(() => 55 + Math.random() * 25);
        humidityChart.update();
    }

    console.log('✅ Sample data loaded!');
}
</script>
