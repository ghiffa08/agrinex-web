<x-filament-panels::page>
    <div x-data="productionFlasher()" @trigger-scan-ports.window="scanPorts()" class="space-y-6">
        <!-- 3-Column Bento Grid for Diagnostics and Monitor -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Box 1: Status Koneksi & Live Monitor (1-column span) -->
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-filament::icon icon="heroicon-o-signal" class="h-5 w-5 text-emerald-500" />
                        <span>Status Koneksi & Live Monitor</span>
                    </div>
                </x-slot>

                <div class="space-y-6">
                    <!-- Centralized Pulsating Indicator -->
                    <div class="flex flex-col items-center justify-center p-6 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-800">
                        <span class="relative flex h-10 w-10 mb-3">
                            <span :class="monitoring ? 'animate-ping' : ''" class="absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span :class="monitoring ? 'bg-emerald-500' : 'bg-gray-400'" class="relative inline-flex rounded-full h-10 w-10"></span>
                        </span>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400" x-text="monitoring ? 'Live Stream Active' : 'Monitor Idle'">Monitor Idle</span>
                    </div>

                    <!-- Telemetry Data Grid -->
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="bg-gray-50 dark:bg-gray-900/30 p-3 rounded-lg border border-gray-100 dark:border-gray-800">
                            <span class="text-[10px] uppercase font-bold text-gray-400 dark:text-gray-500">Temperature</span>
                            <div class="text-xl font-extrabold mt-1 text-gray-900 dark:text-white">
                                <span x-text="telemetry.temp">--</span> <span class="text-sm font-normal text-gray-500">°C</span>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/30 p-3 rounded-lg border border-gray-100 dark:border-gray-800">
                            <span class="text-[10px] uppercase font-bold text-gray-400 dark:text-gray-500">Soil Moisture</span>
                            <div class="text-xl font-extrabold mt-1 text-gray-900 dark:text-white">
                                <span x-text="telemetry.soil">--</span> <span class="text-sm font-normal text-gray-500">%</span>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/30 p-3 rounded-lg border border-gray-100 dark:border-gray-800">
                            <span class="text-[10px] uppercase font-bold text-gray-400 dark:text-gray-500">Flow Rate</span>
                            <div class="text-xl font-extrabold mt-1 text-gray-900 dark:text-white">
                                <span x-text="telemetry.flow">--</span> <span class="text-xs font-normal text-gray-500">L/m</span>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/30 p-3 rounded-lg border border-gray-100 dark:border-gray-800">
                            <span class="text-[10px] uppercase font-bold text-gray-400 dark:text-gray-500">Total Vol</span>
                            <div class="text-xl font-extrabold mt-1 text-gray-900 dark:text-white">
                                <span x-text="telemetry.vol">--</span> <span class="text-xs font-normal text-gray-500">L</span>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/30 p-3 rounded-lg border border-gray-100 dark:border-gray-800 col-span-2">
                            <span class="text-[10px] uppercase font-bold text-gray-400 dark:text-gray-500 block mb-2">Power Statistics</span>
                            <div class="grid grid-cols-3 gap-2 text-center text-xs font-semibold text-gray-700 dark:text-gray-300">
                                <div class="bg-white dark:bg-gray-900 p-1.5 rounded border dark:border-gray-850"><span x-text="telemetry.voltage">--</span>V</div>
                                <div class="bg-white dark:bg-gray-900 p-1.5 rounded border dark:border-gray-850"><span x-text="telemetry.current">--</span>mA</div>
                                <div class="bg-white dark:bg-gray-900 p-1.5 rounded border dark:border-gray-850"><span x-text="telemetry.power">--</span>mW</div>
                            </div>
                        </div>
                    </div>

                    <!-- Diagnostics Control Button -->
                    <x-filament::button @click="toggleMonitoring()" :color="monitoring ? 'danger' : 'success'" class="w-full">
                        <span x-text="monitoring ? 'Stop HTTP Diagnostics' : 'Start HTTP Diagnostics'"></span>
                    </x-filament::button>

                    <!-- Actuator Toggle -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/30 rounded-xl border border-gray-100 dark:border-gray-800">
                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">Relay Actuator (D2)</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="relayState" @change="toggleRelay()" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                </div>
            </x-filament::section>

            <!-- Box 2: Serial Console Terminal (2-column span) -->
            <x-filament::section class="lg:col-span-2">
                <x-slot name="heading">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-filament::icon icon="heroicon-o-command-line" class="h-5 w-5 text-emerald-500" />
                            <span>Serial Console Terminal</span>
                        </div>
                        <div>
                            <select x-model="baudRate" class="text-xs rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 py-1.5 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="9600">9600 Baud</option>
                                <option value="115200">115200 Baud</option>
                                <option value="921600">921600 Baud</option>
                            </select>
                        </div>
                    </div>
                </x-slot>

                <div class="space-y-4">
                    <!-- High-End Terminal Screen -->
                    <div id="serial-terminal-console" class="bg-gray-950 border border-gray-900 rounded-xl p-4 font-mono text-xs text-emerald-400 h-[260px] overflow-y-auto space-y-1.5 select-text scroll-smooth">
                        <template x-for="log in consoleLogs">
                            <div :class="log.color" class="break-all leading-relaxed" x-text="log.text"></div>
                        </template>
                        <div x-show="consoleLogs.length === 0" class="text-gray-600 text-center py-24 italic">Awaiting terminal connection... Select port & baud rate, then connect.</div>
                    </div>

                    <!-- Terminal Action Footer -->
                    <div class="grid grid-cols-3 gap-3 border-t dark:border-gray-800 pt-3">
                        <x-filament::button @click="connectTerminal()" :color="serialConnected ? 'danger' : 'gray'" size="sm">
                            <span x-text="serialConnected ? 'Disconnect Terminal' : 'Connect Terminal'"></span>
                        </x-filament::button>

                        <x-filament::button @click="toggleOTA()" :color="otaActive ? 'danger' : 'gray'" size="sm">
                            <span x-text="otaActive ? 'Disconnect OTA' : 'Connect OTA'"></span>
                        </x-filament::button>

                        <x-filament::button @click="clearConsoleLogs()" color="gray" size="sm">
                            Clear Terminal
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::section>
        </div>

        <!-- Box 3: Form Configuration & Flasher Controls (3-column span) -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-cog-8-tooth" class="h-5 w-5 text-emerald-500" />
                    <span>Firmware Configuration & Flasher Controls</span>
                </div>
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Target Firmware Select -->
                <div class="space-y-3">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Target Firmware</span>
                    <div class="grid grid-cols-3 gap-2">
                        <label :class="firmware === 'tester' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400' : 'border-gray-250 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50'" class="relative flex cursor-pointer rounded-lg border p-3 text-center transition-colors">
                            <input type="radio" x-model="firmware" value="tester" :disabled="isFlashing" class="sr-only">
                            <span class="block w-full text-xs font-semibold">Tester</span>
                        </label>
                        <label :class="firmware === 'sender' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400' : 'border-gray-250 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50'" class="relative flex cursor-pointer rounded-lg border p-3 text-center transition-colors">
                            <input type="radio" x-model="firmware" value="sender" :disabled="isFlashing" class="sr-only">
                            <span class="block w-full text-xs font-semibold">Sender</span>
                        </label>
                        <label :class="firmware === 'receiver' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400' : 'border-gray-250 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50'" class="relative flex cursor-pointer rounded-lg border p-3 text-center transition-colors">
                            <input type="radio" x-model="firmware" value="receiver" :disabled="isFlashing" class="sr-only">
                            <span class="block w-full text-xs font-semibold">Receiver</span>
                        </label>
                    </div>
                </div>

                <!-- Network Parameters Inputs -->
                <div class="space-y-3">
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Network Parameters</span>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" x-model="ssid" :disabled="isFlashing" placeholder="WiFi SSID" class="block w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-emerald-500 focus:border-emerald-500">
                        <input type="password" x-model="password" :disabled="isFlashing" placeholder="WiFi Password" class="block w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>

                <!-- Hardware Pin Map Inputs -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Hardware Pin Map</span>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="overridePins" :disabled="isFlashing" class="h-3.5 w-3.5 rounded text-emerald-600 focus:ring-emerald-500">
                            <span class="text-[10px] font-medium text-gray-500 uppercase">Override Defaults</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <input type="number" min="0" max="39" x-model="pinTemp" :disabled="!overridePins || isFlashing" placeholder="Temp" class="block w-full text-center text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-emerald-500 focus:border-emerald-500">
                        <input type="number" min="0" max="39" x-model="pinFlow" :disabled="!overridePins || isFlashing" placeholder="Flow" class="block w-full text-center text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-emerald-500 focus:border-emerald-500">
                        <input type="number" min="0" max="39" x-model="pinSoil" :disabled="!overridePins || isFlashing" placeholder="Soil" class="block w-full text-center text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
            </div>

            <!-- Target Port & Flasher Action Buttons -->
            <div class="mt-6 pt-6 border-t dark:border-gray-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <select x-model="portValue" :disabled="isFlashing || isScanning" class="text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500 min-w-[220px]">
                        <option value="">-- Select Target COM Port --</option>
                        <template x-for="p in ports">
                            <option :value="p" x-text="p"></option>
                        </template>
                    </select>
                    <span x-show="isScanning" class="text-xs text-gray-500 dark:text-gray-400 animate-pulse">Scanning server ports...</span>
                </div>

                <div class="flex gap-3">
                    <x-filament::button @click="startFlashing(true)" :disabled="isFlashing || !portValue || !ssid || !password" color="warning">
                        Clean Flash
                    </x-filament::button>
                    <x-filament::button @click="startFlashing(false)" :disabled="isFlashing || !portValue || !ssid || !password" color="success">
                        Flash & Provision
                    </x-filament::button>
                </div>
            </div>

            <!-- Log Console -->
            <div class="mt-6 border-t dark:border-gray-800 pt-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Flasher Log Stream (PlatformIO)</span>
                    <button @click="clearLogs()" class="text-[10px] font-bold text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">Clear Logs</button>
                </div>
                <div id="flasher-console" class="bg-gray-950 border border-gray-900 rounded-xl p-4 font-mono text-xs text-emerald-400 h-[220px] overflow-y-auto space-y-1 select-text scroll-smooth">
                    <template x-for="log in logs">
                        <div :class="log.color" class="break-all" x-text="log.text"></div>
                    </template>
                    <div x-show="logs.length === 0" class="text-gray-600 text-center py-20 italic">Awaiting flashing triggers...</div>
                </div>
            </div>
        </x-filament::section>
    </div>

    <!-- Script Block for Hardware Interaction -->
    <script>
        (function() {
            const registerComponent = () => {
                if (window.Alpine) {
                    window.Alpine.data('productionFlasher', () => ({
                        firmware: 'tester',
                        ssid: 'AgriNex_Tester',
                        password: 'password123',

                        // Hardware Overrides
                        overridePins: false,
                        pinTemp: '2',
                        pinFlow: '4',
                        pinSoil: '1',

                        isFlashing: false,
                        isScanning: false,
                        baudRate: '115200',
                        logs: [],

                        // Server Serial Ports
                        ports: [],
                        portValue: '',

                        // Telemetry Polling
                        monitoring: false,
                        pollTimeout: null,
                        relayState: false,
                        telemetry: {
                            temp: '--',
                            soil: '--',
                            flow: '--',
                            vol: '--',
                            voltage: '--',
                            current: '--',
                            power: '--'
                        },

                        // Serial Console Specifics
                        serialConnected: false,
                        serialSource: null,
                        otaActive: false,
                        otaInterval: null,
                        consoleLogs: [],

                        init() {
                            this.addLog('SYSTEM: FILAMENT JIG PANEL COMPONENT LOADED.', 'text-slate-400');
                            this.scanPorts();
                        },

                        addLog(text, color = 'text-emerald-400') {
                            const timestamp = new Date().toLocaleTimeString();
                            this.logs.push({ text: `[${timestamp}] ${text}`, color });
                            this.$nextTick(() => {
                                const consoleEl = document.getElementById('flasher-console');
                                if (consoleEl) {
                                    consoleEl.scrollTop = consoleEl.scrollHeight;
                                }
                            });
                        },

                        clearLogs() {
                            this.logs = [];
                        },

                        addConsoleLog(text, color = 'text-emerald-400') {
                            const timestamp = new Date().toLocaleTimeString();
                            this.consoleLogs.push({ text: `[${timestamp}] ${text}`, color });
                            if (this.consoleLogs.length > 500) {
                                this.consoleLogs.shift();
                            }
                            this.$nextTick(() => {
                                const consoleEl = document.getElementById('serial-terminal-console');
                                if (consoleEl) {
                                    consoleEl.scrollTop = consoleEl.scrollHeight;
                                }
                            });
                        },

                        clearConsoleLogs() {
                            this.consoleLogs = [];
                        },

                        async scanPorts() {
                            this.isScanning = true;
                            try {
                                this.addLog('Scanning server COM ports...', 'text-slate-400');
                                const res = await fetch('/admin/flasher/ports');
                                const data = await res.json();
                                this.ports = data.ports || [];

                                if (this.ports.length > 0) {
                                    this.portValue = this.ports[0];
                                    this.addLog(`Found ${this.ports.length} serial ports on server.`, 'text-emerald-500');
                                } else {
                                    this.addLog('No serial ports found on server. Ensure USB serial device is connected.', 'text-amber-500');
                                }
                            } catch (err) {
                                this.addLog(`Failed to scan server ports: ${err.message}`, 'text-rose-500');
                            } finally {
                                this.isScanning = false;
                            }
                        },

                        connectTerminal() {
                            if (!this.portValue) {
                                this.addConsoleLog('ERROR: Select a COM port first!', 'text-rose-500');
                                return;
                            }
                            if (this.serialConnected) {
                                this.disconnectTerminal();
                                return;
                            }

                            this.addConsoleLog(`Connecting to ${this.portValue} @ ${this.baudRate} Baud...`, 'text-sky-500');
                            try {
                                this.serialSource = new EventSource(`/admin/flasher/serial-stream?port=${encodeURIComponent(this.portValue)}&baud=${this.baudRate}`);
                                this.serialConnected = true;

                                this.serialSource.addEventListener('log', (e) => {
                                    try {
                                        const data = JSON.parse(e.data);
                                        let text = data.text;
                                        let color = 'text-slate-300';

                                        if (text.includes('[Sensor]')) color = 'text-sky-400';
                                        else if (text.includes('[WiFi]')) color = 'text-emerald-400';
                                        else if (text.includes('[HTTP]')) color = 'text-amber-400';
                                        else if (text.toUpperCase().includes('ERROR')) color = 'text-rose-500';

                                        this.addConsoleLog(text, color);
                                    } catch (err) {
                                        this.addConsoleLog(e.data, 'text-slate-400');
                                    }
                                });

                                this.serialSource.onerror = (err) => {
                                    this.addConsoleLog('Serial stream disconnected or encountered error.', 'text-rose-500');
                                    this.disconnectTerminal();
                                };
                            } catch (err) {
                                this.addConsoleLog(`Connection failed: ${err.message}`, 'text-rose-500');
                                this.serialConnected = false;
                            }
                        },

                        disconnectTerminal() {
                            if (this.serialSource) {
                                this.serialSource.close();
                                this.serialSource = null;
                            }
                            this.serialConnected = false;
                            this.addConsoleLog('Terminal Disconnected.', 'text-slate-400');
                        },

                        toggleOTA() {
                            this.otaActive = !this.otaActive;
                            if (this.otaActive) {
                                this.addConsoleLog('OTA monitor – polling http://192.168.4.1/api/sensor...', 'text-sky-400');
                                this.otaInterval = setInterval(async () => {
                                    try {
                                        const res = await fetch('/admin/flasher/sensor');
                                        if (res.ok) {
                                            const data = await res.json();
                                            const line = `Temp:${data.temperature ?? '?'}°C | Soil:${data.soil_moisture ?? '?'}% | Flow:${data.flow_rate ?? '?'}L/min | Vol:${data.total_volume ?? '?'}L | V:${data.voltage ?? '?'}V | I:${data.current ?? '?'}mA | P:${data.power ?? '?'}mW`;
                                            this.addConsoleLog(line, 'text-sky-400');
                                        } else {
                                            this.addConsoleLog(`HTTP ${res.status}`, 'text-amber-500');
                                        }
                                    } catch (err) {
                                        this.addConsoleLog(`OTA error: ${err.message}`, 'text-rose-500');
                                    }
                                }, 1000);
                            } else {
                                clearInterval(this.otaInterval);
                                this.otaInterval = null;
                                this.addConsoleLog('OTA monitor stopped.', 'text-amber-500');
                            }
                        },

                        async startFlashing(clean = false) {
                            this.isFlashing = true;
                            this.clearLogs();
                            this.addLog(`--- STARTING ${clean ? 'CLEAN FLASH' : 'FLASH & PROVISION'} ON SERVER ---`, 'text-amber-500');

                            try {
                                const response = await fetch('/admin/flasher/flash', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        firmware: this.firmware,
                                        ssid: this.ssid,
                                        password: this.password,
                                        port: this.portValue,
                                        clean: clean,
                                        override: this.overridePins,
                                        pin_temp: this.pinTemp,
                                        pin_flow: this.pinFlow,
                                        pin_soil: this.pinSoil
                                    })
                                });

                                if (!response.ok) {
                                    throw new Error('HTTP Error ' + response.status);
                                }

                                const reader = response.body.getReader();
                                const decoder = new TextDecoder();
                                let buffer = '';

                                while (true) {
                                    const { value, done } = await reader.read();
                                    if (done) break;

                                    buffer += decoder.decode(value, { stream: true });
                                    const lines = buffer.split('\n');
                                    buffer = lines.pop();

                                    for (const line of lines) {
                                        if (line.startsWith('data: ')) {
                                            try {
                                                const data = JSON.parse(line.substring(6));
                                                this.addLog(data.text, data.color || 'text-slate-300');
                                            } catch (e) {
                                                this.addLog(line.substring(6), 'text-slate-400');
                                            }
                                        }
                                    }
                                }

                                this.addLog('SUCCESS: Flash task execution completed.', 'text-emerald-500');
                                this.toggleMonitoring(true);
                            } catch (err) {
                                this.addLog(`Flashing execution failed: ${err.message}`, 'text-rose-500');
                            } finally {
                                this.isFlashing = false;
                            }
                        },

                        toggleMonitoring(forceOn = false) {
                            if (forceOn) this.monitoring = false;
                            this.monitoring = !this.monitoring;

                            if (this.monitoring) {
                                this.addLog('Real-time diagnostics active. Polling http://192.168.4.1/api/sensor', 'text-sky-500');
                                this.pollSensorData();
                            } else {
                                if (this.pollTimeout) clearTimeout(this.pollTimeout);
                                this.pollTimeout = null;
                                this.telemetry = { temp: '--', soil: '--', flow: '--', vol: '--', voltage: '--', current: '--', power: '--' };
                                this.addLog('Diagnostics monitoring stopped.', 'text-slate-400');
                            }
                        },

                        async pollSensorData() {
                            if (!this.monitoring) return;
                            try {
                                const response = await fetch('/admin/flasher/sensor');
                                if (!response.ok) {
                                    throw new Error(`HTTP error ${response.status}`);
                                }
                                const data = await response.json();
                                if (data) {
                                    this.telemetry = {
                                        temp: data.temperature !== undefined ? parseFloat(data.temperature).toFixed(1) : '--',
                                        soil: data.soil_moisture !== undefined ? parseFloat(data.soil_moisture).toFixed(1) : '--',
                                        flow: data.flow_rate !== undefined ? parseFloat(data.flow_rate).toFixed(2) : '--',
                                        vol: data.total_volume !== undefined ? parseFloat(data.total_volume).toFixed(1) : '--',
                                        voltage: data.voltage !== undefined ? parseFloat(data.voltage).toFixed(2) : '--',
                                        current: data.current !== undefined ? parseFloat(data.current).toFixed(1) : '--',
                                        power: data.power !== undefined ? parseFloat(data.power).toFixed(0) : '--'
                                    };

                                    this.addLog(`[LIVE DATA] Temp:${this.telemetry.temp}C | Soil:${this.telemetry.soil}% | Flow:${this.telemetry.flow}L/m | Vol:${this.telemetry.vol}L | Pwr:${this.telemetry.power}mW`, 'text-sky-400');
                                }
                            } catch (err) {
                                this.addLog(`Diagnostics poll failure: ${err.message}`, 'text-rose-500');
                                this.telemetry = { temp: '--', soil: '--', flow: '--', vol: '--', voltage: '--', current: '--', power: '--' };
                            } finally {
                                if (this.monitoring) {
                                    this.pollTimeout = setTimeout(() => this.pollSensorData(), 1200);
                                }
                            }
                        },

                        async toggleRelay() {
                            const stateVal = this.relayState ? '1' : '0';
                            this.addLog(`[ACTUATOR] Setting Relay output D2/GPIO3 to ${this.relayState ? 'HIGH (ON)' : 'LOW (OFF)'}...`, 'text-amber-500');
                            try {
                                const response = await fetch('/admin/flasher/relay', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ state: stateVal })
                                });

                                if (!response.ok) {
                                    throw new Error(`HTTP error ${response.status}`);
                                }

                                this.addLog(`[ACTUATOR] Relay state changed to ${this.relayState ? 'ON' : 'OFF'} successfully.`, 'text-emerald-500');
                            } catch (err) {
                                this.addLog(`[ACTUATOR] Relay toggle failed: ${err.message}`, 'text-rose-500');
                                this.relayState = !this.relayState;
                            }
                        }
                    }));
                }
            };

            if (window.Alpine) {
                registerComponent();
            } else {
                document.addEventListener('alpine:init', registerComponent);
            }
        })();
    </script>
</x-filament-panels::page>
