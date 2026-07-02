@extends('layouts.app')

@section('title', 'Node Logs')
@section('page-title', 'Node Communication Logs')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-custom-header">
                <h5 class="mb-0"><i class="bi bi-hdd-network me-2"></i>Node Communication Logs</h5>
                <div>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.node-logs.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
            
            <!-- Filter Form -->
            <div class="collapse" id="filterCollapse">
                <div class="card-custom-body border-bottom">
                    <form method="GET" action="{{ route('admin.node-logs.index') }}" class="row g-3">
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
                            <label class="form-label">Type Sesi</label>
                            <select name="type_sesi" class="form-select">
                                <option value="">All Types</option>
                                <option value="getdata" {{ request('type_sesi') == 'getdata' ? 'selected' : '' }}>Getdata</option>
                                <option value="irrigate" {{ request('type_sesi') == 'irrigate' ? 'selected' : '' }}>Irrigate</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Non Aktif" {{ request('status') == 'Non Aktif' ? 'selected' : '' }}>Non Aktif</option>
                            </select>
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
                                <th>Type</th>
                                <th>RSSI (dBm)</th>
                                <th>SNR (dB)</th>
                                <th>Signal Quality</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Waktu</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $log->id }}</span></td>
                                <td>{{ $log->sesi_id }}</td>
                                <td>
                                    <span class="badge {{ $log->node_id == 65 ? 'bg-warning text-dark' : 'bg-primary' }}">
                                        Node {{ $log->node_id }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $log->type_sesi == 'getdata' ? 'bg-info' : 'bg-success' }} text-dark">
                                        {{ $log->type_sesi }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: {{ $log->rssi_dbm > -70 ? '#10b981' : ($log->rssi_dbm > -85 ? '#f59e0b' : '#ef4444') }};">
                                        {{ $log->rssi_dbm }}
                                    </span>
                                </td>
                                <td>{{ number_format($log->snr_db, 1) }}</td>
                                <td>
                                    @php
                                        $quality = strtolower($log->signal_quality ?? '');
                                        $color = 'secondary';
                                        if(str_contains($quality, 'excellent')) $color = 'success';
                                        elseif(str_contains($quality, 'good')) $color = 'info';
                                        elseif(str_contains($quality, 'fair')) $color = 'warning';
                                        elseif(str_contains($quality, 'poor')) $color = 'danger';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ $log->signal_quality }}</span>
                                </td>
                                <td>
                                    @if($log->status == 'Aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Non Aktif</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $log->keterangan }}</small></td>
                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($log->waktu)->format('Y-m-d H:i:s') }}
                                    </small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.node-logs.show', $log->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
                                    <a href="{{ route('admin.node-logs.edit', $log->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endif
                                    @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('admin.node-logs.destroy', $log->id) }}" method="POST" class="d-inline"
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
                                <td colspan="11" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No node logs found
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
