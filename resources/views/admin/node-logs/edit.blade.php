@extends('layouts.app')
@section('title', 'Edit Node Log')

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-2">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.node-logs.index') }}">Node Logs</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.node-logs.show', $log->id) }}">Detail #{{ $log->id }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.node-logs.show', $log->id) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Batal
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-pencil"></i> Edit Node Log
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.node-logs.update', $log->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="rssi_dbm" class="form-label">RSSI (dBm)</label>
                            <input type="number" step="0.01" class="form-control" id="rssi_dbm" name="rssi_dbm" value="{{ old('rssi_dbm', $log->rssi_dbm) }}">
                        </div>
                        <div class="mb-3">
                            <label for="snr_db" class="form-label">SNR (dB)</label>
                            <input type="number" step="0.01" class="form-control" id="snr_db" name="snr_db" value="{{ old('snr_db', $log->snr_db) }}">
                        </div>
                        <div class="mb-3">
                            <label for="signal_quality" class="form-label">Signal Quality</label>
                            <input type="text" maxlength="20" class="form-control" id="signal_quality" name="signal_quality" value="{{ old('signal_quality', $log->signal_quality) }}">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Aktif" {{ old('status', $log->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Non Aktif" {{ old('status', $log->status) == 'Non Aktif' ? 'selected' : '' }}>Non Aktif</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2">{{ old('keterangan', $log->keterangan) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.node-logs.show', $log->id) }}" class="btn btn-secondary ms-2">Batal</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-info-circle"></i> Info Node
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> <span class="badge bg-dark">{{ $log->id }}</span></p>
                    <p><strong>Node:</strong> <span class="badge bg-info">{{ $log->node_id }}</span></p>
                    @if($log->node && $log->node->location)
                        <p><strong>Lokasi:</strong> {{ $log->node->location }}</p>
                    @endif
                    <p><strong>Waktu:</strong> {{ $log->waktu ? $log->waktu->format('d-m-Y H:i:s') : '-' }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-light">
                    <i class="bi bi-lightbulb"></i> Tips
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Pastikan nilai RSSI dan SNR sesuai hasil monitoring lapangan.</li>
                        <li>Status "Non Aktif" hanya untuk node yang benar-benar tidak aktif.</li>
                        <li>Signal Quality dapat dikosongkan jika tidak diketahui.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
