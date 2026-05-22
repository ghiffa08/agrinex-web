@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-exclamation-triangle"></i> {{ $title }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('alerts.index') }}">Alerts</a></li>
                    <li class="breadcrumb-item active">{{ ucfirst($type) }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('alerts.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to All Alerts
        </a>
    </div>

    <!-- Quick Filter Buttons -->
    <div class="mb-4">
        <div class="btn-group" role="group">
            <a href="{{ route('alerts.by-type', 'moisture') }}" 
               class="btn {{ $type === 'moisture' ? 'btn-danger' : 'btn-outline-danger' }}">
                <i class="bi bi-droplet"></i> Low Moisture
            </a>
            <a href="{{ route('alerts.by-type', 'temperature') }}" 
               class="btn {{ $type === 'temperature' ? 'btn-warning' : 'btn-outline-warning' }}">
                <i class="bi bi-thermometer-high"></i> High Temperature
            </a>
            <a href="{{ route('alerts.by-type', 'voltage') }}" 
               class="btn {{ $type === 'voltage' ? 'btn-warning' : 'btn-outline-warning' }}">
                <i class="bi bi-battery-half"></i> Low Voltage
            </a>
            <a href="{{ route('alerts.by-type', 'communication') }}" 
               class="btn {{ $type === 'communication' ? 'btn-info' : 'btn-outline-info' }}">
                <i class="bi bi-wifi"></i> Communication Issues
            </a>
        </div>
    </div>

    <!-- Alerts Table -->
    <div class="card-custom">
        <div class="card-custom-body">
            @if($alerts->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No {{ ucfirst($type) }} Alerts</h5>
                    <p class="text-muted">Everything is working fine!</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Node ID</th>
                                <th>Location</th>
                                @if($type === 'moisture')
                                    <th>Soil Moisture</th>
                                @elseif($type === 'temperature')
                                    <th>Temperature</th>
                                @elseif($type === 'voltage')
                                    <th>Voltage</th>
                                    <th>Battery %</th>
                                @elseif($type === 'communication')
                                    <th>RSSI</th>
                                    <th>SNR</th>
                                    <th>Signal Quality</th>
                                @endif
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alerts as $alert)
                                <tr>
                                    @if($type === 'communication')
                                        <td>{{ $alert->waktu_log ? $alert->waktu_log->format('Y-m-d H:i:s') : '-' }}</td>
                                        <td>
                                            <a href="{{ route('nodes.show', $alert->node_id) }}" class="text-decoration-none">
                                                <strong>{{ $alert->node_id }}</strong>
                                            </a>
                                        </td>
                                        <td>{{ $alert->node->lokasi ?? '-' }}</td>
                                        <td>{{ $alert->rssi ?? '-' }} dBm</td>
                                        <td>{{ $alert->snr ?? '-' }} dB</td>
                                        <td>
                                            @php
                                                $quality = $alert->signal_quality ?? 'Unknown';
                                                $badgeClass = match($quality) {
                                                    'Excellent', 'Good' => 'success',
                                                    'Fair' => 'warning',
                                                    'Poor' => 'danger',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $badgeClass }}">{{ $quality }}</span>
                                        </td>
                                        <td>
                                            @if($alert->status === 'Aktif')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                    @else
                                        <td>{{ $alert->received_at ? $alert->received_at->format('Y-m-d H:i:s') : '-' }}</td>
                                        <td>
                                            <a href="{{ route('nodes.show', $alert->node_id) }}" class="text-decoration-none">
                                                <strong>{{ $alert->node_id }}</strong>
                                            </a>
                                        </td>
                                        <td>{{ $alert->node->lokasi ?? '-' }}</td>
                                        
                                        @if($type === 'moisture')
                                            <td>
                                                <span class="badge bg-danger">{{ $alert->moist ?? 0 }}%</span>
                                            </td>
                                            <td>
                                                @if($alert->moist < 20)
                                                    <span class="badge bg-danger">Critical</span>
                                                @elseif($alert->moist < 30)
                                                    <span class="badge bg-warning">Warning</span>
                                                @else
                                                    <span class="badge bg-info">Info</span>
                                                @endif
                                            </td>
                                        @elseif($type === 'temperature')
                                            <td>
                                                <span class="badge bg-warning">{{ $alert->temp_ds18 ?? 0 }}°C</span>
                                            </td>
                                            <td>
                                                @if($alert->temp_ds18 > 40)
                                                    <span class="badge bg-danger">Critical</span>
                                                @elseif($alert->temp_ds18 > 35)
                                                    <span class="badge bg-warning">Warning</span>
                                                @else
                                                    <span class="badge bg-info">Info</span>
                                                @endif
                                            </td>
                                        @elseif($type === 'voltage')
                                            <td>
                                                <span class="badge bg-warning">{{ $alert->volt ?? 0 }}V</span>
                                            </td>
                                            <td>
                                                @php
                                                    $batteryPct = (($alert->volt ?? 0) - 2.5) / (4.2 - 2.5) * 100;
                                                    $batteryPct = max(0, min(100, $batteryPct));
                                                @endphp
                                                {{ number_format($batteryPct, 1) }}%
                                            </td>
                                            <td>
                                                @if($alert->volt < 3.0)
                                                    <span class="badge bg-danger">Critical</span>
                                                @elseif($alert->volt < 3.3)
                                                    <span class="badge bg-warning">Warning</span>
                                                @else
                                                    <span class="badge bg-info">Info</span>
                                                @endif
                                            </td>
                                        @endif
                                    @endif
                                    
                                    <td>
                                        <a href="{{ route('nodes.show', $alert->node_id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View Node
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $alerts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
