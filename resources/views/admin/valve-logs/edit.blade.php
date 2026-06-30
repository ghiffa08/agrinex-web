@extends('layouts.app')
@section('title', 'Edit Valve Log')

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-2">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.valve-logs.index') }}">Valve Logs</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.valve-logs.show', $log->id) }}">Detail #{{ $log->id }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.valve-logs.show', $log->id) }}" class="btn btn-secondary">
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
                    <i class="bi bi-pencil"></i> Edit Valve Log
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.valve-logs.update', $log->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="ON" {{ old('status', $log->status) == 'ON' ? 'selected' : '' }}>ON</option>
                                <option value="OFF" {{ old('status', $log->status) == 'OFF' ? 'selected' : '' }}>OFF</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="durasi_detik" class="form-label">Durasi (detik)</label>
                            <input type="number" class="form-control" id="durasi_detik" name="durasi_detik" min="0" value="{{ old('durasi_detik', $log->durasi_detik) }}">
                        </div>
                        <div class="mb-3">
                            <label for="volume_air" class="form-label">Volume Air (L)</label>
                            <input type="number" step="0.01" class="form-control" id="volume_air" name="volume_air" min="0" value="{{ old('volume_air', $log->volume_air) }}">
                        </div>
                        <div class="mb-3">
                            <label for="rata_rata" class="form-label">Rata-rata (L/menit)</label>
                            <input type="number" step="0.01" class="form-control" id="rata_rata" name="rata_rata" min="0" value="{{ old('rata_rata', $log->rata_rata) }}">
                        </div>
                        <div class="mb-3">
                            <label for="pulse" class="form-label">Pulse</label>
                            <input type="number" class="form-control" id="pulse" name="pulse" min="0" value="{{ old('pulse', $log->pulse) }}">
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.valve-logs.show', $log->id) }}" class="btn btn-secondary ms-2">Batal</a>
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
                        <li>Pastikan status dan durasi sesuai dengan data lapangan.</li>
                        <li>Volume air dan rata-rata dapat dikosongkan jika tidak tersedia.</li>
                        <li>Pulse diisi sesuai hasil monitoring.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
