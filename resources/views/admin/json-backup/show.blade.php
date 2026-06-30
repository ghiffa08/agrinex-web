@extends('layouts.app')
@section('title', 'JSON Backup Details')

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-2">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.json-backup.index') }}">JSON Backup</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail #{{ $backup->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.json-backup.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-info-circle"></i> Informasi Backup
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>ID</th>
                            <td><span class="badge bg-dark">{{ $backup->id }}</span></td>
                        </tr>
                        <tr>
                            <th>Sesi ID Getdata</th>
                            <td>{{ $backup->sesi_id_getdata ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Backup Timestamp</th>
                            <td>{{ $backup->created_at ? $backup->created_at->format('d-m-Y H:i:s') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Ukuran Data (KB)</th>
                            <td>
                                @php
                                    $jsonString = is_array($backup->json_data) ? json_encode($backup->json_data) : $backup->json_data;
                                @endphp
                                {{ number_format(strlen($jsonString)/1024, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <th>Total Records</th>
                            <td>{{ $backup->total_records ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <i class="bi bi-file-earmark-code"></i> Preview Data JSON
                </div>
                <div class="card-body" style="max-height: 300px; overflow:auto;">
                    <pre class="small bg-light p-2 border">
                        @php
                            $jsonString = is_array($backup->json_data) ? json_encode($backup->json_data, JSON_PRETTY_PRINT) : $backup->json_data;
                        @endphp
                        {{ $jsonString }}
                    </pre>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->role === 'admin')
    <div class="card border-danger mt-4">
        <div class="card-header bg-danger text-white">
            <i class="bi bi-exclamation-triangle"></i> Danger Zone
        </div>
        <div class="card-body">
            <form action="{{ route('admin.json-backup.destroy', $backup->id) }}" method="POST" onsubmit="return confirm('Hapus backup JSON ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Hapus Backup JSON
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
