/**
 * OAuth Mobile Handler - AgriNex Smart Drip
 * Handle Google OAuth login di dalam app (tidak redirect ke browser eksternal)
 */

import { Capacitor } from '@capacitor/core';
import { Browser } from '@capacitor/browser';
import { App } from '@capacitor/app';

class OAuthMobileHandler {
    constructor() {
        this.isInitialized = false;
        this.appUrlScheme = 'agrinexsmartdrip://';
        this.baseUrl = 'https://smartdrip-system.agrinex.io';
    }

    init() {
        if (this.isInitialized) return;
        
        // Hanya init di native app
        if (!Capacitor.isNativePlatform()) {
            console.log('[OAuth] Running in browser, skip mobile OAuth handler');
            return;
        }

        this.setupDeepLinkListener();
        this.interceptGoogleLoginButton();
        this.isInitialized = true;
        
        console.log('[OAuth] Mobile handler initialized');
    }

    /**
     * Listen deep link dari OAuth callback
     */
    setupDeepLinkListener() {
        App.addListener('appUrlOpen', (event) => {
            console.log('[OAuth] Deep link received:', event.url);
            
            // Parse URL: agrinexsmartdrip://oauth/callback?token=xxx&session=yyy
            if (event.url.startsWith(this.appUrlScheme + 'oauth/callback')) {
                this.handleOAuthCallback(event.url);
            }
        });
    }

    /**
     * Intercept tombol Google Login dan buka in-app browser
     */
    interceptGoogleLoginButton() {
        // Wait for DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.attachListeners());
        } else {
            this.attachListeners();
        }
    }

    attachListeners() {
        // Intercept semua link ke /auth/google
        document.addEventListener('click', (e) => {
            const target = e.target.closest('a[href*="/auth/google"]');
            
            if (target && Capacitor.isNativePlatform()) {
                e.preventDefault();
                e.stopPropagation();
                
                console.log('[OAuth] Google login clicked, opening in-app browser');
                this.openGoogleOAuth();
            }
        }, true); // Use capture phase
    }

    /**
     * Buka Google OAuth di in-app browser
     */
    async openGoogleOAuth() {
        try {
            const oauthUrl = `${this.baseUrl}/auth/google?mobile=1`;
            
            console.log('[OAuth] Opening:', oauthUrl);
            
            // Buka in-app browser
            await Browser.open({
                url: oauthUrl,
                presentationStyle: 'popover',
                toolbarColor: '#22c55e'
            });

            // Listen for browser close
            Browser.addListener('browserFinished', () => {
                console.log('[OAuth] Browser closed');
            });

        } catch (error) {
            console.error('[OAuth] Error opening browser:', error);
            alert('Gagal membuka Google login');
        }
    }

    /**
     * Handle OAuth callback dari deep link
     */
    async handleOAuthCallback(url) {
        try {
            // Close in-app browser
            await Browser.close();

            // Parse URL parameters
            const urlObj = new URL(url);
            const token = urlObj.searchParams.get('token');
            const session = urlObj.searchParams.get('session');
            const error = urlObj.searchParams.get('error');

            if (error) {
                console.error('[OAuth] Callback error:', error);
                alert('Login gagal: ' + error);
                return;
            }

            if (token && session) {
                console.log('[OAuth] Login success, setting session...');
                
                // Set session cookie/token
                await this.setAuthSession(token, session);
                
                // Redirect ke dashboard
                window.location.href = '/';
                
            } else {
                console.error('[OAuth] Missing token or session');
                alert('Login gagal: Data tidak lengkap');
            }

        } catch (error) {
            console.error('[OAuth] Callback error:', error);
            alert('Login gagal: ' + error.message);
        }
    }

    /**
     * Set auth session dari OAuth callback
     */
    async setAuthSession(token, sessionId) {
        try {
            // Set session via API
            const response = await fetch(`${this.baseUrl}/api/v1/mobile/oauth-session`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    token: token,
                    session_id: sessionId
                }),
                credentials: 'include'
            });

            if (!response.ok) {
                throw new Error('Failed to set session');
            }

            const data = await response.json();
            console.log('[OAuth] Session set:', data);

            return data;

        } catch (error) {
            console.error('[OAuth] Set session error:', error);
            throw error;
        }
    }
}

// Auto-init saat di mobile
const oauthHandler = new OAuthMobileHandler();

if (Capacitor.isNativePlatform()) {
    oauthHandler.init();
}

// Export untuk testing
window.agriNexOAuth = oauthHandler;

export default oauthHandler;
