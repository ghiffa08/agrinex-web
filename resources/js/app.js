import './bootstrap';
import './spa'; // Import custom Single Page Application router
import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';
import flatpickr from 'flatpickr';

// Expose globally for inline scripts & blade templates
window.Alpine = Alpine;
window.ApexCharts = ApexCharts;
window.flatpickr = flatpickr;

Alpine.start();
