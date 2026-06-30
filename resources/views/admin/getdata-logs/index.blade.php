@extends('layouts.app')

@section('title', 'Getdata Logs')
@section('page-title', 'Getdata Session Logs')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-custom-header">
                <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Getdata Session Logs</h5>
                <div>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.getdata-logs.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
            
            <!-- Filter Form -->
            <div class="collapse" id="filterCollapse">
                <div class="card-custom-body border-bottom">
                    <form method="GET" action="{{ route('admin.getdata-logs.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <input type="text" name="status" class="form-control" value="{{ request('status') }}" placeholder="Status">
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
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                                <th>Node Sukses</th>
                                <th>Node Gagal</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $log->id }}</span></td>
                                <td><strong>{{ $log->sesi_id_getdata }}</strong></td>
                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($log->waktu_mulai)->format('Y-m-d H:i:s') }}
                                    </small>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $log->waktu_selesai ? \Carbon\Carbon::parse($log->waktu_selesai)->format('Y-m-d H:i:s') : '-' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $log->node_sukses }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger">{{ $log->node_gagal }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark">{{ $log->status }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $log->keterangan }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.getdata-logs.show', $log->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
                                    <a href="{{ route('admin.getdata-logs.edit', $log->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endif
                                    @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('admin.getdata-logs.destroy', $log->id) }}" method="POST" class="d-inline"
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
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No logs found
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
