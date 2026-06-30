@extends('layouts.app')

@section('title', 'Sensor Node Data Details')
@section('page-title', 'Sensor Node Data Details')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.sensor-node-data.index') }}">Sensor Node Data</a></li>
                    <li class="breadcrumb-item active">Details #{{ $data->id }}</li>
                </ol>
            </nav>
            <div>
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
                <a href="{{ route('admin.sensor-node-data.edit', $data->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                @endif
                <a href="{{ route('admin.sensor-node-data.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row g-4">
            <!-- Basic Information -->
            <div class="col-md-6">
                <div class="card-custom">
                    <div class="card-custom-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                    </div>
                    <div class="card-custom-body">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">ID:</th>
                                <td><span class="badge bg-secondary">{{ $data->id }}</span></td>
                            </tr>
                            <tr>
                                <th>Sesi ID:</th>
                                <td><strong>{{ $data->sesi_id_getdata }}</strong></td>
                            </tr>
                            <tr>
                                <th>Node:</th>
                                <td>
                                    <span class="badge bg-primary">Node {{ $data->node_id }}</span>
                                    @if($data->node)
                                        <br><small class="text-muted">{{ $data->node->lokasi }}</small>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Received At:</th>
                                <td>{{ \Carbon\Carbon::parse($data->received_at)->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Power Measurements -->
            <div class="col-md-6">
                <div class="card-custom">
                    <div class="card-custom-header">
                        <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Power Measurements</h5>
                    </div>
                    <div class="card-custom-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <i class="bi bi-battery-charging text-success fs-1"></i>
                                <h4 class="mt-2 mb-0">{{ number_format($data->voltage_v, 2) }}V</h4>
                                <p class="text-muted mb-0 small">Voltage</p>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-arrow-up-circle text-info fs-1"></i>
                                <h4 class="mt-2 mb-0">{{ number_format($data->current_ma, 1) }}mA</h4>
                                <p class="text-muted mb-0 small">Current</p>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-lightning-charge text-warning fs-1"></i>
                                <h4 class="mt-2 mb-0">{{ number_format($data->power_mw, 0) }}mW</h4>
                                <p class="text-muted mb-0 small">Power</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sensor Readings -->
            <div class="col-md-12">
                <div class="card-custom">
                    <div class="card-custom-header">
                        <h5 class="mb-0"><i class="bi bi-thermometer-half me-2"></i>Sensor Readings</h5>
                    </div>
                    <div class="card-custom-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="text-muted">Temperature</h6>
                                <h3>
                                    <span class="badge" style="background-color: {{ $data->temp_c > 30 ? '#fecaca' : '#bfdbfe' }}; color: {{ $data->temp_c > 30 ? '#991b1b' : '#1e40af' }};">
                                        <i class="bi bi-thermometer-half"></i> {{ number_format($data->temp_c, 1) }}°C
                                    </span>
                                </h3>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-muted">Soil Moisture</h6>
                                <h3>
                                    <span class="badge" style="background-color: {{ $data->soil_pct < 30 ? '#fecaca' : ($data->soil_pct < 60 ? '#fef3c7' : '#d1fae5') }}; color: {{ $data->soil_pct < 30 ? '#991b1b' : ($data->soil_pct < 60 ? '#92400e' : '#065f46') }};">
                                        <i class="bi bi-droplet-fill"></i> {{ number_format($data->soil_pct, 1) }}%
                                    </span>
                                </h3>
                                <div class="progress mt-2" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ $data->soil_pct }}%; background-color: {{ $data->soil_pct < 30 ? '#ef4444' : ($data->soil_pct < 60 ? '#f59e0b' : '#10b981') }};">
                                        {{ number_format($data->soil_pct, 0) }}%
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-muted">Soil ADC Value</h6>
                                <h3><span class="badge bg-secondary">{{ $data->soil_adc }}</span></h3>
                                <small class="text-muted">Raw ADC reading</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            @if(auth()->user()->role === 'admin')
            <div class="col-md-12">
                <div class="card-custom border-danger">
                    <div class="card-custom-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone</h5>
                    </div>
                    <div class="card-custom-body">
                        <p class="mb-3">Once you delete this sensor data record, there is no going back. Please be certain.</p>
                        <form action="{{ route('admin.sensor-node-data.destroy', $data->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this sensor data record?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Delete Sensor Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
