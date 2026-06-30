@extends('layouts.app')

@section('title', 'Irrigation Session Details')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-droplet"></i> Irrigation Session Details
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('irrigation.index') }}">Irrigation</a></li>
                    <li class="breadcrumb-item active">Session {{ $session->session_id }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('irrigation.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Irrigation
        </a>
    </div>

    <!-- Session Summary Card -->
    <div class="row g-4 mb-4">
        <div class="col-md-12">
            <div class="card-custom">
                <div class="card-custom-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Session Information</h5>
                </div>
                <div class="card-custom-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Session ID</p>
                            <h6>{{ $session->session_id }}</h6>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Started At</p>
                            <h6>{{ $session->started_at ? $session->started_at->format('Y-m-d H:i:s') : '-' }}</h6>
                        </div>
                        <div class="col-md-2">
                            <p class="text-muted mb-1">Successful Nodes</p>
                            <h6 class="text-success">{{ $session->success_count ?? 0 }}</h6>
                        </div>
                        <div class="col-md-2">
                            <p class="text-muted mb-1">Failed Nodes</p>
                            <h6 class="text-danger">{{ $session->failed_count ?? 0 }}</h6>
                        </div>
                        <div class="col-md-2">
                            <p class="text-muted mb-1">Valves Still On</p>
                            <h6 class="text-warning">{{ $session->valve_on_count ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Valve Logs Table -->
    <div class="card-custom">
        <div class="card-custom-header">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Valve Operation Logs</h5>
        </div>
        <div class="card-custom-body">
            @if($valveLogs->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No Valve Logs Found</h5>
                    <p class="text-muted">No valve operations recorded for this session.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>Node ID</th>
                                <th>Location</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($valveLogs as $log)
                                <tr>
                                    <td>
                                        <i class="bi bi-clock"></i>
                                        {{ $log->logged_at ? $log->logged_at->format('Y-m-d H:i:s') : '-' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('nodes.show', $log->device_id) }}" class="text-decoration-none">
                                            <strong>Device {{ $log->device_id }}</strong>
                                        </a>
                                    </td>
                                    <td>{{ $log->node->lokasi ?? '-' }}</td>
                                    <td>
                                        @php
                                            $duration = 0; // Removed from DB schema
                                            $minutes = floor($duration / 60);
                                            $seconds = $duration % 60;
                                        @endphp
                                        @if($minutes > 0)
                                            <strong>{{ $minutes }}</strong> min 
                                        @endif
                                        <strong>{{ $seconds }}</strong> sec
                                        <br>
                                        <small class="text-muted">({{ $duration }} seconds)</small>
                                    </td>
                                    <td>
                                        @if($log->status === 'ON')
                                            <span class="badge bg-success">
                                                <i class="bi bi-toggle-on"></i> ON
                                            </span>
                                        @elseif($log->status === 'OFF')
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-toggle-off"></i> OFF
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                {{ $log->status ?? 'Unknown' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('nodes.show', $log->device_id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View Node
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total Duration:</strong></td>
                                <td colspan="3">
                                    @php
                                        $totalSeconds = 0;
                                        $totalMinutes = floor($totalSeconds / 60);
                                        $remainingSeconds = $totalSeconds % 60;
                                    @endphp
                                    <strong>{{ $totalMinutes }}</strong> minutes 
                                    <strong>{{ $remainingSeconds }}</strong> seconds
                                    <span class="text-muted">({{ $totalSeconds }} seconds total)</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Timeline Visualization (Optional Enhancement) -->
    @if($valveLogs->isNotEmpty())
    <div class="card-custom mt-4">
        <div class="card-custom-header">
            <h5 class="mb-0"><i class="bi bi-diagram-3 me-2"></i>Operation Timeline</h5>
        </div>
        <div class="card-custom-body">
            <div class="timeline">
                @foreach($valveLogs as $index => $log)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-{{ $log->status === 'ON' ? 'success' : 'secondary' }}"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">
                                        Device {{ $log->device_id }} - 
                                        <span class="badge bg-{{ $log->status === 'ON' ? 'success' : 'secondary' }}">
                                            {{ $log->status }}
                                        </span>
                                    </h6>
                                    <p class="mb-0 text-muted">
                                        <i class="bi bi-clock"></i> 
                                        {{ $log->logged_at ? $log->logged_at->format('H:i:s') : '-' }} 
                                        | Duration: N/A
                                    </p>
                                </div>
                                <div>
                                    <a href="{{ route('nodes.show', $log->device_id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 40px;
    padding-bottom: 30px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 9px;
    top: 30px;
    width: 2px;
    height: calc(100% - 10px);
    background: #dee2e6;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 5px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #dee2e6;
}

.timeline-item:hover .timeline-content {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
</style>
@endsection
