<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">

    {{-- PWA Meta Tags --}}
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="AgriNex">
    <meta name="application-name" content="AgriNex">

    <title>@yield('title', 'Dashboard') | AgriNex SmartDrip</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('AgrinexLogo.jpg') }}" />
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    {{-- Vite Assets (CSS + JS from npm) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.styles')

    {{-- Leaflet (still CDN since not in TailAdmin deps) --}}
    <link rel="preload" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <noscript>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    </noscript>
    <script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- TailAdmin: Apply dark mode immediately to prevent flash --}}
    <style>[x-cloak] { display: none !important; }</style>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    {{-- TailAdmin Alpine Stores (must load before Alpine) --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        body.classList.add('dark', 'bg-gray-900');
                    } else {
                        html.classList.remove('dark');
                        body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });

            Alpine.store('sidebar', {
                isExpanded: window.innerWidth >= 1280,
                isMobileOpen: false,
                isHovered: false,

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    this.isMobileOpen = false;
                },
                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                },
                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },
                setHovered(val) {
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });
        });
    </script>
</head>

<body
    x-data="{ 'loaded': true }"
    x-init="$store.sidebar.isExpanded = window.innerWidth >= 1280;
    const checkMobile = () => {
        if (window.innerWidth < 1280) {
            $store.sidebar.setMobileOpen(false);
            $store.sidebar.isExpanded = false;
        } else {
            $store.sidebar.isMobileOpen = false;
            $store.sidebar.isExpanded = true;
        }
    };
    window.addEventListener('resize', checkMobile);">

    {{-- Preloader --}}
    <div x-show="!loaded" class="fixed inset-0 z-[99999] flex items-center justify-center bg-white dark:bg-gray-900">
        <div class="w-10 h-10 border-4 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <div class="min-h-screen xl:flex">
        {{-- Mobile Backdrop --}}
        <div x-show="$store.sidebar.isMobileOpen"
             @click="$store.sidebar.setMobileOpen(false)"
             class="fixed z-50 h-screen w-full bg-gray-900/50 xl:hidden"
             style="display:none;"></div>

        {{-- Sidebar --}}
        @include('components.admin.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 transition-all duration-300 ease-in-out"
            :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'ml-0': $store.sidebar.isMobileOpen
            }">

            {{-- Header/Navbar --}}
            @include('components.admin.navbar')

            {{-- SPA Content Container --}}
            <div id="spa-content">
                {{-- Page Content --}}
                <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                    @yield('content')
                </div>

                {{-- Page-specific scripts loaded in a hidden wrapper --}}
                <div id="spa-scripts" style="display:none;">
                    @stack('scripts')
                </div>
            </div>

            {{-- Footer --}}
            <footer class="py-4 px-6 text-center text-xs text-gray-400 border-t border-gray-100 dark:border-gray-800">
                Copyright &copy; {{ date('Y') }} <span class="font-semibold text-brand-500">AgriNex</span> SmartDrip. All rights reserved.
            </footer>
        </div>
    </div>
</body>

</html>
