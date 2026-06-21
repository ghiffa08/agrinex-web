{{-- ─── Clean Header ──────────────────────────────────────────── --}}
<header class="flex items-center justify-between gap-3 h-14 px-1">

    {{-- ── Left: Brand + Hamburger ── --}}
    <div class="flex items-center gap-3 flex-shrink-0">
        {{-- Mobile hamburger --}}
        <button
            @click="sidebarOpen = !sidebarOpen"
            class="md:hidden w-9 h-9 rounded-xl bg-white/70 dark:bg-white/10 border border-white/60 dark:border-white/10 flex items-center justify-center text-secondary dark:text-gray-300 shadow-sm hover:bg-white dark:hover:bg-white/20 transition-all"
            aria-label="Toggle menu"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Brand logo pill (desktop) --}}
        <a href="/" class="hidden md:flex items-center gap-2.5 group">
            <span class="text-[15px] font-bold text-gray-900 dark:text-white tracking-tight leading-none">AgriNex</span>
        </a>

        {{-- Mobile brand --}}
        <span class="md:hidden text-[15px] font-bold text-gray-900 dark:text-white tracking-tight">AgriNex</span>
    </div>

    {{-- ── Center: Search (desktop) ── --}}
    <div class="hidden md:flex flex-1 max-w-xs mx-auto">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-3.5 h-3.5 text-secondary opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input
                type="search"
                placeholder="Cari perangkat, lahan..."
                class="w-full h-9 bg-white/60 dark:bg-white/8 border border-white/60 dark:border-white/10 backdrop-blur-sm text-primary dark:text-gray-100 text-sm rounded-xl pl-9 pr-4 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-emerald-400/50 focus:border-emerald-400/50 focus:bg-white dark:focus:bg-white/15 transition-all"
            >
        </div>
    </div>

    {{-- ── Right: Action pills ── --}}
    <div class="flex items-center gap-1.5 flex-shrink-0">

        {{-- PWA Install (desktop only) --}}
        <button
            @click="installPWA()"
            class="hidden lg:flex items-center gap-1.5 h-9 px-3.5 bg-emerald-50 dark:bg-emerald-900/30 hover:bg-emerald-100 dark:hover:bg-emerald-800/40 border border-emerald-200 dark:border-emerald-700/40 text-emerald-800 dark:text-emerald-300 rounded-xl text-xs font-semibold transition-all shadow-sm"
            title="Install App"
        >
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span class="hidden xl:inline">Install</span>
        </button>

        {{-- Refresh --}}
        <button
            @click="loadAll(true)"
            :class="loadingAll ? 'opacity-50 pointer-events-none' : ''"
            class="hidden md:flex w-9 h-9 rounded-xl bg-white/60 dark:bg-white/8 border border-white/60 dark:border-white/10 items-center justify-center text-secondary dark:text-gray-300 hover:bg-white dark:hover:bg-white/20 shadow-sm transition-all"
            title="Refresh"
        >
            <svg :class="loadingAll ? 'animate-spin' : ''" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </button>

        {{-- Language --}}
        <button
            @click="toggleLanguage()"
            class="hidden md:flex w-9 h-9 rounded-xl bg-white/60 dark:bg-white/8 border border-white/60 dark:border-white/10 items-center justify-center text-secondary dark:text-gray-300 hover:bg-white dark:hover:bg-white/20 shadow-sm transition-all text-[11px] font-bold"
            :title="t('switchLang')"
        >
            <span x-text="currentLang === 'id' ? 'ID' : 'EN'"></span>
        </button>

        {{-- Notifications --}}
        <button
            class="relative w-9 h-9 rounded-xl bg-white/60 dark:bg-white/8 border border-white/60 dark:border-white/10 flex items-center justify-center text-secondary dark:text-gray-300 hover:bg-white dark:hover:bg-white/20 shadow-sm transition-all"
            title="Notifikasi"
        >
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 8a6 6 0 0112 0c0 7 3 9 3 9H3s3-2 3-9"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.3 21a1.94 1.94 0 003.4 0"/>
            </svg>
            <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-orange-500 rounded-full ring-1 ring-white dark:ring-slate-950"></span>
        </button>

        {{-- Dark mode toggle --}}
        <button
            @click="darkMode = !darkMode; localStorage.setItem('sis_dark', darkMode ? '1' : '0'); if(darkMode) { document.documentElement.classList.add('dark') } else { document.documentElement.classList.remove('dark') }"
            class="w-9 h-9 rounded-xl bg-white/60 dark:bg-white/8 border border-white/60 dark:border-white/10 flex items-center justify-center text-secondary dark:text-gray-300 hover:bg-white dark:hover:bg-white/20 shadow-sm transition-all"
            :title="darkMode ? 'Light mode' : 'Dark mode'"
        >
            {{-- Sun (shown in dark mode) --}}
            <svg x-show="darkMode" class="w-3.5 h-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="5"/>
                <path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
            </svg>
            {{-- Moon (shown in light mode) --}}
            <svg x-show="!darkMode" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
            </svg>
        </button>

        {{-- Avatar / Account --}}
        <button
            class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center shadow-sm hover:bg-emerald-700 transition-all"
            title="Akun"
        >
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
        </button>
    </div>
</header>
