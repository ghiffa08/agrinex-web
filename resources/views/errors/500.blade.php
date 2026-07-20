<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error - AgriNex</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Error Card -->
        <div class="bg-white rounded-3xl shadow-xl p-8 text-center">
            <!-- Icon -->
            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-800 mb-3">
                Oops! Terjadi Kesalahan
            </h1>

            <!-- Message -->
            <p class="text-gray-600 mb-6">
                Mohon maaf, terjadi kesalahan pada server. Tim kami telah diberitahu dan sedang memperbaikinya.
            </p>

            <!-- Error Code -->
            <div class="inline-block bg-gray-100 rounded-lg px-4 py-2 mb-6">
                <span class="text-sm text-gray-600">Error Code:</span>
                <span class="font-mono text-sm font-bold text-gray-800">500</span>
            </div>

            <!-- Actions -->
            <div class="space-y-3">
                <a href="{{ url('/') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                    Kembali ke Dashboard
                </a>
                
                <button onclick="location.reload()" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                    Coba Lagi
                </button>
            </div>

            <!-- Support -->
            <p class="text-sm text-gray-500 mt-6">
                Jika masalah berlanjut, hubungi 
                <a href="mailto:support@agrinex.io" class="text-green-600 hover:underline">support@agrinex.io</a>
            </p>
        </div>

        <!-- Footer -->
        <p class="text-center text-sm text-gray-600 mt-6">
            AgriNex Smart Drip System © {{ date('Y') }}
        </p>
    </div>
</body>
</html>
