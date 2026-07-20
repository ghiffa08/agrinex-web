@extends('layouts.app')

@section('title', 'Generate Laporan')

@section('content')
<div x-data="reportGenerator()" class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">📊 Generate Laporan</h1>
        <p class="text-gray-600">Buat dan download laporan sistem irigasi dalam format Excel atau PDF</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-neu-flat p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Filter Laporan</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Start Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input 
                    type="date" 
                    x-model="filters.start_date"
                    class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                >
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                <input 
                    type="date" 
                    x-model="filters.end_date"
                    class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                >
            </div>

            <!-- Device Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Device (Opsional)</label>
                <select 
                    x-model="filters.device_id"
                    class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                >
                    <option value="">Semua Device</option>
                    <template x-for="device in devices" :key="device.id">
                        <option :value="device.id" x-text="device.label"></option>
                    </template>
                </select>
            </div>
        </div>
    </div>

    <!-- Report Types Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="report in reports" :key="report.id">
            <div class="bg-white rounded-2xl shadow-neu-flat hover:shadow-neu-pressed transition-all duration-300 cursor-pointer p-6"
                 @click="generateReport(report.id)">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white shadow-lg">
                        <i :class="'fas fa-' + report.icon + ' text-xl'"></i>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase"
                          :class="report.format === 'xlsx' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                        <span x-text="report.format"></span>
                    </span>
                </div>

                <h3 class="text-lg font-bold text-gray-800 mb-2" x-text="report.name"></h3>
                <p class="text-sm text-gray-600 mb-4" x-text="report.description"></p>

                <button 
                    class="w-full py-2 px-4 rounded-xl font-semibold text-white transition-all duration-200"
                    :class="loading === report.id 
                        ? 'bg-gray-400 cursor-not-allowed' 
                        : 'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 shadow-neu-btn active:shadow-neu-pressed'"
                    :disabled="loading === report.id"
                    @click.stop="generateReport(report.id)">
                    <span x-show="loading !== report.id">
                        <i class="fas fa-download mr-2"></i>Download
                    </span>
                    <span x-show="loading === report.id">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Generating...
                    </span>
                </button>
            </div>
        </template>
    </div>

    <!-- Toast Notification -->
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-4 right-4 z-50 max-w-md">
        <div class="rounded-xl shadow-lg p-4 flex items-center space-x-3"
             :class="toastType === 'error' ? 'bg-red-500' : 'bg-green-500'">
            <i :class="toastType === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-check-circle'" 
               class="text-white text-xl"></i>
            <p class="text-white font-medium flex-1" x-text="toastMessage"></p>
            <button @click="showToast = false" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<script>
function reportGenerator() {
    return {
        filters: {
            start_date: new Date(new Date().setDate(new Date().getDate() - 30)).toISOString().split('T')[0],
            end_date: new Date().toISOString().split('T')[0],
            device_id: ''
        },
        devices: [],
        reports: @json($reports),
        loading: null,
        showToast: false,
        toastMessage: '',
        toastType: 'success',

        init() {
            // Load devices on init
            this.loadDevices();
        },

        async loadDevices() {
            try {
                const response = await fetch('{{ route("reports.devices") }}');
                const data = await response.json();
                this.devices = data;
            } catch (error) {
                console.error('Failed to load devices:', error);
            }
        },

        validateDateRange() {
            const start = new Date(this.filters.start_date);
            const end = new Date(this.filters.end_date);
            const diffDays = Math.ceil((end - start) / (1000 * 60 * 60 * 24));

            if (diffDays < 0) {
                this.displayToast('Tanggal mulai tidak boleh lebih besar dari tanggal selesai', 'error');
                return false;
            }

            if (diffDays > 90) {
                this.displayToast('Rentang tanggal maksimal 90 hari', 'error');
                return false;
            }

            return true;
        },

        generateReport(reportType) {
            if (this.loading) return;
            
            if (!this.validateDateRange()) return;
            
            this.loading = reportType;

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = `/reports/generate/${reportType}`;
            form.style.display = 'none';

            // Filters
            Object.keys(this.filters).forEach(key => {
                if (this.filters[key]) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = this.filters[key];
                    form.appendChild(input);
                }
            });

            document.body.appendChild(form);

            // Submit form directly for download
            form.submit();
            
            this.displayToast('Laporan sedang di-generate...', 'success');
            
            setTimeout(() => {
                this.loading = null;
                document.body.removeChild(form);
            }, 2000);
        },

        displayToast(message, type = 'success') {
            this.toastMessage = message;
            this.toastType = type;
            this.showToast = true;
            setTimeout(() => {
                this.showToast = false;
            }, 5000);
        }
    }
}
</script>
@endsection
