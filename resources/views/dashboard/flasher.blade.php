@extends('layouts.admin')

@section('title', 'Production Flasher JIG')

@section('content')
    <div x-data="productionFlasher()" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 mb-3">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-600 dark:bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-semibold uppercase tracking-wider">Production JIG Tool</span>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white sm:text-3xl">
                        AgriNex Production JIG
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        ESP32 Node Calibration & Diagnostics Web Version
                    </p>
                </div>

                <!-- Connection Control Panel -->
                <div class="flex items-center gap-3">
                    <select x-model="portValue" :disabled="isFlashing || isScanning" class="block w-full min-w-[200px] rounded-lg border-slate-200 px-3 py-2 text-sm text-slate-900 disabled:opacity-50 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">-- Select Target COM Port --</option>
                        <template x-for="p in ports">
                            <option :value="p" x-text="p"></option>
                        </template>
                    </select>

                    <button @click="scanPorts()" :disabled="isScanning"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 disabled:opacity-50">
                        <svg :class="{'animate-spin': isScanning}" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Scan Ports</span>
                    </button>
                </div>
            </div>

            <!-- Tab Bar Navigation -->
            <div class="mb-6 border-b border-slate-200 dark:border-slate-700">
                <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                    <button @click="activeTab = 'provisioning'"
                            :class="activeTab === 'provisioning' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300'"
                            class="whitespace-nowrap border-b-2 py-3 px-1 text-sm font-medium transition-colors flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Provisioning
                    </button>
                    <button @click="activeTab = 'telemetry'"
                            :class="activeTab === 'telemetry' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300'"
                            class="whitespace-nowrap border-b-2 py-3 px-1 text-sm font-medium transition-colors flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" /></svg>
                        Telemetry
                    </button>
                    <button @click="activeTab = 'serial'"
                            :class="activeTab === 'serial' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300'"
                            class="whitespace-nowrap border-b-2 py-3 px-1 text-sm font-medium transition-colors flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        Serial Console
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div>
                <!-- TAB 1: PROVISIONING -->
                <div x-show="activeTab === 'provisioning'" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Config Panel -->
                    <div class="lg:col-span-5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                            <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                                Firmware Configuration
                            </h2>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Target Firmware -->
                            <div class="space-y-3">
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Target Firmware</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label :class="firmware === 'tester' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400' : 'border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50'"
                                           class="relative flex cursor-pointer rounded-lg border p-3 focus:outline-none transition-colors">
                                        <input type="radio" x-model="firmware" value="tester" :disabled="isFlashing" class="sr-only">
                                        <span class="block w-full text-center text-xs font-medium">Node Tester</span>
                                    </label>
                                    <label :class="firmware === 'sender' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400' : 'border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50'"
                                           class="relative flex cursor-pointer rounded-lg border p-3 focus:outline-none transition-colors">
                                        <input type="radio" x-model="firmware" value="sender" :disabled="isFlashing" class="sr-only">
                                        <span class="block w-full text-center text-xs font-medium">Sender 01</span>
                                    </label>
                                    <label :class="firmware === 'receiver' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400' : 'border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50'"
                                           class="relative flex cursor-pointer rounded-lg border p-3 focus:outline-none transition-colors">
                                        <input type="radio" x-model="firmware" value="receiver" :disabled="isFlashing" class="sr-only">
                                        <span class="block w-full text-center text-xs font-medium">Receiver 02</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Network Parameters -->
                            <div class="space-y-3">
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Network Parameters</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <input type="text" x-model="ssid" :disabled="isFlashing" placeholder="WiFi SSID" class="block w-full rounded-lg border-slate-200 px-3 py-2.5 text-sm text-slate-900 disabled:opacity-50 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>
                                    <div>
                                        <input type="password" x-model="password" :disabled="isFlashing" placeholder="WiFi Password" class="block w-full rounded-lg border-slate-200 px-3 py-2.5 text-sm text-slate-900 disabled:opacity-50 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Hardware Pin Map -->
                            <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Hardware Pin Map</label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" x-model="overridePins" :disabled="isFlashing" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600 dark:border-slate-600 dark:bg-slate-900 disabled:opacity-50">
                                        <span class="text-xs font-medium text-slate-600 dark:text-slate-300">Override Defaults</span>
                                    </label>
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">DS18B20 Temp</label>
                                        <input type="number" min="0" max="39" x-model="pinTemp" :disabled="!overridePins || isFlashing" class="block w-full text-center rounded-lg border-slate-200 px-3 py-2 text-sm text-slate-900 disabled:bg-slate-100 disabled:text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:disabled:bg-slate-800 focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Flow Meter</label>
                                        <input type="number" min="0" max="39" x-model="pinFlow" :disabled="!overridePins || isFlashing" class="block w-full text-center rounded-lg border-slate-200 px-3 py-2 text-sm text-slate-900 disabled:bg-slate-100 disabled:text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:disabled:bg-slate-800 focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Soil Moisture</label>
                                        <input type="number" min="0" max="39" x-model="pinSoil" :disabled="!overridePins || isFlashing" class="block w-full text-center rounded-lg border-slate-200 px-3 py-2 text-sm text-slate-900 disabled:bg-slate-100 disabled:text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:disabled:bg-slate-800 focus:border-emerald-500 focus:ring-emerald-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="pt-4 grid grid-cols-2 gap-3">
                                <button @click="startFlashing(true)" :disabled="isFlashing || !portValue || !ssid || !password"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                                    <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Clean Flash
                                </button>

                                <button @click="startFlashing(false)" :disabled="isFlashing || !portValue || !ssid || !password"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Flash & Provision
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Console Stream -->
                    <div class="lg:col-span-7 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm flex flex-col h-[520px] lg:h-auto">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                            <h2 class="text-base font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                                <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Flasher Log Stream
                            </h2>
                            <button @click="clearLogs()" class="text-xs font-medium text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">Clear Logs</button>
                        </div>

                        <div id="flasher-console" class="flex-1 bg-[#0F172A] p-4 overflow-y-auto font-mono text-xs text-slate-300 space-y-1 rounded-b-xl">
                            <template x-for="log in logs">
                                <div :class="log.color" class="break-all" x-text="log.text"></div>
                            </template>
                            <div x-show="logs.length === 0" class="text-slate-500 text-center py-24 italic">Awaiting flashing triggers...</div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: TELEMETRY -->
                <div x-show="activeTab === 'telemetry'">
                    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Calibration Diagnostics</h2>
                                <p class="text-xs text-slate-500 mt-1">Real-time HTTP Node Polling</p>
                            </div>

                            <div class="flex items-center gap-4">
                                <button @click="toggleMonitoring()"
                                        :class="monitoring ? 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100 dark:bg-rose-900/20 dark:border-rose-900 dark:text-rose-400' : 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-900 dark:text-emerald-400'"
                                        class="inline-flex items-center justify-center px-4 py-2 border rounded-lg text-sm font-medium transition-colors">
                                    <span x-text="monitoring ? 'Stop Diagnostics' : 'Start Diagnostics'"></span>
                                </button>

                                <div class="flex items-center gap-3 pl-4 border-l border-slate-200 dark:border-slate-700">
                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Actuator</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="relayState" @change="toggleRelay()" class="sr-only peer">
                                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Metrics Grid -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <!-- Temp -->
                                <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4 border border-slate-100 dark:border-slate-800">
                                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Temperature</p>
                                    <div class="mt-2 flex items-baseline gap-1">
                                        <span class="text-2xl font-bold text-slate-900 dark:text-white" x-text="telemetry.temp">--</span>
                                        <span class="text-sm text-slate-500">°C</span>
                                    </div>
                                </div>
                                <!-- Soil -->
                                <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4 border border-slate-100 dark:border-slate-800">
                                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Soil Moisture</p>
                                    <div class="mt-2 flex items-baseline gap-1">
                                        <span class="text-2xl font-bold text-slate-900 dark:text-white" x-text="telemetry.soil">--</span>
                                        <span class="text-sm text-slate-500">%</span>
                                    </div>
                                </div>
                                <!-- Flow -->
                                <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4 border border-slate-100 dark:border-slate-800">
                                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Flow Rate</p>
                                    <div class="mt-2 flex items-baseline gap-1">
                                        <span class="text-2xl font-bold text-slate-900 dark:text-white" x-text="telemetry.flow">--</span>
                                        <span class="text-sm text-slate-500">L/m</span>
                                    </div>
                                </div>
                                <!-- Volume -->
                                <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4 border border-slate-100 dark:border-slate-800">
                                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Total Volume</p>
                                    <div class="mt-2 flex items-baseline gap-1">
                                        <span class="text-2xl font-bold text-slate-900 dark:text-white" x-text="telemetry.vol">--</span>
                                        <span class="text-sm text-slate-500">L</span>
                                    </div>
                                </div>
                                <!-- Voltage -->
                                <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4 border border-slate-100 dark:border-slate-800">
                                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Bus Voltage</p>
                                    <div class="mt-2 flex items-baseline gap-1">
                                        <span class="text-2xl font-bold text-slate-900 dark:text-white" x-text="telemetry.voltage">--</span>
                                        <span class="text-sm text-slate-500">V</span>
                                    </div>
                                </div>
                                <!-- Current -->
                                <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4 border border-slate-100 dark:border-slate-800">
                                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Load Current</p>
                                    <div class="mt-2 flex items-baseline gap-1">
                                        <span class="text-2xl font-bold text-slate-900 dark:text-white" x-text="telemetry.current">--</span>
                                        <span class="text-sm text-slate-500">mA</span>
                                    </div>
                                </div>
                                <!-- Power -->
                                <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4 border border-slate-100 dark:border-slate-800 md:col-span-2">
                                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Power</p>
                                    <div class="mt-2 flex items-baseline gap-1">
                                        <span class="text-2xl font-bold text-slate-900 dark:text-white" x-text="telemetry.power">--</span>
                                        <span class="text-sm text-slate-500">mW</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: SERIAL CONSOLE -->
                <div x-show="activeTab === 'serial'">
                    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm flex flex-col h-[600px]">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 gap-4">
                            <div>
                                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Serial Console</h2>
                                <p class="text-xs text-slate-500 mt-1">Direct hardware stream</p>
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <select x-model="baudRate" class="rounded-lg border-slate-200 px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="9600">9600 Baud</option>
                                    <option value="115200">115200 Baud</option>
                                    <option value="921600">921600 Baud</option>
                                </select>

                                <button @click="connectTerminal()"
                                        :class="serialConnected ? 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600 dark:hover:bg-slate-700'"
                                        class="inline-flex items-center px-4 py-2 border rounded-lg text-sm font-medium transition-colors">
                                    <span x-text="serialConnected ? 'Disconnect Terminal' : 'Connect Terminal'"></span>
                                </button>

                                <button @click="toggleOTA()"
                                        :class="otaActive ? 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600 dark:hover:bg-slate-700'"
                                        class="inline-flex items-center px-4 py-2 border rounded-lg text-sm font-medium transition-colors">
                                    <span x-text="otaActive ? 'Disconnect OTA' : 'Connect OTA'"></span>
                                </button>

                                <button @click="clearConsoleLogs()" class="text-xs font-medium text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 px-2">Clear</button>
                            </div>
                        </div>

                        <div id="serial-terminal-console" class="flex-1 bg-[#0F172A] p-4 overflow-y-auto font-mono text-xs text-slate-300 space-y-1 rounded-b-xl">
                            <template x-for="log in consoleLogs">
                                <div :class="log.color" class="break-all" x-text="log.text"></div>
                            </template>
                            <div x-show="consoleLogs.length === 0" class="text-slate-500 text-center py-36 italic">Awaiting terminal connection...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Web Serial & Telemetry Logic -->
        <script>
            (function() {
                const registerComponent = () => {
                    if (window.Alpine) {
                        window.Alpine.data('productionFlasher', () => ({
                            activeTab: 'provisioning',
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

                            // Serial Console Tab Specifics
                            serialConnected: false,
                            serialSource: null,
                            otaActive: false,
                            otaInterval: null,
                            consoleLogs: [],

                            init() {
                                this.addLog('SYSTEM: STANDALONE JIG COMPONENT LOADED.', 'text-slate-400');
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

                                            // Color code patterns matching desktop JIG
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
                                if (forceOn) this.monitoring = false; // force activation
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
                                    this.relayState = !this.relayState; // revert state
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
@endsection