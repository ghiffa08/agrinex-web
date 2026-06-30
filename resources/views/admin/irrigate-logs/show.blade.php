@extends('layouts.app')
@section('title', 'Irrigate Log Details')

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-2">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.irrigate-logs.index') }}">Irrigate Logs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail #{{ $log->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
            <a href="{{ route('admin.irrigate-logs.edit', $log->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
            @endif
            <a href="{{ route('admin.irrigate-logs.index') }}" class="btn btn-secondary">
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
                    <i class="bi bi-info-circle"></i> Informasi Sesi
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>ID</th>
                            <td><span class="badge bg-dark">{{ $log->id }}</span></td>
                        </tr>
                        <tr>
                            <th>Sesi ID Irigasi</th>
                            <td>{{ $log->sesi_id_irrigate ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Waktu Mulai</th>
                            <td>{{ $log->waktu_mulai ? $log->waktu_mulai->format('d-m-Y H:i:s') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Waktu Akhir</th>
                            <td>{{ $log->waktu_akhir ? $log->waktu_akhir->format('d-m-Y H:i:s') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-bar-chart"></i> Statistik
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Node Sukses</th>
                            <td><span class="badge bg-success">{{ $log->node_sukses ?? 0 }}</span></td>
                        </tr>
                        <tr>
                            <th>Node Gagal</th>
                            <td><span class="badge bg-danger">{{ $log->node_gagal ?? 0 }}</span></td>
                        </tr>
                        <tr>
                            <th>Valve ON Akhir</th>
                            <td>{{ $log->valve_on_akhir ?? '-' }}</td>
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
            <form action="{{ route('admin.irrigate-logs.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Hapus log irigasi ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Hapus Log Irigasi
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
