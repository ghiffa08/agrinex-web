<x-filament-panels::page>
    @if($hasNoNodes)
        <!-- Setup Wizard (Onboarding Perangkat Pertama) -->
        <style>
            /* Hide Filament Page Header on this page */
            .fi-header {
                display: none !important;
            }
            
            .ob-container {
                max-width: 1040px;
                margin: 0 auto;
                padding: 16px 8px;
                font-family: 'Inter', system-ui, sans-serif;
            }
            
            /* Header styling */
            .ob-header {
                text-align: center;
                margin-bottom: 32px;
            }
            .ob-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 5px 14px;
                background: rgba(16, 185, 129, 0.06);
                border: 1px solid rgba(16, 185, 129, 0.15);
                color: #059669;
                font-weight: 700;
                font-size: 10.5px;
                border-radius: 50px;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 16px;
            }
            .dark .ob-badge {
                background: rgba(52, 211, 153, 0.08);
                border-color: rgba(52, 211, 153, 0.15);
                color: #34d399;
            }
            .ob-title {
                font-size: 28px;
                font-weight: 900;
                color: #0f172a;
                letter-spacing: -0.03em;
                line-height: 1.2;
            }
            .dark .ob-title {
                color: #ffffff;
            }
            .ob-subtitle {
                font-size: 13.5px;
                color: #64748b;
                max-width: 600px;
                margin: 12px auto 0 auto;
                line-height: 1.6;
            }
            .dark .ob-subtitle {
                color: #94a3b8;
            }
            
            /* 3-Step Grid */
            .ob-steps-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 20px;
                margin-top: 32px;
            }
            @media (min-width: 768px) {
                .ob-steps-grid {
                    grid-template-columns: repeat(3, 1fr);
                }
            }
            .ob-step-card {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                padding: 20px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                position: relative;
                overflow: hidden;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.005);
            }
            .dark .ob-step-card {
                background: #0f172a;
                border-color: #1e293b;
            }
            .ob-step-card.active {
                border-color: #10b981;
                box-shadow: 0 8px 20px -4px rgba(16, 185, 129, 0.08);
            }
            .dark .ob-step-card.active {
                border-color: #34d399;
                box-shadow: 0 8px 20px -4px rgba(52, 211, 153, 0.04);
            }
            .ob-step-top {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 20px;
            }
            .ob-step-num {
                font-size: 11px;
                font-weight: 800;
                color: #94a3b8;
                font-family: monospace;
            }
            .ob-step-card.active .ob-step-num {
                color: #10b981;
            }
            .ob-step-icon-wrap {
                width: 38px;
                height: 38px;
                border-radius: 10px;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #64748b;
                transition: all 0.3s ease;
            }
            .dark .ob-step-icon-wrap {
                background: #1e293b;
                border-color: #334155;
                color: #94a3b8;
            }
            .ob-step-card.active .ob-step-icon-wrap {
                background: rgba(16, 185, 129, 0.1);
                border-color: rgba(16, 185, 129, 0.15);
                color: #10b981;
            }
            .ob-step-body h3 {
                font-size: 14px;
                font-weight: 750;
                color: #1e293b;
                margin-bottom: 4px;
            }
            .dark .ob-step-body h3 {
                color: #f1f5f9;
            }
            .ob-step-body p {
                font-size: 11.5px;
                color: #64748b;
                line-height: 1.5;
                margin-bottom: 16px;
            }
            .dark .ob-step-body p {
                color: #94a3b8;
            }
            .ob-step-badge {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 3px 8px;
                background: #f1f5f9;
                color: #64748b;
                font-size: 9.5px;
                font-weight: 700;
                border-radius: 5px;
            }
            .dark .ob-step-badge {
                background: #1e293b;
                color: #94a3b8;
            }
            .ob-step-card.active .ob-step-badge {
                background: rgba(16, 185, 129, 0.12);
                color: #10b981;
                animation: activePulse 2.5s infinite;
            }
            .dark .ob-step-card.active .ob-step-badge {
                background: rgba(52, 211, 153, 0.12);
                color: #34d399;
            }
            @keyframes activePulse {
                0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.2); }
                50% { box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
            }
            
            /* Split Layout */
            .ob-layout-split {
                display: grid;
                grid-template-columns: 1fr;
                gap: 24px;
                margin-top: 24px;
            }
            @media (min-width: 1024px) {
                .ob-layout-split {
                    grid-template-columns: 1.7fr 1.3fr;
                }
            }
            
            /* Instructions Card styling */
            .ob-instruction-card {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                padding: 24px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
            }
            .dark .ob-instruction-card {
                background: #0f172a;
                border-color: #1e293b;
            }
            .ob-section-title {
                font-size: 15px;
                font-weight: 800;
                color: #1e293b;
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 20px;
                border-bottom: 1px solid #f1f5f9;
                padding-bottom: 12px;
            }
            .dark .ob-section-title {
                color: #f1f5f9;
                border-color: #1e293b;
            }
            
            .ob-inst-list {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            .ob-inst-item {
                display: flex;
                gap: 12px;
                padding: 12px 14px;
                border-radius: 10px;
                background: #f8fafc;
                border: 1px solid #f1f5f9;
                transition: all 0.2s ease;
            }
            .dark .ob-inst-item {
                background: rgba(30, 41, 59, 0.2);
                border-color: #1e293b;
            }
            .ob-inst-item.active {
                background: #ffffff;
                border-color: rgba(16, 185, 129, 0.2);
            }
            .dark .ob-inst-item.active {
                background: rgba(30, 41, 59, 0.4);
                border-color: rgba(52, 211, 153, 0.2);
            }
            .ob-inst-icon-wrap {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                background: #e2e8f0;
                color: #64748b;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            .dark .ob-inst-icon-wrap {
                background: #334155;
                color: #94a3b8;
            }
            .ob-inst-item.active .ob-inst-icon-wrap {
                background: rgba(16, 185, 129, 0.1);
                color: #10b981;
            }
            .dark .ob-inst-item.active .ob-inst-icon-wrap {
                background: rgba(52, 211, 153, 0.12);
                color: #34d399;
            }
            .ob-inst-text h4 {
                font-size: 13px;
                font-weight: 750;
                color: #1e293b;
                margin-bottom: 2px;
            }
            .dark .ob-inst-text h4 {
                color: #f1f5f9;
            }
            .ob-inst-text p {
                font-size: 11px;
                color: #64748b;
                line-height: 1.5;
            }
            .dark .ob-inst-text p {
                color: #94a3b8;
            }
            
            /* Action Card styling */
            .ob-action-card {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                padding: 24px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
            }
            .dark .ob-action-card {
                background: #0f172a;
                border-color: #1e293b;
            }
            .ob-action-icon-circle {
                width: 52px;
                height: 52px;
                border-radius: 50%;
                background: rgba(16, 185, 129, 0.08);
                border: 1px solid rgba(16, 185, 129, 0.15);
                display: flex;
                align-items: center;
                justify-content: center;
                color: #10b981;
                margin-bottom: 16px;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.08);
            }
            .dark .ob-action-icon-circle {
                background: rgba(52, 211, 153, 0.1);
                border-color: rgba(52, 211, 153, 0.15);
                color: #34d399;
            }
            .ob-action-card h3 {
                font-size: 15px;
                font-weight: 800;
                color: #1e293b;
                margin-bottom: 6px;
            }
            .dark .ob-action-card h3 {
                color: #ffffff;
            }
            .ob-action-card p {
                font-size: 11.5px;
                color: #64748b;
                line-height: 1.5;
                max-width: 220px;
                margin-bottom: 20px;
            }
            .dark .ob-action-card p {
                color: #94a3b8;
            }
            .ob-action-btn {
                width: 100%;
                max-width: 220px;
                padding: 11px 18px;
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: #ffffff;
                font-weight: 700;
                font-size: 12px;
                border-radius: 10px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                text-decoration: none;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
                transition: all 0.3s ease;
            }
            .ob-action-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
                background: linear-gradient(135deg, #059669 0%, #047857 100%);
            }
            
            .ob-footer {
                text-align: center;
                margin-top: 32px;
                font-size: 11px;
                color: #64748b;
            }
            .dark .ob-footer {
                color: #94a3b8;
            }
            .ob-footer-link {
                color: #10b981;
                font-weight: 700;
                text-decoration: none;
                margin-left: 2px;
            }
            .ob-footer-link:hover {
                text-decoration: underline;
            }
        </style>

        <div class="ob-container">
            <!-- Header Section -->
            <div class="ob-header">
                <div class="ob-badge">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Onboarding Perangkat</span>
                </div>
                <h1 class="ob-title">
                    Menunggu Perangkat Pertama
                </h1>
                <p class="ob-subtitle">
                    Sistem belum mendeteksi adanya sensor atau perangkat irigasi. Ikuti langkah di bawah ini untuk menghubungkan perangkat Anda ke AgriNex SmartDrip.
                </p>
            </div>

            <!-- 3-Step Stepper Cards -->
            <div class="ob-steps-grid">
                <!-- Step 1 -->
                <div class="ob-step-card active">
                    <div class="ob-step-top">
                        <span class="ob-step-num">STEP 01</span>
                        <div class="ob-step-icon-wrap">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 18.364a9 9 0 0 1 0-12.728m12.728 0a9 9 0 0 1 0 12.728M12 3v9" />
                            </svg>
                        </div>
                    </div>
                    <div class="ob-step-body">
                        <h3>Nyalakan Perangkat</h3>
                        <p>Hubungkan modul IoT ESP32 Anda ke baterai atau catu daya eksternal.</p>
                        <span class="ob-step-badge">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                            </span>
                            Running
                        </span>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="ob-step-card">
                    <div class="ob-step-top">
                        <span class="ob-step-num">STEP 02</span>
                        <div class="ob-step-icon-wrap">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 0 1 7.778 0M12 20h.01m-7.08-7.071a9.9 9.9 0 0 1 14.14 0M1.929 7.929a14 14 0 0 1 19.828 0" />
                            </svg>
                        </div>
                    </div>
                    <div class="ob-step-body">
                        <h3>Mencari Jaringan</h3>
                        <p>Menghubungkan perangkat ke sinyal nirkabel dan jaringan internet lokal.</p>
                        <span class="ob-step-badge">Waiting</span>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="ob-step-card">
                    <div class="ob-step-top">
                        <span class="ob-step-num">STEP 03</span>
                        <div class="ob-step-icon-wrap">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0" />
                            </svg>
                        </div>
                    </div>
                    <div class="ob-step-body">
                        <h3>Registrasi Otomatis</h3>
                        <p>Menginisialisasi handshake dan mengunggah telemetry pertama ke awan.</p>
                        <span class="ob-step-badge">Waiting</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Split Layout -->
            <div class="ob-layout-split">
                <!-- Instructions -->
                <div class="ob-instruction-card">
                    <h2 class="ob-section-title">
                        <svg class="h-4.5 w-4.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Langkah Panduan Pemasangan
                    </h2>

                    <div class="ob-inst-list">
                        <!-- Step 1 Info -->
                        <div class="ob-inst-item active">
                            <div class="ob-inst-icon-wrap">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="ob-inst-text">
                                <h4>01. Sambungkan Daya Utama</h4>
                                <p>Pastikan modul memiliki tegangan yang cukup dari baterai lithium atau output regulator panel surya.</p>
                            </div>
                        </div>

                        <!-- Step 2 Info -->
                        <div class="ob-inst-item">
                            <div class="ob-inst-icon-wrap">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 0 1 7.778 0M12 20h.01m-7.08-7.071a9.9 9.9 0 0 1 14.14 0" />
                                </svg>
                            </div>
                            <div class="ob-inst-text">
                                <h4>02. Tunggu Sinyal Stabil</h4>
                                <p>Lampu indikator biru pada ESP32 akan berkedip lambat selama proses koneksi Wi-Fi sedang diupayakan.</p>
                            </div>
                        </div>

                        <!-- Step 3 Info -->
                        <div class="ob-inst-item">
                            <div class="ob-inst-icon-wrap">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                                </svg>
                            </div>
                            <div class="ob-inst-text">
                                <h4>03. Konfirmasi di Dashboard</h4>
                                <p>Begitu MQTT telemetri pertama masuk, server akan meregistrasikannya dan mengalihkan Anda ke halaman utama.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Card -->
                <div class="ob-action-card">
                    <div class="ob-action-icon-circle">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3>Setup Perangkat Baru</h3>
                    <p>Gunakan JIG Flasher Tool untuk memasang firmware dan konfigurasi credentials Wi-Fi pada mikrokontroler.</p>
                    <a href="/admin/production-jig" class="ob-action-btn">
                        <span>Buka Flasher Tool</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Footer Help -->
            <div class="ob-footer">
                Mengalami kendala teknis?
                <a href="#" class="ob-footer-link">Baca Dokumentasi Lengkap</a>
                atau hubungi administrator.
            </div>
        </div>
    @else
        <!-- Main Real-Time Telemetry Dashboard -->
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
        </div>

        <!-- Inject Scripts and Chart configurations -->
        @include('partials.dashboard-scripts')
        @include('partials.chart-fix')
    @endif
</x-filament-panels::page>
