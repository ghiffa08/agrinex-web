@extends('layouts.app')
@section('title', 'Node Log Details')

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-2">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.node-logs.index') }}">Node Logs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail #{{ $log->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
            <a href="{{ route('admin.node-logs.edit', $log->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
            @endif
            <a href="{{ route('admin.node-logs.index') }}" class="btn btn-secondary">
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
                            <th>Status</th>
                            <td>
                                <span class="badge {{ $log->status === 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $log->status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Waktu</th>
                            <td>{{ $log->waktu ? $log->waktu->format('d-m-Y H:i:s') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Type Sesi</th>
                            <td>{{ $log->type_sesi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Sesi ID</th>
                            <td>{{ $log->sesi_id ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-broadcast"></i> Komunikasi & Sinyal
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>RSSI (dBm)</th>
                            <td>
                                <span class="badge bg-{{ $log->rssi_dbm > -80 ? 'success' : ($log->rssi_dbm > -100 ? 'warning' : 'danger') }}">
                                    {{ $log->rssi_dbm ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>SNR (dB)</th>
                            <td>
                                <span class="badge bg-{{ $log->snr_db > 5 ? 'success' : ($log->snr_db > 0 ? 'warning' : 'danger') }}">
                                    {{ $log->snr_db ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Signal Quality</th>
                            <td>
                                <span class="badge bg-info">{{ $log->signal_quality ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $log->keterangan ?? '-' }}</td>
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
            <form action="{{ route('admin.node-logs.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Hapus log node ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Hapus Log Node
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
