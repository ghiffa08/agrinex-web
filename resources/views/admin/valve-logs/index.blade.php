@extends('layouts.app')

@section('title', 'Valve Logs')
@section('page-title', 'Valve Operation Logs')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-custom-header">
                <h5 class="mb-0"><i class="bi bi-toggle-on me-2"></i>Valve Operation Logs</h5>
                <div>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.valve-logs.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
            
            <!-- Filter Form -->
            <div class="collapse" id="filterCollapse">
                <div class="card-custom-body border-bottom">
                    <form method="GET" action="{{ route('admin.valve-logs.index') }}" class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Node</label>
                            <select name="node_id" class="form-select">
                                <option value="">All Nodes</option>
                                @foreach($nodes as $node)
                                    <option value="{{ $node->id }}" {{ request('node_id') == $node->id ? 'selected' : '' }}>
                                        Node {{ $node->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="ON" {{ request('status') == 'ON' ? 'selected' : '' }}>ON</option>
                                <option value="OFF" {{ request('status') == 'OFF' ? 'selected' : '' }}>OFF</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sesi ID</label>
                            <input type="number" name="sesi_id" class="form-control" value="{{ request('sesi_id') }}" placeholder="Sesi ID">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Table -->
            <div class="card-custom-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sesi ID</th>
                                <th>Node</th>
                                <th>Status</th>
                                <th>Duration (s)</th>
                                <th>Volume (mL)</th>
                                <th>Flow Rate (mL/s)</th>
                                <th>Pulses</th>
                                <th>Waktu</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $log->id }}</span></td>
                                <td>{{ $log->sesi_id_irrigate }}</td>
                                <td>
                                    <span class="badge bg-primary">Node {{ $log->node_id }}</span>
                                </td>
                                <td>
                                    @if($log->status == 'ON')
                                        <span class="badge bg-success">
                                            <i class="bi bi-toggle-on"></i> ON
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-toggle-off"></i> OFF
                                        </span>
                                    @endif
                                </td>
                                <td>{{ number_format($log->durasi_detik, 0) }}s</td>
                                <td>
                                    @if($log->volume_air)
                                        <span class="badge bg-info text-dark">
                                            <i class="bi bi-droplet-fill"></i> {{ number_format($log->volume_air, 2) }} mL
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->rata_rata)
                                        {{ number_format($log->rata_rata, 2) }} mL/s
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ number_format($log->pulse, 0) }}</td>
                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($log->waktu)->format('Y-m-d H:i:s') }}
                                    </small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.valve-logs.show', $log->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
                                    <a href="{{ route('admin.valve-logs.edit', $log->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endif
                                    @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('admin.valve-logs.destroy', $log->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No valve logs found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} entries
                    </div>
                    <div>
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
