@extends('layouts.app')

@section('title', 'JSON Backup')
@section('page-title', 'JSON Backup Data')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-custom-header">
                <h5 class="mb-0"><i class="bi bi-database me-2"></i>JSON Backup Data</h5>
                <div>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.json-backup.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
            
            <!-- Filter Form -->
            <div class="collapse" id="filterCollapse">
                <div class="card-custom-body border-bottom">
                    <form method="GET" action="{{ route('admin.json-backup.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Sesi ID</label>
                            <input type="number" name="sesi_id" class="form-control" value="{{ request('sesi_id') }}" placeholder="Sesi ID">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
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
                                <th>Data Size (KB)</th>
                                <th>Total Records</th>
                                <th>Node Completeness</th>
                                <th>Getdata Logs</th>
                                <th>Sensor Weather</th>
                                <th>Sensor Node</th>
                                <th>Backup Timestamp</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($backups as $backup)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $backup->id }}</span></td>
                                <td><strong>{{ $backup->sesi_id_getdata }}</strong></td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-file-earmark-code"></i> {{ number_format($backup->data_size_kb, 2) }} KB
                                    </span>
                                </td>
                                <td>{{ $backup->total_records }}</td>
                                <td><small class="text-muted">{{ $backup->node_completeness }}</small></td>
                                <td>{{ $backup->getdata_logs_count }}</td>
                                <td>{{ $backup->sensor_weather_count }}</td>
                                <td>{{ $backup->sensor_node_count }}</td>
                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($backup->backup_timestamp)->format('Y-m-d H:i:s') }}
                                    </small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.json-backup.show', $backup->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('admin.json-backup.destroy', $backup->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this backup?');">
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
                                    No backup data found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $backups->firstItem() ?? 0 }} to {{ $backups->lastItem() ?? 0 }} of {{ $backups->total() }} entries
                    </div>
                    <div>
                        {{ $backups->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
