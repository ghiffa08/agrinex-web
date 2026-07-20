/**
 * Capacitor Native Plugins Integration
 * AgriNex Smart Drip - Push Notifications + Offline Strategy
 */

import { Capacitor } from '@capacitor/core';
import { PushNotifications } from '@capacitor/push-notifications';
import { Network } from '@capacitor/network';
import { Preferences } from '@capacitor/preferences';
import { App } from '@capacitor/app';
import { SplashScreen } from '@capacitor/splash-screen';

class AgriNexMobile {
    constructor() {
        this.isNative = Capacitor.isNativePlatform();
        this.fcmToken = null;
        this.isOnline = true;
        
        console.log('[AgriNex] Running on:', Capacitor.getPlatform());
        console.log('[AgriNex] Is Native:', this.isNative);
    }

    /**
     * Initialize all native features
     */
    async initialize() {
        if (!this.isNative) {
            console.log('[AgriNex] Running in web mode, skipping native init');
            await this.initServiceWorker();
            return;
        }

        console.log('[AgriNex] Initializing native features...');

        try {
            // Hide splash screen after init
            await SplashScreen.hide();

            // Initialize features
            await this.initPushNotifications();
            await this.initNetworkMonitoring();
            await this.initAppStateHandlers();
            await this.loadOfflineData();

            console.log('[AgriNex] Native initialization complete');
        } catch (error) {
            console.error('[AgriNex] Initialization error:', error);
        }
    }

    /**
     * Initialize Push Notifications
     */
    async initPushNotifications() {
        console.log('[AgriNex] Setting up push notifications...');

        // Request permission
        const permission = await PushNotifications.requestPermissions();
        
        if (permission.receive === 'granted') {
            await PushNotifications.register();
        } else {
            console.warn('[AgriNex] Push notification permission denied');
            return;
        }

        // Registration success - get FCM token
        PushNotifications.addListener('registration', async (token) => {
            console.log('[AgriNex] FCM Token:', token.value);
            this.fcmToken = token.value;
            
            // Send token to Laravel backend
            await this.registerFcmToken(token.value);
        });

        // Registration error
        PushNotifications.addListener('registrationError', (error) => {
            console.error('[AgriNex] FCM registration error:', error);
        });

        // Notification received (foreground)
        PushNotifications.addListener('pushNotificationReceived', (notification) => {
            console.log('[AgriNex] Notification received (foreground):', notification);
            
            // Show custom notification UI
            this.showNotificationBanner(notification);
        });

        // Notification clicked
        PushNotifications.addListener('pushNotificationActionPerformed', (action) => {
            console.log('[AgriNex] Notification clicked:', action);
            
            // Navigate based on notification data
            const data = action.notification.data;
            if (data.route) {
                window.location.href = data.route;
            }
        });
    }

    /**
     * Register FCM token dengan Laravel backend
     */
    async registerFcmToken(token) {
        try {
            const response = await fetch('/api/v1/mobile/fcm-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + await this.getAuthToken(),
                },
                body: JSON.stringify({
                    token: token,
                    device_info: {
                        platform: Capacitor.getPlatform(),
                        model: await this.getDeviceInfo(),
                        registered_at: new Date().toISOString(),
                    }
                })
            });

            const result = await response.json();
            
