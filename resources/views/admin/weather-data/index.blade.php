@extends('layouts.app')

@section('title', 'Weather Data')
@section('page-title', 'Weather Station Data (Node 65)')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-custom-header">
                <h5 class="mb-0"><i class="bi bi-cloud-sun me-2"></i>Weather Station Data</h5>
                <div>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.weather-data.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
            
            <!-- Filter Form -->
            <div class="collapse" id="filterCollapse">
                <div class="card-custom-body border-bottom">
                    <form method="GET" action="{{ route('admin.weather-data.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Sesi ID</label>
                            <input type="number" name="sesi_id" class="form-control" value="{{ request('sesi_id') }}" placeholder="Sesi ID">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Table -->
            <div class="card-custom-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sesi ID</th>
                                <th>Temp (°C)</th>
                                <th>Humidity (%)</th>
                                <th>Light (lux)</th>
                                <th>Wind (m/s)</th>
                                <th>Rain</th>
                                <th>Voltage</th>
                                <th>Current</th>
                                <th>Power</th>
                                <th>Received At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($weatherData as $data)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $data->id }}</span></td>
                                <td>{{ $data->sesi_id_getdata }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ number_format($data->temp_dht, 1) }}°C
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $data->humidity }}%;" aria-valuenow="{{ $data->humidity }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="text-muted small">{{ number_format($data->humidity, 0) }}%</span>
                                    </div>
                                </td>
                                <td>{{ number_format($data->light, 1) }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-wind"></i> {{ number_format($data->wind, 1) }}
                                    </span>
                                </td>
                                <td>
                                    @if($data->rain == 0)
                                        <span class="badge bg-success">No Rain</span>
                                    @else
                                        <span class="badge bg-primary">Raining</span>
                                    @endif
                                </td>
                                <td>{{ number_format($data->voltage, 2) }}V</td>
                                <td>{{ number_format($data->current, 1) }}mA</td>
                                <td>{{ number_format($data->power, 0) }}mW</td>
                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($data->received_at)->format('Y-m-d H:i:s') }}
                                    </small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.weather-data.show', $data->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
                                    <a href="{{ route('admin.weather-data.edit', $data->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endif
                                    @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('admin.weather-data.destroy', $data->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No weather data found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $weatherData->firstItem() ?? 0 }} to {{ $weatherData->lastItem() ?? 0 }} of {{ $weatherData->total() }} entries
                    </div>
                    <div>
                        {{ $weatherData->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
