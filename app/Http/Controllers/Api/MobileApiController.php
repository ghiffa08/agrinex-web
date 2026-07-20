<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\PushNotificationService;

class MobileApiController extends Controller
{
    /**
     * Register FCM token untuk push notifications
     */
    public function registerFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
            'device_info' => 'nullable|array'
        ]);

        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $user->update([
                'fcm_token' => $request->fcm_token,
                'fcm_device_info' => $request->device_info,
                'fcm_updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'FCM token registered successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('FCM token registration error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to register FCM token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test notification endpoint
     */
    public function testNotification(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $pushService = app(PushNotificationService::class);
            
            $result = $pushService->sendToUser(
                $user->id,
                '🔔 Test Notification',
                'Ini adalah test notification dari AgriNex Smart Drip'
            );

            return response()->json([
                'success' => true,
                'message' => 'Test notification sent',
                'result' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Test notification error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get app version info
     */
    public function version()
    {
        return response()->json([
            'success' => true,
            'version' => '1.0.0',
            'build' => '2026.07.21',
            'min_version' => '1.0.0',
            'force_update' => false,
            'update_url' => null
        ]);
    }

    /**
     * Health check endpoint
     */
    public function health()
    {
        return response()->json([
            'success' => true,
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
            'server' => 'AgriNex Smart Drip API',
            'mobile_support' => true
        ]);
    }

    /**
     * Set OAuth session untuk mobile app
     * Dipanggil setelah OAuth callback deep link
     */
    public function setOAuthSession(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'session_id' => 'required|string'
        ]);

        try {
            // Get cached OAuth data
            $cacheKey = 'oauth_mobile_' . $request->token;
            $oauthData = cache()->get($cacheKey);

            if (!$oauthData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired OAuth token'
                ], 401);
            }

            // Clear cache
            cache()->forget($cacheKey);

            // Get user
            $user = \App\Models\User::find($oauthData['user_id']);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Login user
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'OAuth session set successfully',
                'user' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'role' => $user->role
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Set OAuth session error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to set OAuth session',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