            if (result.success) {
                console.log('[AgriNex] FCM token registered successfully');
            } else {
                console.error('[AgriNex] Failed to register FCM token:', result.message);
            }
        } catch (error) {
            console.error('[AgriNex] Error registering FCM token:', error);
        }
    }

    /**
     * Initialize Network Monitoring
     */
    async initNetworkMonitoring() {
        console.log('[AgriNex] Setting up network monitoring...');

        // Check current status
        const status = await Network.getStatus();
        this.isOnline = status.connected;
        this.updateOnlineStatus(status.connected);

        // Listen for network changes
        Network.addListener('networkStatusChange', (status) => {
            console.log('[AgriNex] Network status changed:', status);
            this.isOnline = status.connected;
            this.updateOnlineStatus(status.connected);

            if (status.connected) {
                // Back online - sync offline data
                this.syncOfflineData();
            }
        });
    }

    /**
     * Update UI online/offline status
     */
    updateOnlineStatus(isOnline) {
        // Remove existing banner
        const existingBanner = document.getElementById('offline-banner');
        if (existingBanner) {
            existingBanner.remove();
        }

        if (!isOnline) {
            // Show offline banner
            const banner = document.createElement('div');
            banner.id = 'offline-banner';
            banner.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: #ef4444;
                color: white;
                padding: 0.75rem;
                text-align: center;
                font-weight: 600;
                z-index: 9999;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            `;
            banner.innerHTML = '📡 Anda sedang offline';
            document.body.prepend(banner);
        }
    }

    /**
     * Initialize App State Handlers
     */
    async initAppStateHandlers() {
        // App state change (foreground/background)
        App.addListener('appStateChange', ({ isActive }) => {
            console.log('[AgriNex] App state:', isActive ? 'active' : 'background');

            if (isActive) {
                // Refresh data when app comes to foreground
                this.refreshData();
            }
        });

        // Deep link handler
        App.addListener('appUrlOpen', (event) => {
            console.log('[AgriNex] Deep link:', event.url);
            // Handle deep links
        });
    }

    /**
     * Load cached offline data
     */
    async loadOfflineData() {
        try {
            // Load last dashboard data
            const { value: dashboardData } = await Preferences.get({ key: 'dashboard_cache' });
            if (dashboardData) {
                console.log('[AgriNex] Loaded cached dashboard data');
                // Populate UI with cached data
            }

            // Load devices list
            const { value: devicesData } = await Preferences.get({ key: 'devices_cache' });
            if (devicesData) {
                console.log('[AgriNex] Loaded cached devices data');
            }
        } catch (error) {
            console.error('[AgriNex] Error loading offline data:', error);
        }
    }

    /**
     * Save data for offline use
     */
    async saveOfflineData(key, data) {
        try {
            await Preferences.set({
                key: key,
                value: JSON.stringify({
                    data: data,
                    timestamp: new Date().toISOString(),
                })
            });
            console.log('[AgriNex] Saved offline data:', key);
        } catch (error) {
            console.error('[AgriNex] Error saving offline data:', error);
        }
    }

    /**
     * Sync offline data when back online
     */
    async syncOfflineData() {
        console.log('[AgriNex] Syncing offline data...');
        
        // TODO: Implement offline queue sync
        // Get pending actions from IndexedDB/Preferences
        // POST to server
        // Clear queue on success
    }

    /**
     * Refresh data from server
     */
    async refreshData() {
        if (!this.isOnline) {
            console.log('[AgriNex] Offline, skipping refresh');
            return;
        }

        console.log('[AgriNex] Refreshing data...');
        
        // Trigger Alpine.js refresh or fetch new data
        if (window.Alpine && window.Alpine.store('dashboard')) {
            window.Alpine.store('dashboard').refresh();
        }
    }

    /**
     * Show notification banner (custom UI)
     */
    showNotificationBanner(notification) {
        const banner = document.createElement('div');
        banner.style.cssText = `
            position: fixed;
            top: 1rem;
            left: 1rem;
            right: 1rem;
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            animation: slideDown 0.3s ease-out;
        `;
        banner.innerHTML = `
            <div style="font-weight: 600; margin-bottom: 0.25rem;">${notification.title}</div>
            <div style="color: #666; font-size: 0.9rem;">${notification.body}</div>
        `;

        document.body.appendChild(banner);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            banner.style.animation = 'slideUp 0.3s ease-in';
            setTimeout(() => banner.remove(), 300);
        }, 5000);
    }

    /**
     * Get auth token from localStorage/Preferences
     */
    async getAuthToken() {
        // Try localStorage first (web)
        const webToken = localStorage.getItem('auth_token');
        if (webToken) return webToken;

        // Try Preferences (native)
        const { value } = await Preferences.get({ key: 'auth_token' });
        return value || '';
    }

    /**
     * Get device info
     */
    async getDeviceInfo() {
        const info = await App.getInfo();
        return `${info.name} ${info.version}`;
    }

    /**
     * Initialize Service Worker (Web only)
     */
    async initServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('/sw.js');
                console.log('[AgriNex] Service Worker registered:', registration);
            } catch (error) {
                console.error('[AgriNex] Service Worker registration failed:', error);
            }
        }
    }
}

// Initialize when DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.agriNexMobile = new AgriNexMobile();
        window.agriNexMobile.initialize();
    });
} else {
    window.agriNexMobile = new AgriNexMobile();
    window.agriNexMobile.initialize();
}

// Add slideDown/slideUp animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideDown {
        from { transform: translateY(-100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes slideUp {
        from { transform: translateY(0); opacity: 1; }
        to { transform: translateY(-100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

export default AgriNexMobile;
