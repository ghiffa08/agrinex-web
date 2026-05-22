@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Report for Node #{{ $node->node_id ?? $node->id }}</h1>

    <div class="mb-3">
        <a href="{{ route('reports.export', ['type' => 'sensor', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-sm btn-primary">Export Sensor CSV</a>
        <a href="{{ route('reports.export', ['type' => 'irrigation', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-sm btn-secondary">Export Irrigation CSV</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">Statistics</div>
                <div class="card-body">
                    <p><strong>Total readings:</strong> {{ number_format($stats['total_readings'] ?? 0) }}</p>
                    <p><strong>Average moisture:</strong> {{ number_format($stats['avg_moisture'] ?? 0, 2) }}%</p>
                    <p><strong>Average temperature:</strong> {{ number_format($stats['avg_temp'] ?? 0, 2) }}°C</p>
                    <p><strong>Irrigation events:</strong> {{ number_format($stats['irrigation_events'] ?? 0) }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Sensor data ({{ $sensorData->total() }} rows)</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Received at</th>
                                <th>Soil %</th>
                                <th>Temp (°C)</th>
                                <th>Voltage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sensorData as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->received_at }}</td>
                                    <td>{{ $row->soil_pct }}</td>
                                    <td>{{ $row->temp_c }}</td>
                                    <td>{{ $row->voltage_v ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $sensorData->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
