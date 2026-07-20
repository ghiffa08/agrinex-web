@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">📊 Laporan & Analitik</h1>
        <p class="text-gray-600">Generate laporan komprehensif dari data IoT AgriNex Smart Drip</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-neumorphic p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">🔍 Filter Laporan</h2>
        
        <form id="reportFilterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" 
                       id="start_date" 
                       name="start_date" 
                       value="{{ now()->subDays(30)->format('Y-m-d') }}"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" 
                       id="end_date" 
                       name="end_date" 
                       value="{{ now()->format('Y-m-d') }}"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Device</label>
                <select id="device_id" 
                        name="device_id" 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Semua Device</option>
                    @foreach($devices ?? [] as $device)
                        <option value="{{ $device->id }}">{{ $device->name }} - {{ $device->lokasi }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        
        <!-- Quick Date Presets -->
        <div class="flex flex-wrap gap-2 mt-4">
            <button onclick="setDateRange(7)" class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition">7 Hari</button>
            <button onclick="setDateRange(30)" class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition">30 Hari</button>
            <button onclick="setDateRange(90)" class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition">3 Bulan</button>
            <button onclick="setDateRange(180)" class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition">6 Bulan</button>
            <button onclick="setThisMonth()" class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition">Bulan Ini</button>
        </div>
    </div>

    <!-- Report Summary Preview -->
    <div id="summaryPreview" class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl shadow-neumorphic p-6 mb-6 hidden">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 Ringkasan Data</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="text-sm text-gray-600">Total Device</div>
                <div class="text-2xl font-bold text-green-600" id="preview_devices">-</div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="text-sm text-gray-600">Sensor Data</div>
                <div class="text-2xl font-bold text-blue-600" id="preview_readings">-</div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="text-sm text-gray-600">Sesi Irigasi</div>
                <div class="text-2xl font-bold text-cyan-600" id="preview_irrigations">-</div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="text-sm text-gray-600">Total Air</div>
                <div class="text-2xl font-bold text-sky-600" id="preview_water">-</div>
            </div>
        </div>
        <button onclick="togglePreview()" class="mt-4 text-sm text-gray-600 hover:text-gray-800">Tutup Preview</button>
    </div>

    <!-- Report Types Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Sensor Data Report -->
        <div class="bg-white rounded-2xl shadow-neumorphic p-6 hover:shadow-neumorphic-lg transition-all">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Data Sensor</h3>
                    <span class="text-xs text-gray-500">Excel</span>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4">Laporan lengkap pembacaan sensor dari semua device (suhu, kelembapan tanah, baterai)</p>
            <button onclick="generateReport('sensor_data_excel')" 
                    class="w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-medium">
                Generate Excel
            </button>
        </div>

        <!-- Weather Data Report -->
        <div class="bg-white rounded-2xl shadow-neumorphic p-6 hover:shadow-neumorphic-lg transition-all">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Data Cuaca</h3>
                    <span class="text-xs text-gray-500">Excel</span>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4">Laporan kondisi cuaca dan lingkungan (suhu, kelembapan, curah hujan)</p>
            <button onclick="generateReport('weather_data_excel')" 
                    class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium">
                Generate Excel
            </button>
        </div>

        <!-- Irrigation Report -->
        <div class="bg-white rounded-2xl shadow-neumorphic p-6 hover:shadow-neumorphic-lg transition-all">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Log Irigasi</h3>
                    <span class="text-xs text-gray-500">Excel</span>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4">Riwayat sesi irigasi, durasi, dan volume air yang digunakan</p>
            <button onclick="generateReport('irrigation_excel')" 
                    class="w-full py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg transition font-medium">
                Generate Excel
            </button>
        </div>

        <!-- Water Usage Summary -->
        <div class="bg-white rounded-2xl shadow-neumorphic p-6 hover:shadow-neumorphic-lg transition-all">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Penggunaan Air</h3>
                    <span class="text-xs text-gray-500">Excel</span>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4">Ringkasan statistik penggunaan air per device dan lokasi</p>
            <button onclick="generateReport('water_usage_excel')" 
                    class="w-full py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg transition font-medium">
                Generate Excel
            </button>
        </div>

        <!-- Comprehensive PDF -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl shadow-neumorphic p-6 hover:shadow-neumorphic-lg transition-all border-2 border-green-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Laporan Lengkap</h3>
                    <span class="text-xs text-green-700 font-medium">PDF</span>
                </div>
            </div>
            <p class="text-sm text-gray-700 mb-4 font-medium">📑 Laporan komprehensif semua data: device activity, sensor, irigasi, dan analitik lengkap</p>
            <button onclick="generateReport('comprehensive_pdf')" 
                    class="w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-medium shadow-md">
                Generate PDF Lengkap
            </button>
        </div>

        <!-- Irrigation PDF -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-neumorphic p-6 hover:shadow-neumorphic-lg transition-all border-2 border-blue-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Laporan Irigasi</h3>
                    <span class="text-xs text-blue-700 font-medium">PDF</span>
                </div>
            </div>
            <p class="text-sm text-gray-700 mb-4 font-medium">💧 Fokus pada analisis irigasi dan efisiensi penggunaan air</p>
            <button onclick="generateReport('irrigation_pdf')" 
                    class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium shadow-md">
                Generate PDF Irigasi
            </button>
        </div>

    </div>

    <!-- Preview Button -->
    <div class="mt-6 text-center">
        <button onclick="loadPreview()" 
                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition font-medium shadow-sm">
            👁️ Preview Ringkasan Data
        </button>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
        <div class="flex flex-col items-center">
            <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-green-600 mb-4"></div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Generating Report...</h3>
            <p class="text-gray-600 text-center" id="loadingMessage">Mohon tunggu, laporan sedang dibuat</p>
        </div>
    </div>
</div>

<script>
// Date Range Helpers
function setDateRange(days) {
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(endDate.getDate() - days);
    
    document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
    document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
}

function setThisMonth() {
    const now = new Date();
    const start = new Date(now.getFullYear(), now.getMonth(), 1);
    const end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    
    document.getElementById('start_date').value = start.toISOString().split('T')[0];
    document.getElementById('end_date').value = end.toISOString().split('T')[0];
}

// Generate Report
function generateReport(reportType) {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const deviceId = document.getElementById('device_id').value;
    
    // Validation
    if (!startDate || !endDate) {
        alert('⚠️ Harap pilih tanggal mulai dan akhir');
        return;
    }
    
    if (new Date(startDate) > new Date(endDate)) {
        alert('⚠️ Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        return;
    }
    
    // Show loading
    showLoading(`Membuat laporan ${reportType}...`);
    
    // Build URL
    const params = new URLSearchParams({
        start_date: startDate,
        end_date: endDate,
        device_id: deviceId || ''
    });
    
    const url = `/reports/generate/${reportType}?${params.toString()}`;
    
    // Download file
    window.location.href = url;
    
    // Hide loading after delay
    setTimeout(() => hideLoading(), 2000);
}

// Preview Data
async function loadPreview() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const deviceId = document.getElementById('device_id').value;
    
    if (!startDate || !endDate) {
        alert('⚠️ Harap pilih tanggal mulai dan akhir');
        return;
    }
    
    showLoading('Memuat preview...');
    
    try {
        const params = new URLSearchParams({
            start_date: startDate,
            end_date: endDate,
            device_id: deviceId || ''
        });
        
        const response = await fetch(`/api/reports/preview?${params.toString()}`);
        const data = await response.json();
        
        if (data.success) {
            const summary = data.summary;
            document.getElementById('preview_devices').textContent = `${summary.devices.active}/${summary.devices.total}`;
            document.getElementById('preview_readings').textContent = summary.readings.total.toLocaleString();
            document.getElementById('preview_irrigations').textContent = summary.irrigation.total_sessions.toLocaleString();
            document.getElementById('preview_water').textContent = `${summary.irrigation.total_water_liters.toLocaleString()} L`;
            
            document.getElementById('summaryPreview').classList.remove('hidden');
        } else {
            alert('❌ Gagal memuat preview: ' + data.message);
        }
    } catch (error) {
        console.error('Preview error:', error);
        alert('❌ Gagal memuat preview data');
    } finally {
        hideLoading();
    }
}

function togglePreview() {
    document.getElementById('summaryPreview').classList.add('hidden');
}

// Loading Modal
function showLoading(message = 'Loading...') {
    document.getElementById('loadingMessage').textContent = message;
    document.getElementById('loadingModal').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingModal').classList.add('hidden');
}
</script>
@endsection
