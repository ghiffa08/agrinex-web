@extends('layouts.app')
@section('title', 'Edit Sensor Node Data')

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-2">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.sensor-node-data.index') }}">Sensor Node Data</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.sensor-node-data.show', $data->id) }}">Detail #{{ $data->id }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.sensor-node-data.show', $data->id) }}" class="btn btn-secondary">
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
                    <i class="bi bi-pencil"></i> Edit Sensor Node Data
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sensor-node-data.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="voltage_v" class="form-label">Voltage (V)</label>
                            <input type="number" step="0.01" class="form-control" id="voltage_v" name="voltage_v" value="{{ old('voltage_v', $data->voltage_v) }}">
                        </div>
                        <div class="mb-3">
                            <label for="current_ma" class="form-label">Current (mA)</label>
                            <input type="number" step="0.01" class="form-control" id="current_ma" name="current_ma" value="{{ old('current_ma', $data->current_ma) }}">
                        </div>
                        <div class="mb-3">
                            <label for="power_mw" class="form-label">Power (mW)</label>
                            <input type="number" step="0.01" class="form-control" id="power_mw" name="power_mw" value="{{ old('power_mw', $data->power_mw) }}">
                        </div>
                        <div class="mb-3">
                            <label for="temp_c" class="form-label">Temperature (°C)</label>
                            <input type="number" step="0.01" class="form-control" id="temp_c" name="temp_c" value="{{ old('temp_c', $data->temp_c) }}">
                        </div>
                        <div class="mb-3">
                            <label for="soil_pct" class="form-label">Soil Moisture (%)</label>
                            <input type="number" step="0.01" class="form-control" id="soil_pct" name="soil_pct" value="{{ old('soil_pct', $data->soil_pct) }}">
                        </div>
                        <div class="mb-3">
                            <label for="soil_adc" class="form-label">Soil ADC</label>
                            <input type="number" class="form-control" id="soil_adc" name="soil_adc" value="{{ old('soil_adc', $data->soil_adc) }}">
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.sensor-node-data.show', $data->id) }}" class="btn btn-secondary ms-2">Batal</a>
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
                    <p><strong>ID:</strong> <span class="badge bg-dark">{{ $data->id }}</span></p>
                    <p><strong>Node:</strong> <span class="badge bg-info">{{ $data->node_id }}</span></p>
                    @if($data->node && $data->node->location)
                        <p><strong>Lokasi:</strong> {{ $data->node->location }}</p>
                    @endif
                    <p><strong>Waktu Diterima:</strong> {{ $data->received_at ? $data->received_at->format('d-m-Y H:i:s') : '-' }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-light">
                    <i class="bi bi-lightbulb"></i> Tips
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Pastikan nilai sensor sesuai hasil monitoring lapangan.</li>
                        <li>Soil ADC dapat dikosongkan jika tidak tersedia.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
