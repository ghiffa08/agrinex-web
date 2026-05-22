<header class="flex items-center justify-between z-40 relative">
    <!-- Left side (Logo & Profile) -->
    <div class="flex items-center gap-3">
        <!-- Profile Picture -->
        <div class="h-12 w-12 rounded-full overflow-hidden border-2 border-white shadow-sm flex-shrink-0">
            @if (app()->environment('production'))
                <img src="images/agrinexlogo.jpg" alt="Profile" class="h-full w-full object-cover">
            @else
                <img src="{{ asset('AgrinexLogo.jpg') }}" alt="Profile" class="h-full w-full object-cover">
            @endif
        </div>
        
        <!-- Greeting -->
        <div class="flex flex-col justify-center">
            <div class="text-xs font-medium text-gray-500 flex items-center gap-1">
                Selamat Malam
            </div>
            <h1 class="text-xl font-bold text-gray-800 leading-tight">
                Hi, Admin
            </h1>
        </div>
    </div>

    <!-- Right Actions -->
    <div class="flex items-center gap-2 md:gap-3">
        
        <!-- Desktop specific actions (Hidden on Mobile) -->
        <div class="hidden md:flex items-center gap-2 mr-2">
            <!-- PWA Install Button -->
            <button @click="window.testPWAInstall ? testPWAInstall() : console.log('testPWAInstall not loaded')" 
                class="bg-white shadow-[0_2px_10px_rgba(0,0,0,0.05)] text-green-600 hover:bg-green-50 px-3 py-2 rounded-xl flex items-center gap-1.5 transition-colors"
                title="Install App">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span class="text-xs font-semibold">Install App</span>
            </button>
            
            <!-- Language Toggle -->
            <button @click="toggleLanguage()" class="bg-white shadow-[0_2px_10px_rgba(0,0,0,0.05)] hover:bg-gray-50 px-3 py-2 rounded-xl flex items-center gap-1.5 transition-colors text-gray-600" :title="t('switchLang')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-semibold uppercase" x-text="currentLang === 'id' ? 'ID' : 'EN'"></span>
            </button>
            
            <!-- Refresh Button -->
            <button @click="loadAll(true)" class="bg-white shadow-[0_2px_10px_rgba(0,0,0,0.05)] hover:bg-gray-50 px-3 py-2 rounded-xl flex items-center gap-1.5 transition-colors text-gray-600"
                :class="loadingAll ? 'opacity-60 pointer-events-none' : ''">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" :class="loadingAll ? 'animate-spin' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span class="text-xs font-semibold" x-text="loadingAll ? t('loading') : t('refresh')"></span>
            </button>

            @auth
                <a href="/dashboard" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl text-xs font-semibold shadow-[0_2px_10px_rgba(22,163,74,0.3)] transition-colors" x-text="t('admin')">Admin</a>
            @else
                <a href="/login" class="bg-white shadow-[0_2px_10px_rgba(0,0,0,0.05)] hover:bg-gray-50 px-4 py-2.5 rounded-xl text-xs font-semibold transition-colors" x-text="t('login')">Masuk</a>
            @endauth
        </div>

        <!-- Notification Bell -->
        <button class="h-11 w-11 bg-white rounded-full flex items-center justify-center shadow-[0_2px_10px_rgba(0,0,0,0.05)] text-green-600 hover:bg-green-50 transition-colors relative flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
            </svg>
            <span class="absolute top-2 right-2.5 h-2.5 w-2.5 bg-red-500 border-2 border-white rounded-full"></span>
        </button>
    </div>
</header>
