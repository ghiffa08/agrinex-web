<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    @include('partials.head')
    @include('partials.dashboard-scripts')
</head>

<body x-data="dashboard()" x-init="applyPersistedTheme();"
    class="h-full bg-[#f0f5f0] text-gray-800 dark:bg-[#0b1120] dark:text-slate-100 relative overflow-x-hidden transition-colors duration-500">

    {{-- ── Fixed background image ── --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-[0.12] dark:opacity-[0.06] transition-opacity duration-700"
            style="background-image: url('{{ asset('images/background-perkebunan.webp') }}');"></div>
        {{-- Soft gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/80 via-transparent to-transparent dark:from-emerald-950/20 dark:via-transparent dark:to-transparent"></div>
    </div>

    {{-- ══════════════════════════════════════════════════
         App Shell: [Sidebar | Main]
         Sidebar is sticky inside flex — no z-fighting
    ══════════════════════════════════════════════════ --}}
    <div class="relative z-10 flex h-full min-h-screen">

        {{-- Sidebar — sticky column, hidden on mobile --}}
        <div class="hidden md:flex md:flex-shrink-0">
            @include('components.sidebar')
        </div>

        {{-- Mobile slide-over sidebar --}}
        <div class="md:hidden">
            @include('components.sidebar')
        </div>

        {{-- ── Main column ── --}}
        <div class="flex-1 min-w-0 flex flex-col min-h-screen overflow-x-hidden">

            {{-- Sticky frosted header --}}
            <div class="sticky top-0 z-30 w-full
                        bg-white/55 dark:bg-[#0b1120]/70
                        backdrop-blur-2xl
                        border-b border-white/40 dark:border-white/[0.06]
                        shadow-sm shadow-black/[0.05]
                        transition-colors duration-300">
                <div class="px-4 md:px-6 xl:px-8">
                    @include('components.header')
                </div>
            </div>

            {{-- Page content --}}
            <main class="flex-1 px-4 md:px-6 xl:px-8 pt-6 pb-28 md:pb-10 w-full max-w-[1400px] mx-auto">

                {{-- Section 1: Weather + Devices --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                    <div class="lg:col-span-4">
                        @include('components.weather-summary')
                    </div>
                    <div class="lg:col-span-8">
                        @include('components.devices-tank')
                    </div>
                </div>

                {{-- Section 2: Lahan + Tank + Metrics --}}
                <div class="mt-5 space-y-5">
                    @include('components.lahan-pantau')
                    @include('components.water-tank')
                    @include('components.metrics-cards')
                </div>

                {{-- Section 3: Analytics --}}
                <div class="mt-5 space-y-5">
                    @include('components.environmental-charts')
                    @include('components.weekly-tasks')
                    @include('components.usage-charts')
                    @include('components.location-maps')
                </div>

                <footer class="text-center pt-10 pb-2 text-xs text-gray-400 dark:text-slate-600 font-medium tracking-wide">
                    &copy; {{ date('Y') }} AgriNex Smart Irrigation
                </footer>

            </main>
        </div>
    </div>

    {{-- Mobile bottom nav --}}
    @include('components.bottom-nav')

    @include('components.pwa-components')
    @include('components.modals')
    @include('partials.pwa-scripts')
    @include('partials.chart-fix')

</body>
</html>