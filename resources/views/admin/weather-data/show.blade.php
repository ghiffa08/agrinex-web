@extends('layouts.app')

@section('title', 'Weather Data Details')
@section('page-title', 'Weather Station Data Details')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.weather-data.index') }}">Weather Data</a></li>
                    <li class="breadcrumb-item active">Details #{{ $data->id }}</li>
                </ol>
            </nav>
            <div>
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
                <a href="{{ route('admin.weather-data.edit', $data->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                @endif
                <a href="{{ route('admin.weather-data.index') }}" class="btn btn-outline-secondary btn-sm">
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
                                <th>Received At:</th>
                                <td>{{ \Carbon\Carbon::parse($data->received_at)->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Created At:</th>
                                <td>{{ $data->created_at ? $data->created_at->format('Y-m-d H:i:s') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Updated At:</th>
                                <td>{{ $data->updated_at ? $data->updated_at->format('Y-m-d H:i:s') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Weather Measurements -->
            <div class="col-md-6">
                <div class="card-custom">
                    <div class="card-custom-header">
                        <h5 class="mb-0"><i class="bi bi-cloud-sun me-2"></i>Weather Measurements</h5>
                    </div>
                    <div class="card-custom-body">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Temperature:</th>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-thermometer-half"></i> {{ number_format($data->temp_dht, 1) }}°C
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Humidity:</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $data->humidity }}%;">
                                                {{ number_format($data->humidity, 0) }}%
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Light:</th>
                                <td>
                                    <i class="bi bi-sun"></i> {{ number_format($data->light, 1) }} lux
                                </td>
                            </tr>
                            <tr>
                                <th>Wind Speed:</th>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-wind"></i> {{ number_format($data->wind, 1) }} m/s
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Rain Status:</th>
                                <td>
                                    @if($data->rain == 0)
                                        <span class="badge bg-success">
                                            <i class="bi bi-sun"></i> No Rain
                                        </span>
                                    @else
                                        <span class="badge bg-primary">
                                            <i class="bi bi-cloud-rain"></i> Raining
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Power Measurements -->
            <div class="col-md-12">
                <div class="card-custom">
                    <div class="card-custom-header">
                        <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Power Measurements</h5>
                    </div>
                    <div class="card-custom-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="p-3">
                                    <i class="bi bi-battery-charging text-success fs-1"></i>
                                    <h4 class="mt-2 mb-0">{{ number_format($data->voltage, 2) }}V</h4>
                                    <p class="text-muted mb-0">Voltage</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3">
                                    <i class="bi bi-arrow-up-circle text-info fs-1"></i>
                                    <h4 class="mt-2 mb-0">{{ number_format($data->current, 1) }}mA</h4>
                                    <p class="text-muted mb-0">Current</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3">
                                    <i class="bi bi-lightning-charge text-warning fs-1"></i>
                                    <h4 class="mt-2 mb-0">{{ number_format($data->power, 0) }}mW</h4>
                                    <p class="text-muted mb-0">Power</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if(auth()->user()->role === 'admin')
            <div class="col-md-12">
                <div class="card-custom border-danger">
                    <div class="card-custom-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone</h5>
                    </div>
                    <div class="card-custom-body">
                        <p class="mb-3">Once you delete this weather data record, there is no going back. Please be certain.</p>
                        <form action="{{ route('admin.weather-data.destroy', $data->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this weather data record?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Delete Weather Data
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
