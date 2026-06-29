@extends('layouts.admin')

@section('title', 'AgriNex Dashboard')

@section('content')
<div x-data="dashboard()" class="space-y-6">
    {{-- Section 1: Weather + Devices --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-4">
            @include('components.weather-summary')
        </div>
        <div class="lg:col-span-8">
            @include('components.devices-tank')
        </div>
    </div>

    {{-- Section 2: Lahan + Tank + Metrics --}}
    <div class="space-y-6">
        @include('components.lahan-pantau')
        @include('components.water-tank')
        @include('components.metrics-cards')
    </div>

    {{-- Section 3: Analytics --}}
    <div class="space-y-6">
        @include('components.environmental-charts')
        @include('components.weekly-tasks')
        @include('components.usage-charts')
        @include('components.location-maps')
    </div>

    @include('components.modals')
</div>
@endsection

@push('scripts')
    @include('partials.dashboard-scripts')
    @include('partials.chart-fix')
@endpush