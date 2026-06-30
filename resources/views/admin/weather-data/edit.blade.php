@extends('layouts.app')

@section('title', 'Edit Weather Data')
@section('page-title', 'Edit Weather Station Data')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.weather-data.index') }}">Weather Data</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.weather-data.show', $data->id) }}">Details #{{ $data->id }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
            <a href="{{ route('admin.weather-data.show', $data->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-x"></i> Cancel
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card-custom">
                    <div class="card-custom-header">
                        <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Weather Data</h5>
                    </div>
                    <div class="card-custom-body">
                        <form action="{{ route('admin.weather-data.update', $data->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Weather Measurements -->
                            <h6 class="mb-3"><i class="bi bi-cloud-sun me-2"></i>Weather Measurements</h6>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Temperature (°C) *</label>
                                    <input type="number" step="0.1" class="form-control @error('temp_dht') is-invalid @enderror" 
                                           name="temp_dht" value="{{ old('temp_dht', $data->temp_dht) }}" required>
                                    @error('temp_dht')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Humidity (%) *</label>
                                    <input type="number" step="0.1" class="form-control @error('humidity') is-invalid @enderror" 
                                           name="humidity" value="{{ old('humidity', $data->humidity) }}" min="0" max="100" required>
                                    @error('humidity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label">Light (lux) *</label>
                                    <input type="number" step="0.1" class="form-control @error('light') is-invalid @enderror" 
                                           name="light" value="{{ old('light', $data->light) }}" required>
                                    @error('light')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Wind Speed (m/s) *</label>
                                    <input type="number" step="0.1" class="form-control @error('wind') is-invalid @enderror" 
                                           name="wind" value="{{ old('wind', $data->wind) }}" required>
                                    @error('wind')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Rain Status *</label>
                                    <select class="form-select @error('rain') is-invalid @enderror" name="rain" required>
                                        <option value="0" {{ old('rain', $data->rain) == 0 ? 'selected' : '' }}>No Rain</option>
                                        <option value="1" {{ old('rain', $data->rain) == 1 ? 'selected' : '' }}>Raining</option>
                                    </select>
                                    @error('rain')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Power Measurements -->
                            <h6 class="mb-3"><i class="bi bi-lightning me-2"></i>Power Measurements</h6>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label">Voltage (V) *</label>
                                    <input type="number" step="0.01" class="form-control @error('voltage') is-invalid @enderror" 
                                           name="voltage" value="{{ old('voltage', $data->voltage) }}" required>
                                    @error('voltage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Current (mA) *</label>
                                    <input type="number" step="0.1" class="form-control @error('current') is-invalid @enderror" 
                                           name="current" value="{{ old('current', $data->current) }}" required>
                                    @error('current')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Power (mW) *</label>
                                    <input type="number" step="0.1" class="form-control @error('power') is-invalid @enderror" 
                                           name="power" value="{{ old('power', $data->power) }}" required>
                                    @error('power')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Save Changes
                                </button>
                                <a href="{{ route('admin.weather-data.show', $data->id) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-custom-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Information</h5>
                    </div>
                    <div class="card-custom-body">
                        <p><strong>ID:</strong> {{ $data->id }}</p>
                        <p><strong>Sesi ID:</strong> {{ $data->sesi_id_getdata }}</p>
                        <p><strong>Received:</strong><br>{{ \Carbon\Carbon::parse($data->received_at)->format('Y-m-d H:i:s') }}</p>
                        <p class="mb-0"><strong>Created:</strong><br>{{ $data->created_at ? $data->created_at->format('Y-m-d H:i:s') : '-' }}</p>
                    </div>
                </div>

                <div class="card-custom mt-3">
                    <div class="card-custom-header">
                        <h5 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Tips</h5>
                    </div>
                    <div class="card-custom-body">
                        <ul class="mb-0 ps-3">
                            <li>Temperature in Celsius (°C)</li>
                            <li>Humidity: 0-100%</li>
                            <li>Light in lux units</li>
                            <li>Wind speed in meters/second</li>
                            <li>Rain: 0=No rain, 1=Raining</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
