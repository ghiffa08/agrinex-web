@extends('layouts.app')

@section('title', 'Weather Station')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-cloud-sun"></i> Weather Station</h4>
            <p class="text-muted mb-0">Weather monitoring from Node 65</p>
        </div>
        <a href="{{ route('weather.history') }}" class="btn btn-outline-primary">
            <i class="bi bi-clock-history"></i> View History
        </a>
    </div>

    <!-- Current Weather Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card-custom">
                <div class="card-custom-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Temperature</p>
                            <h3 class="mb-0">{{ number_format($stats['current_temp'], 1) }}°C</h3>
                            <small class="text-muted">24h avg: {{ number_format($stats['avg_temp_24h'], 1) }}°C</small>
                        </div>
                        <div class="stat-icon bg-danger">
                            <i class="bi bi-thermometer-half"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-custom">
                <div class="card-custom-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Humidity</p>
                            <h3 class="mb-0">{{ number_format($stats['current_humidity'], 1) }}%</h3>
                            <small class="text-muted">24h avg: {{ number_format($stats['avg_humidity_24h'], 1) }}%</small>
                        </div>
                        <div class="stat-icon bg-info">
                            <i class="bi bi-moisture"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-custom">
                <div class="card-custom-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Light Level</p>
                            <h3 class="mb-0">{{ number_format($stats['max_light_24h'], 0) }}</h3>
                            <small class="text-muted">24h maximum</small>
                        </div>
                        <div class="stat-icon bg-warning">
                            <i class="bi bi-brightness-high"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-custom">
                <div class="card-custom-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Rain Status</p>
                            <h3 class="mb-0">
                                @if($stats['rain_status'] === 'Raining')
                                    <i class="bi bi-cloud-rain text-primary"></i>
                                @else
                                    <i class="bi bi-sun text-warning"></i>
                                @endif
                            </h3>
                            <small class="text-muted">{{ $stats['rain_status'] }}</small>
                        </div>
                        <div class="stat-icon bg-primary">
                            <i class="bi bi-cloud-drizzle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Reading Card -->
    @if($latestWeather)
    <div class="card-custom mb-4">
        <div class="card-custom-header">
            <h5 class="mb-0">Latest Weather Reading</h5>
        </div>
        <div class="card-custom-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $latestWeather->waktu_weather->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Temperature:</th>
                            <td>{{ number_format($latestWeather->temp_dht, 1) }}°C</td>
                        </tr>
                        <tr>
                            <th>Humidity:</th>
                            <td>{{ number_format($latestWeather->humidity, 1) }}%</td>
                        </tr>
                        <tr>
                            <th>Light Level:</th>
                            <td>{{ number_format($latestWeather->light, 0) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th>Wind Speed:</th>
                            <td>{{ number_format($latestWeather->wind, 1) }} km/h</td>
                        </tr>
                        <tr>
                            <th>Rain Sensor:</th>
                            <td>
                                @if($latestWeather->rain > 0)
                                    <span class="badge bg-primary">Raining ({{ $latestWeather->rain }})</span>
                                @else
                                    <span class="badge bg-success">No Rain</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Total Readings:</th>
                            <td>{{ number_format($stats['total_readings']) }}</td>
                        </tr>
                        <tr>
                            <th>Reading Age:</th>
                            <td>{{ $latestWeather->waktu_weather->diffForHumans() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Weather Charts -->
    <div class="row g-3 mb-4">
        <!-- Temperature & Humidity Chart -->
        <div class="col-md-6">
            <div class="card-custom">
                <div class="card-custom-header">
                    <h5 class="mb-0">Temperature & Humidity (24h)</h5>
                </div>
                <div class="card-custom-body">
                    <canvas id="tempHumidityChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Light & Wind Chart -->
        <div class="col-md-6">
            <div class="card-custom">
                <div class="card-custom-header">
                    <h5 class="mb-0">Light Level & Wind Speed (24h)</h5>
                </div>
                <div class="card-custom-body">
                    <canvas id="lightWindChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- 7-Day Averages -->
    <div class="card-custom">
        <div class="card-custom-header">
            <h5 class="mb-0">7-Day Weather Summary</h5>
        </div>
        <div class="card-custom-body">
            <canvas id="weeklyChart" height="80"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch chart data
    fetch('{{ route('weather.chart-data') }}?period=24h')
        .then(response => response.json())
        .then(data => {
            // Temperature & Humidity Chart
            const ctx1 = document.getElementById('tempHumidityChart');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Temperature (°C)',
                            data: data.temperature,
                            borderColor: 'rgb(239, 68, 68)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            yAxisID: 'y',
                        },
                        {
                            label: 'Humidity (%)',
                            data: data.humidity,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: { display: true, text: 'Temperature (°C)' }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: { display: true, text: 'Humidity (%)' },
                            grid: { drawOnChartArea: false }
                        }
                    }
                }
            });

            // Light & Wind Chart
            const ctx2 = document.getElementById('lightWindChart');
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Light Level',
                            data: data.light,
                            borderColor: 'rgb(251, 191, 36)',
                            backgroundColor: 'rgba(251, 191, 36, 0.1)',
                            yAxisID: 'y',
                        },
                        {
                            label: 'Wind Speed (km/h)',
                            data: data.wind,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: { display: true, text: 'Light Level' }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: { display: true, text: 'Wind Speed (km/h)' },
                            grid: { drawOnChartArea: false }
                        }
                    }
                }
            });
        });

    // Fetch 7-day data
    fetch('{{ route('weather.chart-data') }}?period=7d')
        .then(response => response.json())
        .then(data => {
            const ctx3 = document.getElementById('weeklyChart');
            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Avg Temperature (°C)',
                            data: data.temperature,
                            backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        },
                        {
                            label: 'Avg Humidity (%)',
                            data: data.humidity,
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
});
</script>
@endpush
@endsection
