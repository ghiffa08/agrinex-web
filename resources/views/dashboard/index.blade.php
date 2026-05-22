@extends('layouts.app')

@section('title', 'Dashboard - Smart Drip Irrigation')
@section('page-title', 'Dashboard Overview')

@section('content')
<!-- Stats Cards Row -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-card-label">Total Nodes</div>
                    <div class="stat-card-value text-primary" id="total-nodes">{{ $stats['total_nodes'] }}</div>
                    <small class="text-success">
                        <i class="bi bi-arrow-up"></i> {{ $stats['active_nodes'] }} active
                    </small>
                </div>
                <div class="stat-card-icon" style="background-color: #dbeafe; color: #1e40af;">
                    <i class="bi bi-cpu"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-card-label">Experimental Plots</div>
                    <div class="stat-card-value text-success">{{ $stats['total_plots'] }}</div>
                    <small class="text-muted">All plots</small>
                </div>
                <div class="stat-card-icon" style="background-color: #d1fae5; color: #065f46;">
                    <i class="bi bi-grid-3x3"></i>
                </div>
            </div>
        </div>
    </div>
    
    {{-- <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-card-label">Active Alerts</div>
                    <div class="stat-card-value text-danger" id="active-alerts">{{ $stats['active_alerts'] }}</div>
                    <small class="text-danger">
                        <i class="bi bi-exclamation-circle"></i> Needs attention
                    </small>
                </div>
                <div class="stat-card-icon" style="background-color: #fee2e2; color: #991b1b;">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div> --}}
    
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-card-label">Ongoing Irrigation</div>
                    <div class="stat-card-value text-info" id="ongoing-irrigation">{{ $stats['ongoing_irrigation'] }}</div>
                    <small class="text-info">
                        <i class="bi bi-water"></i> Active now
                    </small>
                </div>
                <div class="stat-card-icon" style="background-color: #dbeafe; color: #0284c7;">
                    <i class="bi bi-water"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Sensor Nodes Status -->
        <div class="card-custom">
            <div class="card-custom-header">
                <h5 class="mb-0"><i class="bi bi-cpu me-2"></i>Sensor Nodes Status</h5>
                <button class="btn btn-sm btn-outline-primary" onclick="refreshNodes()">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
            </div>
            <div class="card-custom-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Node ID</th>
                                <th>Group</th>
                                <th>Perlakuan</th>
                                <th>Status</th>
                                <th>Soil Moisture</th>
                                <th>Temperature</th>
                                <th>Last Reading</th>
                            </tr>
                        </thead>
                        <tbody id="nodes-table-body">
                            @forelse($nodesWithData as $node)
                            <tr>
                                <td>
                                    <strong>
                                        @if($node->node_id == 65)
                                            <span class="badge bg-warning text-dark">Node {{ $node->node_id }}</span>
                                        @else
                                            <span class="badge bg-primary">Node {{ $node->node_id }}</span>
                                        @endif
                                    </strong>
                                </td>
                                <td>{{ $node->group ?? 'N/A' }}</td>
                                <td>{{ $node->kode_perlakuan ?? 'N/A' }}</td>
                                <td>
                                    @if($node->is_active)
                                        <span class="node-badge online">
                                            <span class="status-dot online"></span> Online
                                        </span>
                                    @else
                                        <span class="node-badge offline">
                                            <span class="status-dot offline"></span> Offline
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($node->latestReading && $node->latestReading->soil_pct !== null)
                                        @php
                                            $moisture = $node->latestReading->soil_pct;
                                            $color = $moisture < 30 ? 'danger' : ($moisture < 60 ? 'warning' : 'success');
                                        @endphp
                                        <span class="fw-bold text-{{ $color }}">{{ number_format($moisture, 1) }}</span> %
                                    @else
                                        <span class="text-muted">--</span>
                                    @endif
                                </td>
                                <td>
                                    @if($node->latestReading && $node->latestReading->temp_c !== null)
                                        <span class="fw-bold text-danger">{{ number_format($node->latestReading->temp_c, 1) }}</span> °C
                                    @else
                                        <span class="text-muted">--</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        @if($node->lastCommunication)
                                            {{ \Carbon\Carbon::parse($node->lastCommunication)->diffForHumans() }}
                                        @else
                                            Never
                                        @endif
                                    </small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">No sensor nodes found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Soil Moisture Trend Chart -->
        <div class="card-custom mt-4">
            <div class="card-custom-header">
                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Soil Moisture Trend (Last 24h)</h5>
                <select class="form-select form-select-sm" style="width: 200px;" id="chart-node-select" onchange="updateChart()">
                    <option value="">Select Node</option>
                    @foreach($nodes as $node)
                        <option value="{{ $node->node_id }}">{{ $node->node_code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="card-custom-body">
                <canvas id="soilMoistureChart" height="80"></canvas>
            </div>
        </div>
        
        <!-- Temperature & Humidity Chart -->
        <div class="card-custom mt-4">
            <div class="card-custom-header">
                <h5 class="mb-0"><i class="bi bi-thermometer-half me-2"></i>Temperature & Humidity</h5>
            </div>
            <div class="card-custom-body">
                <canvas id="tempHumidityChart" height="80"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Weather Widget -->
        @if($weather)
        <div class="card-custom">
            <div class="card-custom-header">
                <h5 class="mb-0"><i class="bi bi-cloud-sun me-2"></i>Current Weather (Node 65)</h5>
            </div>
            <div class="card-custom-body text-center">
                <div class="mb-3">
                    <i class="bi bi-sun fs-1 text-warning"></i>
                </div>
                <h2 class="mb-0">{{ number_format($weather->temp_dht ?? 0, 1) }}°C</h2>
                <p class="text-muted mb-4">{{ number_format($weather->humidity ?? 0, 0) }}% Humidity</p>
                
                <div class="row g-3 text-start">
                    <div class="col-6">
                        <div class="sensor-reading" style="padding: 12px;">
                            <div class="sensor-icon" style="background-color: #fef3c7; color: #92400e; width: 35px; height: 35px; font-size: 16px;">
                                <i class="bi bi-droplet"></i>
                            </div>
                            <div>
                                <div class="sensor-label">Rain ADC</div>
                                <div class="sensor-value" style="font-size: 18px;">{{ $weather->rain_adc ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="sensor-reading" style="padding: 12px;">
                            <div class="sensor-icon" style="background-color: #dbeafe; color: #1e40af; width: 35px; height: 35px; font-size: 16px;">
                                <i class="bi bi-wind"></i>
                            </div>
                            <div>
                                <div class="sensor-label">Wind</div>
                                <div class="sensor-value" style="font-size: 18px;">{{ number_format($weather->wind ?? 0, 1) }} m/s</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sensor-reading" style="padding: 12px;">
                            <div class="sensor-icon" style="background-color: #fef3c7; color: #92400e; width: 35px; height: 35px; font-size: 16px;">
                                <i class="bi bi-brightness-high"></i>
                            </div>
                            <div>
                                <div class="sensor-label">Light Intensity</div>
                                <div class="sensor-value" style="font-size: 18px;">{{ number_format($weather->light ?? 0, 0) }} lux</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sensor-reading" style="padding: 12px;">
                            <div class="sensor-icon" style="background-color: #dcfce7; color: #166534; width: 35px; height: 35px; font-size: 16px;">
                                <i class="bi bi-battery-charging"></i>
                            </div>
                            <div>
                                <div class="sensor-label">Power</div>
                                <div class="sensor-value" style="font-size: 14px;">
                                    {{ number_format($weather->voltage ?? 0, 2) }}V / {{ number_format($weather->current ?? 0, 0) }}mA
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <small class="text-muted d-block mt-3">
                    <i class="bi bi-clock"></i> Updated {{ \Carbon\Carbon::parse($weather->received_at)->diffForHumans() }}
                </small>
            </div>
        </div>
        @endif
        
        <!-- Recent Alerts -->
        {{-- <div class="card-custom mt-4">
            <div class="card-custom-header">
                <h5 class="mb-0"><i class="bi bi-bell me-2"></i>Recent Alerts</h5>
                <a href="{{ route('alerts.index') }}" class="btn btn-sm btn-link">View All</a>
            </div>
            <div class="card-custom-body">
                @forelse($recentAlerts as $alert)
                <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                    <div class="me-3">
                        @if($alert->severity == 'critical')
                            <i class="bi bi-exclamation-circle-fill text-danger fs-4"></i>
                        @elseif($alert->severity == 'warning')
                            <i class="bi bi-exclamation-triangle-fill text-warning fs-4"></i>
                        @else
                            <i class="bi bi-info-circle-fill text-info fs-4"></i>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <strong class="d-block">{{ $alert->message }}</strong>
                            <span class="alert-badge {{ $alert->severity }}">{{ $alert->severity }}</span>
                        </div>
                        <small class="text-muted d-block">{{ \Carbon\Carbon::parse($alert->timestamp)->diffForHumans() }}</small>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="bi bi-check-circle fs-1 text-success"></i>
                    <p class="text-muted mt-2 mb-0">No active alerts</p>
                </div>
                @endforelse
            </div>
        </div> --}}
        
        <!-- Today's Irrigation -->
        <div class="card-custom mt-4">
            <div class="card-custom-header">
                <h5 class="mb-0"><i class="bi bi-water me-2"></i>Today's Irrigation</h5>
            </div>
            <div class="card-custom-body">
                @forelse($todayIrrigation as $event)
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="me-3">
                        <i class="bi bi-droplet-fill text-primary fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong class="d-block">Sesi ID: {{ $event->sesi_id_irrigate }}</strong>
                        <small class="text-muted d-block">
                            Nodes: 
                            <span class="badge bg-success">{{ $event->node_sukses }} Success</span>
                            <span class="badge bg-danger">{{ $event->node_gagal }} Failed</span>
                        </small>
                        <small class="text-muted d-block">
                            Valve ON: <span class="badge bg-info">{{ $event->valve_on_akhir }}</span>
                        </small>
                        @if($event->waktu_akhir)
                            <span class="badge bg-success ms-2">Completed</span>
                        @else
                            <span class="badge bg-warning text-dark ms-2">In Progress</span>
                        @endif
                        <small class="text-muted d-block mt-1">
                            {{ \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') }}
                            @if($event->waktu_akhir)
                                - {{ \Carbon\Carbon::parse($event->waktu_akhir)->format('H:i') }}
                            @endif
                        </small>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="bi bi-droplet fs-1 text-muted"></i>
                    <p class="text-muted mt-2 mb-0">No irrigation events today</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize Charts
    let soilMoistureChart, tempHumidityChart;
    
    // Soil Moisture Chart
    const ctxSoilMoisture = document.getElementById('soilMoistureChart');
    if (ctxSoilMoisture) {
        soilMoistureChart = new Chart(ctxSoilMoisture, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Soil Moisture (%)',
                    data: [],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Temperature & Humidity Chart
    const ctxTempHumidity = document.getElementById('tempHumidityChart');
    if (ctxTempHumidity) {
        tempHumidityChart = new Chart(ctxTempHumidity, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Temperature (°C)',
                        data: [],
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        yAxisID: 'y',
                        tension: 0.4
                    },
                    {
                        label: 'Humidity (%)',
                        data: [],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        yAxisID: 'y1',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Temperature (°C)'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Humidity (%)'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    }
    
    // Update Chart Data
    function updateChart() {
        const nodeId = document.getElementById('chart-node-select').value;
        if (!nodeId) return;
        
        fetch(`/dashboard/chart-data?node_id=${nodeId}&hours=24`)
            .then(response => response.json())
            .then(data => {
                // Update Soil Moisture Chart
                soilMoistureChart.data.labels = data.labels;
                soilMoistureChart.data.datasets[0].data = data.soil_moisture;
                soilMoistureChart.update();
                
                // Update Temperature & Humidity Chart
                tempHumidityChart.data.labels = data.labels;
                tempHumidityChart.data.datasets[0].data = data.air_temperature;
                tempHumidityChart.data.datasets[1].data = data.air_humidity;
                tempHumidityChart.update();
            })
            .catch(error => console.error('Error fetching chart data:', error));
    }
    
    // Refresh Nodes Data
    function refreshNodes() {
        fetch('/dashboard/realtime-data')
            .then(response => response.json())
            .then(data => {
                // Update stats
                document.getElementById('total-nodes').textContent = data.nodes.length;
                document.getElementById('active-alerts').textContent = data.active_alerts;
                
                // Update nodes table (simplified - you can expand this)
                console.log('Nodes data updated:', data);
            })
            .catch(error => console.error('Error refreshing data:', error));
    }
    
    // Auto-refresh every 30 seconds
    setInterval(refreshNodes, 30000);
    
    // Load initial chart data if a node is selected
    document.addEventListener('DOMContentLoaded', function() {
        const nodeSelect = document.getElementById('chart-node-select');
        if (nodeSelect && nodeSelect.options.length > 1) {
            nodeSelect.selectedIndex = 1;
            updateChart();
        }
    });
</script>
@endpush
