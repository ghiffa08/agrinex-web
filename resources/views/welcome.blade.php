<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AgriNex SmartDrip | IoT Smart Irrigation System</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('AgrinexLogo.jpg') }}" />

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>

    <script>
        // Check local storage or system preference to apply dark mode
        if (localStorage.getItem('theme') === 'dark' || 
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 flex flex-col selection:bg-brand-500 selection:text-white transition-colors duration-200">

    {{-- Header / Navigation --}}
    <header class="sticky top-0 z-50 bg-white/70 dark:bg-slate-950/70 backdrop-blur-xl border-b border-slate-200/50 dark:border-slate-800/50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-brand-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-brand-500/20">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2".5 d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                    Agri<span class="text-brand-500 font-black">Nex</span>
                </span>
            </a>

            <nav class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm font-semibold text-slate-600 hover:text-brand-500 dark:text-slate-300 dark:hover:text-brand-400 transition-colors">Features</a>
                <a href="#technology" class="text-sm font-semibold text-slate-600 hover:text-brand-500 dark:text-slate-300 dark:hover:text-brand-400 transition-colors">Technology</a>
                <a href="#system" class="text-sm font-semibold text-slate-600 hover:text-brand-500 dark:text-slate-300 dark:hover:text-brand-400 transition-colors">Interactive Demo</a>
            </nav>

            <div class="flex items-center gap-4">
                {{-- Light/Dark Mode Switcher --}}
                <button id="themeToggle" class="p-2.5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-all shadow-sm">
                    <svg id="themeToggleDarkIcon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg id="themeToggleLightIcon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.46 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.41 8.49a1 1 0 01-1.414 0l-.707-.707a1 1 0 011.414-1.414l.707.707a1 1 0 010 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>

                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-2xl bg-brand-500 hover:bg-brand-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-brand-500/20 hover:shadow-brand-600/30 transition-all duration-300">
                        Go to Dashboard
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-2xl bg-brand-500 hover:bg-brand-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-brand-500/20 hover:shadow-brand-600/30 transition-all duration-300">
                        Get Started
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-grow">
        
        {{-- Hero Section --}}
        <section class="relative overflow-hidden pt-12 pb-24 md:pt-20 md:pb-32 bg-gradient-to-tr from-emerald-50 via-emerald-100/10 to-teal-50 dark:from-slate-950 dark:via-emerald-950/15 dark:to-slate-900">
            {{-- Background decorative blobs --}}
            <div class="absolute top-1/4 left-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl -translate-x-1/2"></div>
            <div class="absolute bottom-1/4 right-0 w-96 h-96 bg-brand-500/10 rounded-full blur-3xl translate-x-1/2"></div>

            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
                <div class="lg:col-span-7 text-center lg:text-left space-y-6">
                    <div class="inline-flex items-center gap-2 bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 px-4 py-2 rounded-full border border-brand-200 dark:border-brand-500/20 text-xs font-bold uppercase tracking-wider animate-pulse">
                        <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                        Next-Gen IoT Farming
                    </div>
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-none">
                        Cultivating the Future with <span class="bg-gradient-to-r from-emerald-500 to-teal-400 bg-clip-text text-transparent">Smart Irrigation</span>
                    </h1>
                    <p class="text-base sm:text-lg text-slate-600 dark:text-slate-350 max-w-2xl mx-auto lg:mx-0 font-medium">
                        AgriNex delivers automated drip irrigation, high-precision soil metrics monitoring, and localized weather forecasting powered by ESP32 sensors and real-time dashboard controllers.
                    </p>
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded-2xl bg-brand-500 hover:bg-brand-600 px-8 py-4 text-base font-bold text-white shadow-xl shadow-brand-500/20 hover:shadow-brand-600/30 transition-all duration-300">
                                Open Web Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-2xl bg-brand-500 hover:bg-brand-600 px-8 py-4 text-base font-bold text-white shadow-xl shadow-brand-500/20 hover:shadow-brand-600/30 transition-all duration-300">
                                Access Admin Portal
                            </a>
                        @endauth
                        <a href="#features" class="rounded-2xl bg-white hover:bg-slate-50 dark:bg-slate-900 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 px-8 py-4 text-base font-bold text-slate-700 dark:text-slate-200 transition-all shadow-sm">
                            Learn More
                        </a>
                    </div>
                </div>

                {{-- Interactive Hero Mockup Card --}}
                <div class="lg:col-span-5 flex justify-center">
                    <div class="w-full max-w-sm rounded-[2.5rem] border border-slate-200/80 bg-white/70 p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900/50 backdrop-blur-xl transition-all duration-300 hover:scale-102">
                        <div class="flex items-center justify-between border-b border-slate-200/50 dark:border-slate-800/50 pb-4 mb-5">
                            <div class="flex items-center gap-3">
                                <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <h3 class="font-extrabold text-slate-950 dark:text-white text-base">Node 1 Status</h3>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full">ACTIVE</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-5">
                            <div class="bg-white/60 dark:bg-slate-800/40 border border-white/50 dark:border-slate-800 p-4 rounded-3xl shadow-sm">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Soil Moisture</span>
                                <span class="text-3xl font-black text-slate-900 dark:text-white">68.4%</span>
                                <span class="text-[10px] font-semibold text-emerald-500 block mt-1">Optimal Range</span>
                            </div>
                            <div class="bg-white/60 dark:bg-slate-800/40 border border-white/50 dark:border-slate-800 p-4 rounded-3xl shadow-sm">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Temperature</span>
                                <span class="text-3xl font-black text-slate-900 dark:text-white">26.8°C</span>
                                <span class="text-[10px] font-semibold text-orange-500 block mt-1">Warm Weather</span>
                            </div>
                        </div>

                        <div class="bg-white/60 dark:bg-slate-800/40 border border-white/50 dark:border-slate-800 p-4 rounded-3xl shadow-sm mb-5 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-medium text-slate-400">Treatment Plan:</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">Clay Loam (FC 80%)</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-medium text-slate-400">Next Irrigation:</span>
                                <span class="font-bold text-brand-500">Auto (Moisture ≤ 30%)</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-slate-400 pt-2 border-t border-slate-200/50 dark:border-slate-800/50">
                            <span>Last Communication:</span>
                            <span class="font-bold">2 mins ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features Section --}}
        <section id="features" class="py-24 bg-white dark:bg-slate-900">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center space-y-4 max-w-3xl mx-auto mb-16">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-brand-500">System Capabilities</h2>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white">Everything required for smart field management</h3>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">Precision agricultural monitoring integrated directly into an intuitive dashboard system.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    {{-- Feature 1 --}}
                    <div class="group p-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] shadow-sm hover:shadow-md hover:bg-white dark:hover:bg-slate-900 hover:scale-102 transition-all duration-300 flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/30 text-blue-500 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 dark:text-white">Precision Monitoring</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                                Tracking exact soil moisture percentages, temperatures, and physical variables inside experimental plots.
                            </p>
                        </div>
                    </div>

                    {{-- Feature 2 --}}
                    <div class="group p-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] shadow-sm hover:shadow-md hover:bg-white dark:hover:bg-slate-900 hover:scale-102 transition-all duration-300 flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 text-brand-500 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 dark:text-white">Smart Irrigation</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                                Automated valve triggering based on field capacity thresholds to prevent water stress and soil erosion.
                            </p>
                        </div>
                    </div>

                    {{-- Feature 3 --}}
                    <div class="group p-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] shadow-sm hover:shadow-md hover:bg-white dark:hover:bg-slate-900 hover:scale-102 transition-all duration-300 flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-orange-50 dark:bg-orange-950/30 text-orange-500 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 dark:text-white">Weather Insights</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                                Localized microclimate data integrating light lux, precipitation levels, wind speeds, and BMKG forecast APIs.
                            </p>
                        </div>
                    </div>

                    {{-- Feature 4 --}}
                    <div class="group p-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] shadow-sm hover:shadow-md hover:bg-white dark:hover:bg-slate-900 hover:scale-102 transition-all duration-300 flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/30 text-teal-500 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 dark:text-white">Reports & Logs</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                                Multi-layered audit trails recording every valve state change, API query payload, database sync, and PDF data logs.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Tech Stack / Architecture Section --}}
        <section id="technology" class="py-24 bg-slate-50 dark:bg-slate-950 border-y border-slate-200/50 dark:border-slate-800/50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="space-y-6">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-brand-500">Robust Infrastructure</h2>
                        <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white leading-tight">Hardware & Software working in complete harmony</h3>
                        <p class="text-slate-600 dark:text-slate-350 leading-relaxed font-medium">
                            AgriNex integrates multiple layers of network protocols and software backends to ensure the absolute integrity and safety of physical devices on the field.
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-center shrink-0">
                                    <span class="text-brand-500 font-bold">1</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white">ESP32 & LoRa Nodes</h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-normal">Low-power long-range endpoints transmitting sensor payloads through secure sub-GHz radio spectrums.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-center shrink-0">
                                    <span class="text-brand-500 font-bold">2</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white">Laravel REST API</h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-normal">Highly secured controller endpoint group handling validation, payload parsing, and logging audits.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Technical Flow Chart Mock --}}
                    <div class="bg-white/70 dark:bg-slate-900/50 p-8 rounded-[2.5rem] border border-slate-200/80 dark:border-slate-800 shadow-xl backdrop-blur-md">
                        <h4 class="font-bold text-slate-950 dark:text-white mb-6 flex items-center gap-2 text-sm uppercase tracking-wider">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            Data Synchronization Flow
                        </h4>
                        <div class="space-y-6 relative">
                            {{-- Line connector --}}
                            <div class="absolute left-6 top-3 bottom-3 w-0.5 bg-slate-200 dark:bg-slate-800 z-0"></div>

                            {{-- Flow Step 1 --}}
                            <div class="flex items-center gap-6 relative z-10">
                                <div class="w-12 h-12 rounded-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center font-bold text-slate-800 dark:text-white shadow-sm">
                                    A
                                </div>
                                <div class="flex-grow">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ESP32 SENSORS</span>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">Read soil moistures & temperatures</p>
                                </div>
                            </div>

                            {{-- Flow Step 2 --}}
                            <div class="flex items-center gap-6 relative z-10">
                                <div class="w-12 h-12 rounded-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center font-bold text-slate-800 dark:text-white shadow-sm">
                                    B
                                </div>
                                <div class="flex-grow">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">LoRa / Gateway HTTP</span>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">Transmit secure JSON payloads to host</p>
                                </div>
                            </div>

                            {{-- Flow Step 3 --}}
                            <div class="flex items-center gap-6 relative z-10">
                                <div class="w-12 h-12 rounded-full bg-brand-500 text-white flex items-center justify-center font-bold shadow-lg shadow-brand-500/20">
                                    C
                                </div>
                                <div class="flex-grow">
                                    <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Laravel Central</span>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">Auto-triggers valve states & updates UI</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Interactive Demo Area / CTA Section --}}
        <section id="system" class="py-24 bg-white dark:bg-slate-900">
            <div class="max-w-5xl mx-auto px-6">
                <div class="relative rounded-[3rem] bg-gradient-to-r from-emerald-600 to-teal-700 p-8 md:p-16 overflow-hidden shadow-2xl text-center text-white">
                    {{-- Design circles --}}
                    <div class="absolute -top-10 -right-10 w-48 h-48 bg-white/5 rounded-full blur-xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-black/5 rounded-full blur-xl"></div>

                    <div class="relative z-10 max-w-2xl mx-auto space-y-6">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-emerald-100">Ready to begin?</h2>
                        <h3 class="text-3xl md:text-5xl font-extrabold tracking-tight">Access the dashboard control room</h3>
                        <p class="text-emerald-100 font-medium">
                            Authorized administrators and operators can manage physical valves, adjust moisture triggers, and download Excel/PDF log reviews.
                        </p>
                        <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="rounded-2xl bg-white hover:bg-emerald-5 px-8 py-4 text-base font-bold text-slate-900 transition-all shadow-lg hover:shadow-xl">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="rounded-2xl bg-white hover:bg-emerald-5 px-8 py-4 text-base font-bold text-slate-900 transition-all shadow-lg hover:shadow-xl">
                                    Log In Now
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    {{-- Footer --}}
    <footer class="bg-slate-100 dark:bg-slate-950 border-t border-slate-200/50 dark:border-slate-800/50 py-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-500 text-white rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="text-lg font-bold text-slate-900 dark:text-white">
                    Agri<span class="text-brand-500">Nex</span>
                </span>
            </div>
            
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                Copyright &copy; {{ date('Y') }} <span class="font-semibold text-brand-500">AgriNex</span> SmartDrip. All rights reserved.
            </p>
        </div>
    </footer>

    {{-- Script for Light/Dark Mode Switcher --}}
    <script>
        const themeToggleBtn = document.getElementById('themeToggle');
        const themeToggleDarkIcon = document.getElementById('themeToggleDarkIcon');
        const themeToggleLightIcon = document.getElementById('themeToggleLightIcon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('theme') === 'dark' || 
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function() {
            // toggle icons
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('theme')) {
                if (localStorage.getItem('theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        });
    </script>

</body>
</html>
