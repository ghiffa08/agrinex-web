@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-file-earmark-bar-graph"></i> Reports & Analytics</h4>
            <p class="text-muted mb-0">Generate and export system reports</p>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="card-custom mb-4">
        <div class="card-custom-body">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" 
                           value="{{ request('start_date', now()->subDays(7)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" 
                           value="{{ request('end_date', now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel"></i> Apply Filter
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
                <div class="col-md-3 text-end">
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-download"></i> Export Data
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('reports.export', ['type' => 'sensor', 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}">
                                    <i class="bi bi-file-earmark-spreadsheet"></i> Sensor Data CSV
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('reports.export', ['type' => 'irrigation', 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}">
                                    <i class="bi bi-file-earmark-spreadsheet"></i> Irrigation Data CSV
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row g-3 mb-4">
        <!-- Irrigation Statistics -->
        <div class="col-md-6">
            <div class="card-custom">
                <div class="card-custom-header">
                    <h5 class="mb-0"><i class="bi bi-water"></i> Irrigation Summary</h5>
                </div>
                <div class="card-custom-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <h3 class="text-primary mb-0">{{ $irrigationStats['total_events'] }}</h3>
                                <small class="text-muted">Total Events</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <h3 class="text-info mb-0">{{ number_format($irrigationStats['total_duration'], 0) }}</h3>
                                <small class="text-muted">Total Duration (min)</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-center">
                                <h3 class="text-success mb-0">N/A</h3>
                                <small class="text-muted">Average Duration (min)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sensor Statistics -->
        <div class="col-md-6">
            <div class="card-custom">
                <div class="card-custom-header">
                    <h5 class="mb-0"><i class="bi bi-thermometer-half"></i> Sensor Data Summary</h5>
                </div>
                <div class="card-custom-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <h3 class="text-primary mb-0">{{ number_format($sensorStats['total_readings']) }}</h3>
                                <small class="text-muted">Total Readings</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <h3 class="text-success mb-0">{{ number_format($sensorStats['avg_moisture'], 1) }}%</h3>
                                <small class="text-muted">Avg Soil Moisture</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-danger mb-0">{{ number_format($sensorStats['avg_temp'], 1) }}°C</h3>
                                <small class="text-muted">Avg Temperature</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-warning mb-0">{{ number_format($sensorStats['min_moisture'], 1) }}%</h3>
                                <small class="text-muted">Min Moisture</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Node Activity -->
    <div class="card-custom mb-4">
        <div class="card-custom-header">
            <h5 class="mb-0">Node Activity</h5>
        </div>
        <div class="card-custom-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Node ID</th>
                            <th>Group</th>
                            <th>Location</th>
                            <th>Total Readings</th>
                            <th>Avg Moisture</th>
                            <th>Avg Temperature</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nodeActivity as $activity)
                        <tr>
                            <td><strong>Device {{ $activity->device_id }}</strong></td>
                            <td>{{ $activity->device->group ?? '-' }}</td>
                            <td>{{ $activity->device->lokasi ?? '-' }}</td>
                            <td>{{ number_format($activity->reading_count) }}</td>
                            <td>
                                <span class="badge {{ $activity->avg_moisture < 30 ? 'bg-danger' : 'bg-success' }}">
                                    {{ number_format($activity->avg_moisture, 1) }}%
                                </span>
                            </td>
                            <td>{{ number_format($activity->avg_temp, 1) }}°C</td>
                            <td>
                                <a href="{{ route('reports.by-node', $activity->device_id) }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-text"></i> Detailed Report
                                </a>
                                <a href="{{ route('nodes.show', $activity->device_id) }}" 
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i> View Node
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No data available for the selected period
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Daily Summary -->
    <div class="card-custom">
        <div class="card-custom-header">
            <h5 class="mb-0">Daily Summary</h5>
        </div>
        <div class="card-custom-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Total Readings</th>
                            <th>Avg Moisture</th>
                            <th>Avg Temperature</th>
                            <th>Min Moisture</th>
                            <th>Max Temperature</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dailySummary as $day)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</td>
                            <td>{{ number_format($day->total_readings) }}</td>
                            <td>
                                <span class="badge {{ $day->avg_moisture < 30 ? 'bg-danger' : 'bg-success' }}">
                                    {{ number_format($day->avg_moisture, 1) }}%
                                </span>
                            </td>
                            <td>{{ number_format($day->avg_temp, 1) }}°C</td>
                            <td>{{ number_format($day->min_moisture, 1) }}%</td>
                            <td>
                                <span class="badge {{ $day->max_temp > 35 ? 'bg-warning' : 'bg-info' }}">
                                    {{ number_format($day->max_temp, 1) }}°C
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No data available for the selected period
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
