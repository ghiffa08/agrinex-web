@extends('layouts.app')

@section('title', 'System Alerts')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-exclamation-triangle"></i> System Alerts</h4>
            <p class="text-muted mb-0">Monitor system health and alerts</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card-custom border-danger">
                <div class="card-custom-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Critical</p>
                            <h3 class="mb-0 text-danger">{{ $stats['critical'] }}</h3>
                            <small class="text-muted">Requires immediate attention</small>
                        </div>
                        <div class="stat-icon bg-danger">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-custom border-warning">
                <div class="card-custom-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Warning</p>
                            <h3 class="mb-0 text-warning">{{ $stats['warning'] }}</h3>
                            <small class="text-muted">Needs attention</small>
                        </div>
                        <div class="stat-icon bg-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-custom border-info">
                <div class="card-custom-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Info</p>
                            <h3 class="mb-0 text-info">{{ $stats['info'] }}</h3>
                            <small class="text-muted">Informational</small>
                        </div>
                        <div class="stat-icon bg-info">
                            <i class="bi bi-info-circle"></i>
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
                            <p class="text-muted mb-1">Total</p>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            <small class="text-muted">All alerts</small>
                        </div>
                        <div class="stat-icon bg-primary">
                            <i class="bi bi-bell"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Filter Buttons -->
    <div class="mb-4">
        <div class="btn-group" role="group">
            <a href="{{ route('alerts.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-list"></i> All Alerts
            </a>
            <a href="{{ route('alerts.by-type', 'moisture') }}" class="btn btn-outline-danger">
                <i class="bi bi-droplet"></i> Low Moisture
            </a>
            <a href="{{ route('alerts.by-type', 'temperature') }}" class="btn btn-outline-warning">
                <i class="bi bi-thermometer-high"></i> High Temperature
            </a>
            <a href="{{ route('alerts.by-type', 'voltage') }}" class="btn btn-outline-warning">
                <i class="bi bi-battery-half"></i> Low Voltage
            </a>
            <a href="{{ route('alerts.by-type', 'communication') }}" class="btn btn-outline-info">
                <i class="bi bi-wifi-off"></i> Communication
            </a>
        </div>
    </div>

    <!-- Offline Nodes - Critical -->
    @if($offlineNodes->isNotEmpty())
    <div class="card-custom border-danger mb-4">
        <div class="card-custom-header bg-danger text-white">
            <h5 class="mb-0">
                <i class="bi bi-exclamation-circle"></i> Offline Nodes ({{ $offlineNodes->count() }})
            </h5>
        </div>
        <div class="card-custom-body">
            <div class="row">
                @foreach($offlineNodes as $node)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Device {{ $node->id }}</h6>
                            <p class="mb-1"><strong>Location:</strong> {{ $node->lokasi ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Group:</strong> {{ $node->group ?? 'N/A' }}</p>
                            <p class="mb-0 text-danger">
                                <i class="bi bi-clock"></i> No data received in last 2 hours
                            </p>
                            <a href="{{ route('nodes.show', $node->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Low Moisture - Critical -->
    @if($lowMoisture->isNotEmpty())
    <div class="card-custom border-danger mb-4">
        <div class="card-custom-header bg-danger text-white">
            <h5 class="mb-0">
                <i class="bi bi-droplet"></i> Low Soil Moisture ({{ $lowMoisture->count() }})
            </h5>
        </div>
        <div class="card-custom-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Node ID</th>
                            <th>Location</th>
                            <th>Moisture Level</th>
                            <th>Temperature</th>
                            <th>Last Reading</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowMoisture as $data)
                        <tr>
                            <td><strong>Device {{ $data->device_id }}</strong></td>
                            <td>{{ $data->device->lokasi ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-danger">
                                    {{ number_format($data->soil_pct, 1) }}%
                                </span>
                            </td>
                            <td>{{ number_format($data->temp_c, 1) }}°C</td>
                            <td>{{ $data->recorded_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('nodes.show', $data->device_id) }}" class="btn btn-sm btn-outline-primary">
                                    View Node
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- High Temperature - Warning -->
    @if($highTemp->isNotEmpty())
    <div class="card-custom border-warning mb-4">
        <div class="card-custom-header bg-warning">
            <h5 class="mb-0">
                <i class="bi bi-thermometer-high"></i> High Temperature ({{ $highTemp->count() }})
            </h5>
        </div>
        <div class="card-custom-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Node ID</th>
                            <th>Location</th>
                            <th>Temperature</th>
                            <th>Moisture</th>
                            <th>Last Reading</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($highTemp as $data)
                        <tr>
                            <td><strong>Device {{ $data->device_id }}</strong></td>
                            <td>{{ $data->device->lokasi ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-warning">
                                    {{ number_format($data->temp_c, 1) }}°C
                                </span>
                            </td>
                            <td>{{ number_format($data->soil_pct, 1) }}%</td>
                            <td>{{ $data->recorded_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('nodes.show', $data->device_id) }}" class="btn btn-sm btn-outline-primary">
                                    View Node
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Low Voltage - Warning -->
    @if($lowVoltage->isNotEmpty())
    <div class="card-custom border-warning mb-4">
        <div class="card-custom-header bg-warning">
            <h5 class="mb-0">
                <i class="bi bi-battery-half"></i> Low Voltage ({{ $lowVoltage->count() }})
            </h5>
        </div>
        <div class="card-custom-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Node ID</th>
                            <th>Location</th>
                            <th>Voltage</th>
                            <th>Current</th>
                            <th>Last Reading</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowVoltage as $data)
                        <tr>
                            <td><strong>Device {{ $data->device_id }}</strong></td>
                            <td>{{ $data->device->lokasi ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-warning">
                                    {{ number_format($data->voltage_v, 2) }}V
                                </span>
                            </td>
                            <td>{{ number_format($data->current_ma, 0) }} mA</td>
                            <td>{{ $data->recorded_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('nodes.show', $data->device_id) }}" class="btn btn-sm btn-outline-primary">
                                    View Node
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Communication Failures - Info -->
    @if($commFailures->isNotEmpty())
    <div class="card-custom border-info mb-4">
        <div class="card-custom-header bg-info text-white">
            <h5 class="mb-0">
                <i class="bi bi-wifi-off"></i> Communication Issues ({{ $commFailures->count() }})
            </h5>
        </div>
        <div class="card-custom-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Node ID</th>
                            <th>Time</th>
                            <th>RSSI</th>
                            <th>SNR</th>
                            <th>Signal Quality</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commFailures->take(20) as $log)
                        <tr>
                            <td><strong>Device {{ $log->device_id }}</strong></td>
                            <td>{{ $log->logged_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $log->rssi_dbm }} dBm</td>
                            <td>{{ $log->snr_db }} dB</td>
                            <td>
                                <span class="badge bg-danger">{{ $log->signal_quality }}</span>
                            </td>
                            <td>
                                <a href="{{ route('nodes.show', $log->device_id) }}" class="btn btn-sm btn-outline-primary">
                                    View Node
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- No Alerts -->
    @if($offlineNodes->isEmpty() && $lowMoisture->isEmpty() && $highTemp->isEmpty() && $lowVoltage->isEmpty() && $commFailures->isEmpty())
    <div class="card-custom">
        <div class="card-custom-body text-center py-5">
            <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
            <h5 class="mt-3">All Systems Operational</h5>
            <p class="text-muted">No alerts at this time. All nodes are operating normally.</p>
        </div>
    </div>
    @endif
</div>
@endsection
