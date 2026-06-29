@extends('layouts.admin')

@section('title', 'Setup Perangkat')

@section('content')
    <!-- Soft UI Evolution Style Definition -->
    <style>
        @keyframes smoothPulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            }
            50% {
                transform: scale(1.05);
                opacity: 0.95;
                box-shadow: 0 0 0 16px rgba(16, 185, 129, 0);
            }
        }
        
        .smooth-pulse-radar {
            animation: smoothPulse 2.5s infinite cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes waveGlow {
            0%, 100% {
                opacity: 0.15;
            }
            50% {
                opacity: 0.35;
            }
        }

        .wave-glow {
            animation: waveGlow 3s infinite ease-in-out;
        }
    </style>

    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

        <!-- Header Section -->
        <div class="mb-8 lg:mb-12 text-center lg:text-left">
            <!-- Brand Badge / Info -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100/50 dark:border-emerald-900/30 mb-3">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400">Onboarding Perangkat</span>
            </div>
            <!-- Title -->
            <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight sm:text-4xl">
                Menunggu Perangkat Pertama
            </h1>
            <!-- Subtitle -->
            <p class="mt-3 max-w-2xl text-base text-slate-600 dark:text-slate-300 leading-relaxed">
                Sistem belum mendeteksi adanya sensor atau perangkat irigasi. Ikuti langkah di bawah ini untuk menghubungkan perangkat Anda ke AgriNex SmartDrip.
            </p>
        </div>

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-12 gap-6">
            
            <!-- Bento Item 1: Horizontal Stepper Progress (Full Width - 12 Cols) -->
            <div class="col-span-12 bg-white/80 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-6 lg:p-8 shadow-sm backdrop-blur-md">
                <!-- Stepper Progress Tracker -->
                <div class="relative flex flex-col md:flex-row items-center justify-between max-w-3xl mx-auto py-4 gap-6 md:gap-0">
                    <!-- Horizontal Connecting Line (hidden on small screens) -->
                    <div class="hidden md:block absolute left-16 right-16 top-1/2 -translate-y-1/2 h-0.5 bg-slate-100 dark:bg-slate-800/60">
                        <!-- Active progress line segment -->
                        <div class="h-full bg-emerald-500 w-[16.6%]"></div>
                    </div>
                    
                    <!-- Step 1: Nyalakan (Active) -->
                    <div class="relative z-10 flex flex-col items-center gap-2 bg-white dark:bg-slate-900 px-4 text-center">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-white ring-4 ring-emerald-50 dark:ring-emerald-950/30 shadow-md shadow-emerald-100 dark:shadow-none transition-all duration-300">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 18.364a9 9 0 0 1 0-12.728m12.728 0a9 9 0 0 1 0 12.728M12 3v9" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-800 dark:text-white">Nyalakan Perangkat</span>
                    </div>

                    <!-- Step 2: Jaringan (Pending) -->
                    <div class="relative z-10 flex flex-col items-center gap-2 bg-white dark:bg-slate-900 px-4 text-center">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500 transition-all duration-300">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 0 1 7.778 0M12 20h.01m-7.08-7.071a9.9 9.9 0 0 1 14.14 0M1.929 7.929a14 14 0 0 1 19.828 0" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Mencari Jaringan</span>
                    </div>

                    <!-- Step 3: Registrasi (Pending) -->
                    <div class="relative z-10 flex flex-col items-center gap-2 bg-white dark:bg-slate-900 px-4 text-center">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500 transition-all duration-300">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">Registrasi Otomatis</span>
                    </div>
                </div>
            </div>

            <!-- Bento Item 2: Step Detail Descriptions (8 Cols on large, 12 on mobile) -->
            <div class="col-span-12 lg:col-span-8 bg-white/80 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-6 lg:p-8 shadow-sm backdrop-blur-md flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2.5">
                        <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Instruksi Pemasangan
                    </h2>

                    <div class="space-y-6">
                        <!-- Step 1 Detail -->
                        <div class="flex items-start gap-4">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 18.364a9 9 0 0 1 0-12.728m12.728 0a9 9 0 0 1 0 12.728M12 3v9" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Nyalakan Perangkat IoT</h3>
                                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                    Hubungkan perangkat Anda ke sumber tegangan (baterai atau panel surya). Pastikan lampu indikator menyala yang menandakan sistem mulai proses booting.
                                </p>
                            </div>
                        </div>

                        <!-- Step 2 Detail -->
                        <div class="flex items-start gap-4">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-50 text-slate-500 dark:bg-slate-800/60 dark:text-slate-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 0 1 7.778 0M12 20h.01m-7.08-7.071a9.9 9.9 0 0 1 14.14 0M1.929 7.929a14 14 0 0 1 19.828 0" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Konektivitas Jaringan</h3>
                                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                    Perangkat akan otomatis mencoba terhubung ke jaringan internet. Pastikan perangkat berada dalam jangkauan sinyal yang stabil.
                                </p>
                            </div>
                        </div>

                        <!-- Step 3 Detail -->
                        <div class="flex items-start gap-4">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-50 text-slate-500 dark:bg-slate-800/60 dark:text-slate-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Registrasi Otomatis</h3>
                                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                    Segera setelah sistem menerima paket data pertama, perangkat akan terdaftar secara otomatis dan halaman ini akan diperbarui.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bento Item 3: ESP32 Web Flasher Link (4 Cols on large, 12 on mobile) -->
            <div class="col-span-12 lg:col-span-4 bg-white/80 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-6 shadow-sm backdrop-blur-md relative flex flex-col justify-center items-center min-h-[420px] text-center">
                
                <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-emerald-500 dark:bg-emerald-950/40 dark:text-emerald-400 ring-8 ring-emerald-50/50 dark:ring-emerald-900/20">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Setup Perangkat Baru</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-8 max-w-[250px]">
                    Gunakan tool ESP32 Flasher untuk memprogram dan menghubungkan perangkat baru ke sistem.
                </p>

                <a href="{{ route('admin.flasher') }}" class="inline-flex items-center justify-center w-full rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white py-3 px-4 font-semibold text-sm transition-all hover:-translate-y-0.5 active:translate-y-0 shadow-sm shadow-emerald-100 dark:shadow-none gap-2">
                    Buka Flasher Tool
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Help Link -->
        <div class="mt-12 text-center py-4">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Mengalami kesulitan? 
                <a href="#" class="cursor-pointer inline-flex items-center gap-1 text-emerald-500 font-semibold hover:text-emerald-600 transition-colors duration-200 hover:underline">
                    Baca dokumentasi
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
                atau hubungi teknisi.
            </p>
        </div>
    </div>

@endsection