@extends('layouts.app')

@section('title', 'Irrigation Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-water"></i> Irrigation Management</h4>
            <p class="text-muted mb-0">Monitor and control irrigation system</p>
        </div>
        {{-- @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#triggerIrrigationModal">
            <i class="bi bi-droplet-fill"></i> Trigger Irrigation
        </button>
        @endif --}}
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card-custom">
                <div class="card-custom-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Events</p>
                            <h3 class="mb-0">{{ $stats['total_events'] }}</h3>
                            <small class="text-muted">All time</small>
                        </div>
                        <div class="stat-icon bg-primary">
                            <i class="bi bi-droplet"></i>
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
                            <p class="text-muted mb-1">Today's Events</p>
                            <h3 class="mb-0 text-info">{{ $stats['today_events'] }}</h3>
                            <small class="text-muted">Last 24 hours</small>
                        </div>
                        <div class="stat-icon bg-info">
                            <i class="bi bi-calendar-day"></i>
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
                            <p class="text-muted mb-1">Active Valves</p>
                            <h3 class="mb-0 text-success">{{ $stats['active_valves'] }}</h3>
                            <small class="text-muted">Currently running</small>
                        </div>
                        <div class="stat-icon bg-success">
                            <i class="bi bi-toggle-on"></i>
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
                            <p class="text-muted mb-1">Total Duration</p>
                            <h3 class="mb-0">{{ number_format($stats['total_duration'], 0) }} min</h3>
                            <small class="text-muted">Today</small>
                        </div>
                        <div class="stat-icon bg-warning">
                            <i class="bi bi-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Irrigation Sessions -->
    @if($activeIrrigation->isNotEmpty())
    <div class="alert alert-info mb-4">
        <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Active Irrigation Sessions</h6>
        <div class="row mt-3">
            @foreach($activeIrrigation as $active)
            <div class="col-md-4">
                <div class="card border-info">
                    <div class="card-body">
                        <h6>Node {{ $active->node_id }}</h6>
                        <p class="mb-1"><strong>Session:</strong> {{ $active->sesi_id_irrigate }}</p>
                        <p class="mb-1"><strong>Started:</strong> {{ \Carbon\Carbon::parse($active->waktu)->format('d/m/Y H:i') }}</p>
                        <p class="mb-1"><strong>Duration:</strong> {{ number_format($active->durasi_detik / 60, 1) }} min</p>
                        @if($active->volume_air)
                        <p class="mb-0"><strong>Volume:</strong> {{ number_format($active->volume_air, 2) }} L</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Irrigation Logs -->
    <div class="card-custom">
        <div class="card-custom-header">
            <h5 class="mb-0">Irrigation History</h5>
        </div>
        <div class="card-custom-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Session ID</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Success Nodes</th>
                            <th>Failed Nodes</th>
                            <th>Valves ON</th>
                            <th>Duration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($irrigationLogs as $log)
                        <tr>
                            <td><strong>{{ $log->sesi_id_irrigate }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($log->waktu_mulai)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($log->waktu_akhir)
                                    {{ \Carbon\Carbon::parse($log->waktu_akhir)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success">{{ $log->node_sukses ?? 0 }}</span>
                            </td>
                            <td>
                                @if($log->node_gagal > 0)
                                    <span class="badge bg-danger">{{ $log->node_gagal }}</span>
                                @else
                                    <span class="badge bg-secondary">0</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $log->valve_on_akhir ?? 0 }}</span>
                            </td>
                            <td>
                                @if($log->waktu_mulai && $log->waktu_akhir)
                                    @php
                                        $duration = \Carbon\Carbon::parse($log->waktu_mulai)->diffInMinutes(\Carbon\Carbon::parse($log->waktu_akhir));
                                    @endphp
                                    <span class="badge bg-primary">{{ $duration }} min</span>
                                @else
                                    <span class="badge bg-warning">Ongoing</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('irrigation.history', $log->sesi_id_irrigate) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mt-2">No irrigation logs found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $irrigationLogs->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Trigger Irrigation Modal -->
@if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
<div class="modal fade" id="triggerIrrigationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('irrigation.trigger') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-droplet-fill"></i> Trigger Irrigation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="node_id" class="form-label">Select Node</label>
                        <select name="node_id" id="node_id" class="form-select" required>
                            <option value="">-- Select Node --</option>
                            @foreach($nodes as $node)
                            <option value="{{ $node->node_id }}">
                                Node {{ $node->node_id }} - {{ $node->lokasi ?? 'N/A' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration (minutes)</label>
                        <input type="number" name="duration" id="duration" class="form-control" 
                               min="1" max="120" value="30" required>
                        <small class="text-muted">Recommended: 15-60 minutes</small>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Note:</strong> This will manually trigger irrigation for the selected node.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-droplet-fill"></i> Start Irrigation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
