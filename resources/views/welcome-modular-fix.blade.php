<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    @include('partials.head')

    {{-- Dashboard JavaScript (Alpine.js) - For other features only --}}
    @include('partials.dashboard-scripts')
</head>

<body x-data="dashboard()" x-init="applyPersistedTheme(); init();" class="h-full bg-gray-50 text-gray-800 relative overflow-x-hidden">

    <!-- Aesthetic background blobs (fixed to body) -->
    <div class="fixed top-0 left-0 w-full md:w-1/2 h-72 bg-[#f2f8f2] rounded-br-[6rem] -z-10"></div>
    <div class="fixed top-[-40px] right-[-20px] w-48 md:w-96 h-48 md:h-96 bg-[#e8f3e8] rounded-full opacity-60 -z-10"></div>
    <div class="fixed top-[250px] left-[-60px] w-64 md:w-[500px] h-64 md:h-[500px] bg-[#e8f3e8] rounded-full opacity-40 -z-10"></div>

    <!-- Sidebar Component (Desktop Only) -->
    @include('components.sidebar')

    <!-- Main Wrapper -->
    <div class="min-h-screen pb-28 md:pb-8 flex flex-col md:pl-64 transition-all duration-300">
        
        {{-- Header Navigation --}}
        <div class="w-full px-4 md:px-8 pt-6">
            <div class="max-w-7xl mx-auto">
                @include('components.header')
            </div>
        </div>

        <!-- Content Area -->
        <main class="flex-1 px-4 md:px-8 mt-6 max-w-7xl mx-auto w-full">
            <!-- Top Section: Core Monitoring -->
            <div class="md:grid md:grid-cols-12 md:gap-8">
                
                <!-- Left Column (Mobile: Full Width) -->
                <div class="col-span-12 lg:col-span-4 space-y-6 md:space-y-8">
                    {{-- Weather, Time & Date Section --}}
                    @include('components.weather-summary')
                </div>

                <!-- Right Column (Mobile: Full Width) -->
                <div class="col-span-12 lg:col-span-8 mt-8 lg:mt-0 space-y-6 md:space-y-8">
                    {{-- Devices Section --}}
                    @include('components.devices-tank')
                </div>
            </div>

            <!-- Full-Width Middle Section -->
            <div class="mt-8 space-y-6 md:space-y-8">
                {{-- Monitored Fields Section (Full Width) --}}
                @include('components.lahan-pantau')

                {{-- Water Tank (Full Width) --}}
                @include('components.water-tank')

                {{-- Metrics Gauge Cards (Full Width) --}}
                @include('components.metrics-cards')
            </div>

            <!-- Bottom Section: Analytics & Maps -->
            <div class="mt-8 space-y-6 md:space-y-8">
                {{-- Environmental Charts (Light, Water, Soil, Temp, Humidity) --}}
                @include('components.environmental-charts')

                {{-- Weekly Tasks & Calendar --}}
                @include('components.weekly-tasks')

                <div class="md:grid md:grid-cols-12 md:gap-8 space-y-6 md:space-y-0">
                    <div class="col-span-12 lg:col-span-12">
                        {{-- Water Usage Charts (30 days & 24 hours) --}}
                        @include('components.usage-charts')
                    </div>
                </div>

                {{-- Location Maps (Street View & Leaflet) - Full Width --}}
                @include('components.location-maps')
            </div>

            <footer class="text-center py-8 text-xs text-gray-400 font-medium tracking-wide mt-12">&copy; {{ date('Y') }} Smart Irrigation</footer>
        </main>
    </div>

    {{-- Bottom Navigation (Hidden on Desktop) --}}
    @include('components.bottom-nav')

    {{-- PWA Components (Install Prompt, Update Banner, Offline Indicator) --}}
    @include('components.pwa-components')

    {{-- Modals (Device Detail) --}}
    @include('components.modals')

    {{-- PWA Service Worker Scripts --}}
    @include('partials.pwa-scripts')

    {{-- ✨ CHART FIX - Loaded last to override any conflicts --}}
    @include('partials.chart-fix')

</body>

</html>