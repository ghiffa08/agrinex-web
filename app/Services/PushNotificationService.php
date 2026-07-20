<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    /**
     * Send push notification via FCM (Firebase Cloud Messaging)
     * 
     * @param string|array $deviceToken FCM token atau array of tokens
     * @param string $title Notification title
     * @param string $body Notification body
     * @param array $data Additional data payload
     * @return array Response dari FCM
     */
    public function sendNotification($deviceToken, string $title, string $body, array $data = []): array
    {
        // TODO: Setup FCM Server Key di .env
        // FCM_SERVER_KEY=your_firebase_server_key_here
        $fcmServerKey = env('FCM_SERVER_KEY', '');
        
        if (empty($fcmServerKey)) {
            Log::warning('FCM_SERVER_KEY not configured in .env');
            return [
                'success' => false,
                'message' => 'FCM not configured'
            ];
        }

        // Support single token atau multiple tokens
        $tokens = is_array($deviceToken) ? $deviceToken : [$deviceToken];

        $payload = [
            'registration_ids' => $tokens,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
                'badge' => '1',
            ],
            'data' => array_merge([
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'timestamp' => now()->toIso8601String(),
            ], $data),
            'priority' => 'high',
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $fcmServerKey,
                'Content-Type' => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', $payload);

            $result = $response->json();

            Log::info('Push notification sent', [
                'tokens' => $tokens,
                'title' => $title,
                'response' => $result,
            ]);

            return [
                'success' => $response->successful(),
                'data' => $result,
            ];
        } catch (\Exception $e) {
            Log::error('Push notification failed', [
                'error' => $e->getMessage(),
                'tokens' => $tokens,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send notification ke semua users yang punya FCM token
     */
    public function broadcastNotification(string $title, string $body, array $data = []): array
    {
        // Get all FCM tokens from database
        // TODO: Tambah kolom fcm_token di table users
        $tokens = \App\Models\User::whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();

        if (empty($tokens)) {
            return [
                'success' => false,
                'message' => 'No FCM tokens found',
            ];
        }

        return $this->sendNotification($tokens, $title, $body, $data);
    }

    /**
     * Send notification ke user tertentu
     */
    public function sendToUser(int $userId, string $title, string $body, array $data = []): array
    {
        $user = \App\Models\User::find($userId);

        if (!$user || !$user->fcm_token) {
            return [
                'success' => false,
                'message' => 'User FCM token not found',
            ];
        }

        return $this->sendNotification($user->fcm_token, $title, $body, $data);
    }

    /**
     * Send IoT alert notification
     * Contoh: Soil moisture rendah, tank level low, device offline
     */
    public function sendIotAlert(string $alertType, string $deviceName, array $data = []): array
    {
        $titles = [
            'soil_moisture_low' => '⚠️ Kelembaban Tanah Rendah',
            'tank_level_low' => '💧 Level Tangki Air Rendah',
            'device_offline' => '🔴 Device Offline',
            'irrigation_started' => '💦 Irigasi Dimulai',
            'irrigation_completed' => '✅ Irigasi Selesai',
        ];

        $title = $titles[$alertType] ?? '🔔 AgriNex Alert';
        $body = "Device: {$deviceName}";

        return $this->broadcastNotification($title, $body, array_merge([
            'alert_type' => $alertType,
            'device_name' => $deviceName,
        ], $data));
    }
}
