@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div x-data="{ 
    activeTab: 'users', 
    addUserModal: false, 
    editUserModal: false, 
    addTreatmentModal: false,
    selectedUser: { id: null, username: '', full_name: '', email: '', phone_number: '', role: 'viewer' }
}" class="space-y-6">
    
    {{-- Page Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90 font-bold">System Settings</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure users, treatments, and system parameters</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="rounded-lg bg-success-50 p-4 text-theme-sm text-success-800 dark:bg-success-500/10 dark:text-success-400 flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-lg bg-error-50 p-4 text-theme-sm text-error-800 dark:bg-error-500/10 dark:text-error-400 flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Settings Tabs -->
    <div class="flex flex-wrap border-b border-gray-200 dark:border-gray-800 gap-2 mb-6">
        <button @click="activeTab = 'users'" 
                :class="activeTab === 'users' ? 'border-brand-500 text-brand-500 dark:border-brand-400 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                class="px-4 py-2.5 font-medium text-sm border-b-2 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            User Management
        </button>
        <button @click="activeTab = 'system'" 
                :class="activeTab === 'system' ? 'border-brand-500 text-brand-500 dark:border-brand-400 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                class="px-4 py-2.5 font-medium text-sm border-b-2 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            System Info
        </button>
        <button @click="activeTab = 'treatments'" 
                :class="activeTab === 'treatments' ? 'border-brand-500 text-brand-500 dark:border-brand-400 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                class="px-4 py-2.5 font-medium text-sm border-b-2 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
            </svg>
            Treatments
        </button>
        <button @click="activeTab = 'notifications'" 
                :class="activeTab === 'notifications' ? 'border-brand-500 text-brand-500 dark:border-brand-400 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                class="px-4 py-2.5 font-medium text-sm border-b-2 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            Notifications
        </button>
    </div>

    <!-- Tab Contents -->
    <div>
        <!-- Users Tab -->
        <div x-show="activeTab === 'users'" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">User Management ({{ count($users) }})</h3>
                <button @click="addUserModal = true" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-3.5 py-2 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Add User
                </button>
            </div>

            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                            <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Username</th>
                            <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Full Name</th>
                            <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Role</th>
                            <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="py-3 px-6 text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Created</th>
                            <th class="py-3 px-6 text-center font-medium text-gray-500 text-theme-xs dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="py-3 px-6 whitespace-nowrap">
                                <span class="text-theme-sm font-semibold text-gray-800 dark:text-white">{{ $user->username }}</span>
                                @if ($user->id == auth()->id())
                                    <span class="rounded-full bg-sky-50 text-sky-500 px-2 py-0.5 text-[10px] font-semibold ml-1 dark:bg-sky-500/15 dark:text-sky-400">You</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-700 dark:text-gray-300">{{ $user->full_name }}</td>
                            <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
                            <td class="py-3 px-6 whitespace-nowrap">
                                <span class="rounded-full bg-brand-50 px-2.5 py-0.5 text-theme-xs font-semibold text-brand-600 dark:bg-brand-500/15">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap">
                                <span class="rounded-full px-2.5 py-0.5 text-theme-xs font-semibold {{ $user->is_active ? 'bg-success-50 text-success-600 dark:bg-success-500/15' : 'bg-error-50 text-error-600 dark:bg-error-500/15' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap text-theme-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                            </td>
                            <td class="py-3 px-6 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2.5">
                                    <button @click="
                                        selectedUser = { 
                                            id: {{ $user->id }}, 
                                            username: '{{ $user->username }}', 
                                            full_name: '{{ $user->full_name }}', 
                                            email: '{{ $user->email }}', 
                                            phone_number: '{{ $user->phone_number ?? '' }}', 
                                            role: '{{ $user->role }}' 
                                        };
                                        editUserModal = true;
                                    " class="text-brand-500 hover:text-brand-600 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    @if ($user->id != auth()->id())
                                        <button onclick="deleteUser({{ $user->id }}, '{{ $user->username }}')" class="text-error-500 hover:text-error-600 transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-10 px-6 text-center text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-700 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                No users found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- System Info Tab -->
        <div x-show="activeTab === 'system'" style="display: none;" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Database Info --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Database Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                        <span class="text-gray-500 dark:text-gray-400">Database Name</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ config('database.connections.mysql.database') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                        <span class="text-gray-500 dark:text-gray-400">Host</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ config('database.connections.mysql.host') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                        <span class="text-gray-500 dark:text-gray-400">Port</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ config('database.connections.mysql.port') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-theme-sm pb-2">
                        <span class="text-gray-500 dark:text-gray-400">Connection</span>
                        <span class="rounded-full bg-success-50 px-2.5 py-0.5 text-theme-xs font-semibold text-success-600 dark:bg-success-500/15">Connected</span>
                    </div>
                </div>
            </div>

            {{-- Application Info --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Application Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                        <span class="text-gray-500 dark:text-gray-400">App Name</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ config('app.name') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                        <span class="text-gray-500 dark:text-gray-400">Environment</span>
                        <span class="rounded-full px-2.5 py-0.5 text-theme-xs font-semibold {{ config('app.env') == 'production' ? 'bg-success-50 text-success-600' : 'bg-warning-50 text-warning-600' }}">
                            {{ ucfirst(config('app.env')) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                        <span class="text-gray-500 dark:text-gray-400">Debug Mode</span>
                        <span class="rounded-full px-2.5 py-0.5 text-theme-xs font-semibold {{ config('app.debug') ? 'bg-error-50 text-error-600' : 'bg-success-50 text-success-600' }}">
                            {{ config('app.debug') ? 'ON' : 'OFF' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-theme-sm border-b border-gray-100 dark:border-gray-850 pb-2">
                        <span class="text-gray-500 dark:text-gray-400">Laravel Version</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between items-center text-theme-sm pb-2">
                        <span class="text-gray-500 dark:text-gray-400">PHP Version</span>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ PHP_VERSION }}</span>
                    </div>
                </div>
            </div>

            {{-- Statistics --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-5">System Statistics</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center p-4 border border-gray-150 rounded-xl dark:border-gray-800">
                        <h4 class="text-2xl font-bold text-brand-500">{{ App\Models\User::count() }}</h4>
                        <span class="text-xs text-gray-500 mt-1 block">Total Users</span>
                    </div>
                    <div class="text-center p-4 border border-gray-150 rounded-xl dark:border-gray-800">
                        <h4 class="text-2xl font-bold text-success-500">{{ App\Models\Node::count() }}</h4>
                        <span class="text-xs text-gray-500 mt-1 block">Sensor Nodes</span>
                    </div>
                    <div class="text-center p-4 border border-gray-150 rounded-xl dark:border-gray-800">
                        <h4 class="text-2xl font-bold text-sky-500">{{ App\Models\SensorNodeData::count() }}</h4>
                        <span class="text-xs text-gray-500 mt-1 block">Sensor Readings</span>
                    </div>
                    <div class="text-center p-4 border border-gray-150 rounded-xl dark:border-gray-800">
                        <h4 class="text-2xl font-bold text-brand-500">{{ App\Models\IrrigateLog::count() }}</h4>
                        <span class="text-xs text-gray-500 mt-1 block">Irrigation Events</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Treatments Tab -->
        <div x-show="activeTab === 'treatments'" style="display: none;" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Treatment Management</h3>
                <button @click="addTreatmentModal = true" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-3.5 py-2 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Add Treatment
                </button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse ($treatments as $treatment)
                        <div class="border border-gray-150 rounded-xl p-5 dark:border-gray-800 relative bg-gray-50/10 dark:bg-gray-900/10"
                            style="border-left-width: 4px; border-left-color: {{ $treatment->color_code }} !important;">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="font-bold text-gray-800 dark:text-white">{{ $treatment->treatment_name }}</h4>
                                <button class="text-brand-500 hover:text-brand-600 transition-colors"
                                    onclick="editTreatment({{ $treatment->treatment_id }})">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block font-medium">FC Target</span>
                                    <span class="text-theme-sm font-semibold text-gray-800 dark:text-white">{{ $treatment->target_fc_percent }}%</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block font-medium">Threshold</span>
                                    <span class="text-theme-sm font-semibold text-gray-800 dark:text-white">{{ $treatment->irrigation_threshold_percent }}%</span>
                                </div>
                                <div class="col-span-2 pt-2.5 border-t border-gray-100 dark:border-gray-850">
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block font-medium">Description</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">{{ $treatment->description }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-10">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-700 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">No treatments configured yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Notifications Tab -->
        <div x-show="activeTab === 'notifications'" style="display: none;" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Notification Settings</h3>
                <button type="button" onclick="saveNotificationSettings()" class="inline-flex items-center gap-2 rounded-lg bg-success-500 px-3.5 py-2 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-success-600 transition-colors">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Save Settings
                </button>
            </div>
            <div class="p-6">
                <form id="notificationSettingsForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border border-gray-150 rounded-xl p-5 dark:border-gray-800 bg-gray-50/10 dark:bg-gray-900/10">
                        <h4 class="font-bold text-gray-850 dark:text-white mb-4">Alert Notifications</h4>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" id="emailAlerts" checked class="rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-700">
                                <span class="text-theme-sm text-gray-700 dark:text-gray-300">Email notifications for alerts</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" id="criticalOnly" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-700">
                                <span class="text-theme-sm text-gray-700 dark:text-gray-300">Critical alerts only</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" id="smsAlerts" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-700">
                                <span class="text-theme-sm text-gray-700 dark:text-gray-300">SMS notifications (if available)</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="border border-gray-150 rounded-xl p-5 dark:border-gray-800 bg-gray-50/10 dark:bg-gray-900/10">
                        <h4 class="font-bold text-gray-850 dark:text-white mb-4">System Notifications</h4>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" id="irrigationNotif" checked class="rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-700">
                                <span class="text-theme-sm text-gray-700 dark:text-gray-300">Irrigation events</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" id="sensorNotif" checked class="rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-700">
                                <span class="text-theme-sm text-gray-700 dark:text-gray-300">Sensor anomalies</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" id="dailyReport" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-700">
                                <span class="text-theme-sm text-gray-700 dark:text-gray-300">Daily summary reports</span>
                            </label>
                        </div>
                    </div>

                    <div class="border border-gray-150 rounded-xl p-5 dark:border-gray-800 bg-gray-50/10 dark:bg-gray-900/10 md:col-span-2">
                        <h4 class="font-bold text-gray-850 dark:text-white mb-3">Email Recipients</h4>
                        <textarea rows="3" placeholder="Enter email addresses (comma-separated)&#10;example: user1@example.com, user2@example.com"
                                  class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-805 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90"></textarea>
                        <p class="text-xs text-gray-400 mt-1">Add email addresses to receive notifications</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div x-show="addUserModal" class="fixed inset-0 z-[99] flex items-center justify-center p-4" style="display: none;" x-cloak>
        <div @click="addUserModal = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-950 border border-gray-100 dark:border-gray-850">
            <form action="{{ route('settings.users.store') }}" method="POST">
                @csrf
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-5">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Add New User
                    </h3>
                    <button type="button" @click="addUserModal = false" class="text-gray-400 hover:text-gray-650 dark:hover:text-gray-250">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Username *</label>
                        <input type="text" name="username" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-805 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name *</label>
                        <input type="text" name="full_name" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-805 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Email *</label>
                        <input type="email" name="email" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-805 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                        <input type="text" name="phone_number" placeholder="Optional" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-805 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Password *</label>
                        <input type="password" name="password" required minlength="6" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-805 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                        <p class="text-xs text-gray-400 mt-1">Minimum 6 characters</p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Role *</label>
                        <select name="role" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-805 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                            <option value="viewer">Viewer (Read-only access)</option>
                            <option value="operator" selected>Operator (Can edit data)</option>
                            <option value="admin">Admin (Full access)</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800 mt-6">
                    <button type="button" @click="addUserModal = false" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 transition-colors">Cancel</button>
                    <button type="submit" class="rounded-lg bg-brand-500 px-5 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">Create User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div x-show="editUserModal" class="fixed inset-0 z-[99] flex items-center justify-center p-4" style="display: none;" x-cloak>
        <div @click="editUserModal = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-950 border border-gray-100 dark:border-gray-850">
            <form :action="`/settings/users/${selectedUser.id}`" method="POST">
                @csrf
                @method('PUT')
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-5">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit User
                    </h3>
                    <button type="button" @click="editUserModal = false" class="text-gray-400 hover:text-gray-650 dark:hover:text-gray-250">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Username *</label>
                        <input type="text" name="username" :value="selectedUser.username" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-855 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name *</label>
                        <input type="text" name="full_name" :value="selectedUser.full_name" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-855 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Email *</label>
                        <input type="email" name="email" :value="selectedUser.email" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-855 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                        <input type="text" name="phone_number" :value="selectedUser.phone_number" placeholder="Optional" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-855 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                        <input type="password" name="password" minlength="6" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-855 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                        <p class="text-xs text-gray-400 mt-1">Leave blank to keep current password. Minimum 6 characters if changing.</p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Role *</label>
                        <select name="role" :value="selectedUser.role" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-855 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                            <option value="viewer">Viewer (Read-only access)</option>
                            <option value="operator">Operator (Can edit data)</option>
                            <option value="admin">Admin (Full access)</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800 mt-6">
                    <button type="button" @click="editUserModal = false" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 transition-colors">Cancel</button>
                    <button type="submit" class="rounded-lg bg-brand-500 px-5 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">Update User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Treatment Modal -->
    <div x-show="addTreatmentModal" class="fixed inset-0 z-[99] flex items-center justify-center p-4" style="display: none;" x-cloak>
        <div @click="addTreatmentModal = false" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-950 border border-gray-100 dark:border-gray-850">
            <form id="addTreatmentForm">
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-5">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Add New Treatment
                    </h3>
                    <button type="button" @click="addTreatmentModal = false" class="text-gray-400 hover:text-gray-650 dark:hover:text-gray-250">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Treatment Name *</label>
                        <input type="text" name="treatment_name" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-855 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" rows="2" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-855 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Target FC (%)</label>
                            <input type="number" name="target_fc_percent" min="0" max="100" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-855 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Threshold (%)</label>
                            <input type="number" name="irrigation_threshold_percent" min="0" max="100" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-855 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:text-white/90">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Color Code</label>
                        <input type="color" name="color_code" value="#3b82f6" class="w-16 h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent p-1 cursor-pointer">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800 mt-6">
                    <button type="button" @click="addTreatmentModal = false" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 transition-colors">Cancel</button>
                    <button type="button" onclick="addTreatment()" class="rounded-lg bg-brand-500 px-5 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">Add Treatment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form (hidden) -->
<form id="deleteUserForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function deleteUser(userId, username) {
        if (confirm(`Are you sure you want to delete user "${username}"?\n\nThis action cannot be undone.`)) {
            const form = document.getElementById('deleteUserForm');
            form.action = `/settings/users/${userId}`;
            form.submit();
        }
    }

    function editTreatment(treatmentId) {
        alert('Edit treatment - ID: ' + treatmentId);
    }

    function addTreatment() {
        const form = document.getElementById('addTreatmentForm');
        if (form.checkValidity()) {
            alert('Treatment added successfully!\nThis feature will be fully implemented with backend integration.');
            // Dispatch custom Alpine event or simply refresh page/update state if it was a real endpoint
        } else {
            form.reportValidity();
        }
    }

    function saveNotificationSettings() {
        alert('Saving notification settings...\nThis feature will be fully implemented with backend integration.');
    }
</script>
@endsection
