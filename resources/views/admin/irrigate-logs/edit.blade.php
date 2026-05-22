@extends('layouts.app')
@section('title', 'Edit Irrigate Log')

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-2">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.irrigate-logs.index') }}">Irrigate Logs</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.irrigate-logs.show', $log->id) }}">Detail #{{ $log->id }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.irrigate-logs.show', $log->id) }}" class="btn btn-secondary">
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
                    <i class="bi bi-pencil"></i> Edit Irrigate Log
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.irrigate-logs.update', $log->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="node_sukses" class="form-label">Node Sukses</label>
                            <input type="number" class="form-control" id="node_sukses" name="node_sukses" min="0" value="{{ old('node_sukses', $log->node_sukses) }}">
                        </div>
                        <div class="mb-3">
                            <label for="node_gagal" class="form-label">Node Gagal</label>
                            <input type="number" class="form-control" id="node_gagal" name="node_gagal" min="0" value="{{ old('node_gagal', $log->node_gagal) }}">
                        </div>
                        <div class="mb-3">
                            <label for="valve_on_akhir" class="form-label">Valve ON Akhir</label>
                            <input type="number" class="form-control" id="valve_on_akhir" name="valve_on_akhir" min="0" value="{{ old('valve_on_akhir', $log->valve_on_akhir) }}">
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.irrigate-logs.show', $log->id) }}" class="btn btn-secondary ms-2">Batal</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-info-circle"></i> Info Sesi
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> <span class="badge bg-dark">{{ $log->id }}</span></p>
                    <p><strong>Sesi ID Irigasi:</strong> {{ $log->sesi_id_irrigate ?? '-' }}</p>
                    <p><strong>Waktu Mulai:</strong> {{ $log->waktu_mulai ? $log->waktu_mulai->format('d-m-Y H:i:s') : '-' }}</p>
                    <p><strong>Waktu Akhir:</strong> {{ $log->waktu_akhir ? $log->waktu_akhir->format('d-m-Y H:i:s') : '-' }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-light">
                    <i class="bi bi-lightbulb"></i> Tips
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Pastikan jumlah node sukses/gagal sesuai hasil monitoring.</li>
                        <li>Valve ON Akhir diisi sesuai hasil akhir irigasi.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
