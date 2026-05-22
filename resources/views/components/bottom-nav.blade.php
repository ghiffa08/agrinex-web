<!-- Floating Bottom Navigation -->
<div class="fixed bottom-6 left-4 right-4 z-50 md:hidden">
    <nav class="bg-[#5a7c49]/95 backdrop-blur-xl rounded-[2.5rem] shadow-[0_10px_40px_rgba(90,124,73,0.3)] border border-[#6b8d5a] px-2 py-2 flex items-center justify-between">
        
        <!-- 1. Fields (Active) -->
        <button class="flex flex-col items-center justify-center w-16 gap-1 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#9de066]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            <span class="text-[10px] font-semibold text-[#9de066]">Fields</span>
            <div class="w-1 h-1 bg-[#9de066] rounded-full absolute bottom-1.5 opacity-100"></div>
        </button>

        <!-- 2. Power/Devices -->
        <button class="flex flex-col items-center justify-center w-16 gap-1 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white/60 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <span class="text-[10px] font-medium text-white/60 group-hover:text-white transition-colors">Devices</span>
        </button>

        <!-- 3. Add Button (Center) -->
        <button class="h-14 w-14 rounded-full bg-[#7cbd4a] flex items-center justify-center shadow-[0_0_20px_rgba(124,189,74,0.4)] flex-shrink-0 -my-2 border-4 border-[#5a7c49] text-white hover:scale-105 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
        </button>

        <!-- 4. Documents/Tasks -->
        <button class="flex flex-col items-center justify-center w-16 gap-1 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white/60 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="text-[10px] font-medium text-white/60 group-hover:text-white transition-colors">Doc</span>
        </button>

        <!-- 5. Profile -->
        <button class="flex flex-col items-center justify-center w-16 gap-1 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white/60 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-[10px] font-medium text-white/60 group-hover:text-white transition-colors">Profile</span>
        </button>

    </nav>
</div>
