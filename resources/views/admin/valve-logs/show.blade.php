@extends('layouts.app')
@section('title', 'Valve Log Details')

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-2">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.valve-logs.index') }}">Valve Logs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail #{{ $log->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
            <a href="{{ route('admin.valve-logs.edit', $log->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
            @endif
            <a href="{{ route('admin.valve-logs.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-info-circle"></i> Informasi Dasar
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>ID</th>
                            <td><span class="badge bg-dark">{{ $log->id }}</span></td>
                        </tr>
                        <tr>
                            <th>Node</th>
                            <td>
                                <span class="badge bg-info">{{ $log->node_id }}</span>
                                @if($log->node && $log->node->location)
                                    <span class="text-muted">({{ $log->node->location }})</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Sesi ID Irigasi</th>
                            <td>{{ $log->sesi_id_irrigate ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Waktu</th>
                            <td>{{ $log->waktu ? $log->waktu->format('d-m-Y H:i:s') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-gear"></i> Detail Operasi Valve
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge {{ $log->status === 'ON' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $log->status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Durasi (detik)</th>
                            <td>{{ $log->durasi_detik ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Volume Air (L)</th>
                            <td>{{ $log->volume_air ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Rata-rata (L/menit)</th>
                            <td>{{ $log->rata_rata ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Pulse</th>
                            <td>{{ $log->pulse ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->role === 'admin')
    <div class="card border-danger mt-4">
        <div class="card-header bg-danger text-white">
            <i class="bi bi-exclamation-triangle"></i> Danger Zone
        </div>
        <div class="card-body">
            <form action="{{ route('admin.valve-logs.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Hapus log valve ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Hapus Log Valve
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
