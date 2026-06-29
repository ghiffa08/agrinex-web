<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | AgriNex SmartDrip</title>
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('AgrinexLogo.jpg') }}" />

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
        if (localStorage.getItem('dark-mode') === 'true' || 
            (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-50 dark:bg-gray-950 p-4 transition-colors duration-200">
    <div class="w-full max-w-md">
        {{-- Card Container --}}
        <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-xl dark:border-gray-800 dark:bg-white/[0.03] backdrop-blur-md">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center p-3 rounded-2xl bg-brand-50 dark:bg-brand-500/10 border border-brand-200 dark:border-brand-500/20 mb-4">
                    <img src="{{ asset('AgrinexLogo.jpg') }}" alt="AgriNex Logo" class="w-12 h-12 rounded-xl object-cover shadow-sm">
                </div>
                <h1 class="text-2xl font-bold text-gray-850 dark:text-white">Welcome Back</h1>
                <p class="text-theme-sm text-gray-550 dark:text-gray-400 mt-1">Sign in to AgriNex IoT SmartDrip</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-error-50 p-4 text-theme-sm text-error-800 dark:bg-error-500/10 dark:text-error-400">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="username" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Username or Email</label>
                    <input type="text" name="username" id="username" required autofocus
                           class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-700">
                        <span class="text-theme-sm text-gray-600 dark:text-gray-400">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full rounded-lg bg-brand-500 py-3 text-theme-sm font-semibold text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                    Sign In
                </button>
            </form>

            <div class="relative flex py-5 items-center">
                <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
                <span class="flex-shrink mx-4 text-theme-xs text-gray-450 dark:text-gray-500 uppercase tracking-wider font-semibold">Or continue with</span>
                <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
            </div>

            <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-2.5 rounded-lg border border-gray-300 bg-white dark:bg-gray-800/10 px-4 py-3 text-theme-sm font-semibold text-gray-700 dark:text-gray-300 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-white/[0.03] transition-colors">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google Logo" class="w-5 h-5">
                Sign in with Google
            </a>

            <div class="text-center mt-6">
                <a href="{{ route('welcome') }}" class="text-theme-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 inline-flex items-center gap-1.5 transition-colors font-semibold">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Landing Page
                </a>
            </div>
        </div>
    </div>
</body>
</html>
