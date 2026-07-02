<style>
    /* ─── Design Tokens ─────────────────────────────── */
    :root {
        color-scheme: light dark;
        --font-display: 'Plus Jakarta Sans', system-ui, sans-serif;
        --font-mono:    'DM Mono', 'Cascadia Code', monospace;

        /* Glass surfaces */
        --glass-bg:      rgba(255, 255, 255, 0.60);
        --glass-border:  rgba(255, 255, 255, 0.50);
        --glass-blur:    blur(16px);

        /* Text */
        --text-primary:   #1a2e1a;
        --text-secondary: #5a6872;
        --text-muted:     #9ca3af;

        /* Emerald accent */
        --accent:         #10b981;
        --accent-dim:     #d1fae5;
    }

    .dark {
        --glass-bg:      rgba(15, 23, 42, 0.65);
        --glass-border:  rgba(255, 255, 255, 0.07);
        --text-primary:  #e2f0e2;
        --text-secondary:#94a3b8;
        --text-muted:    #475569;
        --accent-dim:    rgba(16, 185, 129, 0.15);
    }

    html, body {
        font-family: var(--font-display);
        font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
        font-optical-sizing: auto;
    }

    /* Global text helpers using tokens */
    .text-primary   { color: var(--text-primary); }
    .text-secondary { color: var(--text-secondary); }
    .text-muted     { color: var(--text-muted); }


    /* ─── Sidebar ───────────────────────────────────── */
    .sidebar-item {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.75rem;   /* 44px */
        height: 2.75rem;
        border-radius: 0.875rem;
        color: #6b7280;
        /* IMPORTANT: overflow visible so tooltip isn't clipped */
        overflow: visible;
        transition: background 0.15s ease, color 0.15s ease, transform 0.15s ease;
    }

    .sidebar-item:hover {
        background: rgba(255, 255, 255, 0.8);
        color: #1a3a1a;
        transform: scale(1.05);
    }

    .dark .sidebar-item:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #e2e8f0;
    }

    .sidebar-item.active {
        background: #d1fae5;        /* emerald-100 */
        color: #065f46;             /* emerald-900 */
    }

    .dark .sidebar-item.active {
        background: rgba(16, 185, 129, 0.15);
        color: #6ee7b7;
    }

    /* Tooltip that appears to the right of each icon */
    .sidebar-tooltip {
        pointer-events: none;
        position: absolute;
        left: calc(100% + 12px);
        top: 50%;
        transform: translateY(-50%) translateX(-4px);
        white-space: nowrap;
        background: #1e293b;
        color: #f1f5f9;
        font-size: 0.6875rem;       /* 11px */
        font-weight: 600;
        letter-spacing: 0.02em;
        padding: 0.3rem 0.65rem;
        border-radius: 0.5rem;
        opacity: 0;
        transition: opacity 0.15s ease, transform 0.15s ease;
        z-index: 60;
        box-shadow: 0 2px 8px rgba(0,0,0,0.18);
    }

    .sidebar-tooltip::before {
        content: '';
        position: absolute;
        right: 100%;
        top: 50%;
        transform: translateY(-50%);
        border: 5px solid transparent;
        border-right-color: #1e293b;
    }

    .sidebar-item:hover .sidebar-tooltip {
        opacity: 1;
        transform: translateY(-50%) translateX(0);
    }

    .dark .sidebar-tooltip {
        background: #0f172a;
        box-shadow: 0 2px 8px rgba(0,0,0,0.4);
    }

    .dark .sidebar-tooltip::before {
        border-right-color: #0f172a;
    }

    /* Hide scrollbar for Chrome, Safari and Opera */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }

    /* ─── Bottom Nav (Mobile) ────────────────────────── */
    .bottom-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.2rem;
        padding: 0.4rem 0.6rem;
        border-radius: 0.875rem;
        color: #9ca3af;
        font-size: 0.625rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        transition: background 0.15s ease, color 0.15s ease;
        min-width: 2.75rem;
    }

    .bottom-nav-item:hover  { color: #059669; background: rgba(16,185,129,.08); }
    .bottom-nav-item.active { color: #065f46; background: #d1fae5; }

    .dark .bottom-nav-item         { color: #64748b; }
    .dark .bottom-nav-item:hover   { color: #6ee7b7; background: rgba(16,185,129,.12); }
    .dark .bottom-nav-item.active  { color: #6ee7b7; background: rgba(16,185,129,.15); }

    /* Numeric readouts use tabular mono for stability */
    .stat-value,
    .font-mono,
    [class*="text-"] .font-mono,
    .gauge-inner text,
    .tabular-nums {
        font-family: var(--font-mono);
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.01em;
    }

    /* ─── Card / Surface ─────────────────────────────── */
    .card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.07), 0 4px 16px rgba(0, 0, 0, 0.05);
        padding: 1.25rem;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
    }

    .card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1), 0 8px 24px rgba(0, 0, 0, 0.07);
        transform: translateY(-1px);
    }

    .dark .card {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4), 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    /* ─── Section Title ──────────────────────────────── */
    .section-title {
        font-size: 0.8125rem;       /* 13px */
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #6b7280;
    }

    .dark .section-title {
        color: #94a3b8;
    }

    /* ─── Stats labels ───────────────────────────────── */
    .stat-value {
        font-size: 1.5rem;
        line-height: 2rem;
        font-weight: bold;
        color: var(--text-primary);
    }

    .stat-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.06em;
        color: #9ca3af;
        text-transform: uppercase;
    }

    .dark .stat-label {
        color: #64748b;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        line-height: 1rem;
        font-weight: 500;
        transition: all 0.15s ease-in-out;
    }

    .btn-ghost {
        background-color: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .btn-ghost:hover {
        background-color: #e5e7eb;
        border-color: #9ca3af;
    }

    .skeleton {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        background-color: #e5e7eb;
        border-radius: 0.25rem;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    @keyframes popIn {
        0% {
            transform: scale(.8);
            opacity: 0
        }
        100% {
            transform: scale(1);
            opacity: 1
        }
    }

    .animate-pop {
        animation: popIn .35s cubic-bezier(.4, 1.4, .4, 1) both
    }

    .metric-icon svg {
        width: 20px;
        height: 20px;
        stroke-width: 2;
    }

    .metric-icon--small svg {
        width: 16px;
        height: 16px;
        stroke-width: 2;
    }

    .metric-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .metric-card:hover {
        transform: translateY(-2px);
    }

    .gauge-progress {
        transition: stroke-dashoffset 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @media (max-width: 768px) {
        .metrics-container {
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: #d1d5db transparent;
        }

        .metrics-container::-webkit-scrollbar {
            height: 4px;
        }

        .metrics-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .metrics-container::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 2px;
        }
    }

    @keyframes rainDrop {
        0% {
            transform: translateY(-100%);
            opacity: 0;
        }
        50% {
            opacity: 1;
        }
        100% {
            transform: translateY(100%);
            opacity: 0;
        }
    }

    .rain-drop {
        animation: rainDrop 2s infinite linear;
    }

    @keyframes batteryPulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.6;
        }
    }

    .battery-full {
        animation: batteryPulse 1.5s infinite ease-in-out;
    }

    /* Leaflet z-index fixes */
    .leaflet-container {
        z-index: 1 !important;
    }

    .leaflet-control-container {
        z-index: 2 !important;
    }

    .leaflet-popup {
        z-index: 3 !important;
    }

    .modal-overlay {
        z-index: 9999 !important;
    }

    .leaflet-tile-pane {
        z-index: 1 !important;
    }

    .leaflet-overlay-pane {
        z-index: 2 !important;
    }

    .leaflet-marker-pane {
        z-index: 3 !important;
    }

    .leaflet-tooltip-pane {
        z-index: 4 !important;
    }

    .leaflet-shadow-pane {
        z-index: 1 !important;
    }

    #leafletMap .leaflet-container,
    #leafletMapFull .leaflet-container {
        z-index: 1 !important;
        position: relative !important;
    }

    .gauge {
        filter: drop-shadow(0 1px 2px rgba(0, 0, 0, .12));
    }

    .gauge-inner {
        font-variant-numeric: tabular-nums;
    }

    .card-gradient-mask {
        background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, .4), transparent 70%);
    }

    .chart-legend-item {
        transition: all 0.2s ease;
    }

    .chart-legend-item:hover {
        transform: translateX(2px);
    }

    /* PWA Styles */
    [x-cloak] {
        display: none !important;
    }

    body.pwa-installed {
        padding-top: env(safe-area-inset-top);
        padding-bottom: env(safe-area-inset-bottom);
    }

    @keyframes slideUp {
        from {
            transform: translateY(100px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @media (display-mode: standalone) {
        body {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            user-select: none;
        }
    }

    .pull-to-refresh {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #16a34a;
        color: white;
        transform: translateY(-100%);
        transition: transform 0.3s ease;
        z-index: 9999;
    }

    .pull-to-refresh.active {
        transform: translateY(0);
    }
</style>
