@extends('layouts.app')

@section('title', 'Sensor Node Data')
@section('page-title', 'Sensor Node Data')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-custom-header">
                <h5 class="mb-0"><i class="bi bi-thermometer-half me-2"></i>Sensor Node Data</h5>
                <div>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.sensor-node-data.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
            
            <!-- Filter Form -->
            <div class="collapse" id="filterCollapse">
                <div class="card-custom-body border-bottom">
                    <form method="GET" action="{{ route('admin.sensor-node-data.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Node</label>
                            <select name="node_id" class="form-select">
                                <option value="">All Nodes</option>
                                @foreach($nodes as $node)
                                    <option value="{{ $node->id }}" {{ request('node_id') == $node->id ? 'selected' : '' }}>
                                        Node {{ $node->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sesi ID</label>
                            <input type="number" name="sesi_id" class="form-control" value="{{ request('sesi_id') }}" placeholder="Sesi ID">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
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
                                <th>Node</th>
                                <th>Voltage (V)</th>
                                <th>Current (mA)</th>
                                <th>Power (mW)</th>
                                <th>Temp (°C)</th>
                                <th>Soil (%)</th>
                                <th>Soil ADC</th>
                                <th>Received At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sensorData as $data)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $data->id }}</span></td>
                                <td>{{ $data->sesi_id_getdata }}</td>
                                <td>
                                    <span class="badge bg-primary">Node {{ $data->node_id }}</span>
                                </td>
                                <td>{{ number_format($data->voltage_v, 2) }}</td>
                                <td>{{ number_format($data->current_ma, 1) }}</td>
                                <td>{{ number_format($data->power_mw, 0) }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $data->temp_c > 30 ? '#fecaca' : '#bfdbfe' }}; color: {{ $data->temp_c > 30 ? '#991b1b' : '#1e40af' }};">
                                        {{ number_format($data->temp_c, 1) }}°C
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $data->soil_pct }}%; background-color: {{ $data->soil_pct < 30 ? '#ef4444' : ($data->soil_pct < 60 ? '#f59e0b' : '#10b981') }};" aria-valuenow="{{ $data->soil_pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="text-muted small">{{ number_format($data->soil_pct, 0) }}%</span>
                                    </div>
                                </td>
                                <td>{{ $data->soil_adc }}</td>
                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($data->received_at)->format('Y-m-d H:i:s') }}
                                    </small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.sensor-node-data.show', $data->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
                                    <a href="{{ route('admin.sensor-node-data.edit', $data->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endif
                                    @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('admin.sensor-node-data.destroy', $data->id) }}" method="POST" class="d-inline"
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
                                <td colspan="11" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No sensor data found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $sensorData->firstItem() ?? 0 }} to {{ $sensorData->lastItem() ?? 0 }} of {{ $sensorData->total() }} entries
                    </div>
                    <div>
                        {{ $sensorData->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
