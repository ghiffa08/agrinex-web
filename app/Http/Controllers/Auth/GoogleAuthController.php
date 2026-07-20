<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        // Check if request from mobile app
        $isMobile = request()->get('mobile') === '1';
        
        if ($isMobile) {
            // Store mobile flag in session untuk callback
            session(['oauth_mobile' => true]);
        }
        
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if (!$user) {
                $user = User::create([
                    'full_name' => $googleUser->getName(),
                    'username' => strtolower(str_replace(' ', '_', $googleUser->getName())) . rand(100, 999),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'role' => 'viewer', // Default role for new OAuth users
                    'is_active' => true,
                ]);
            } else {
                // Update google ID and avatar if it was a standard email user
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
            }

            Auth::login($user);
            $user->updateLastLogin();

            // Check if from mobile app
            $isMobile = session('oauth_mobile', false);
            
            if ($isMobile) {
                // Clear mobile flag
                session()->forget('oauth_mobile');
                
                // Generate session token untuk mobile
                $sessionToken = hash('sha256', $user->id . time() . config('app.key'));
                
                // Store token in cache (5 minutes TTL)
                cache()->put('oauth_mobile_' . $sessionToken, [
                    'user_id' => $user->id,
                    'session_id' => session()->getId()
                ], now()->addMinutes(5));
                
                // Deep link redirect ke app
                $deepLink = 'agrinexsmartdrip://oauth/callback?' . http_build_query([
                    'token' => $sessionToken,
                    'session' => session()->getId()
                ]);
                
                return redirect($deepLink);
            }

            return redirect()->intended('/');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google OAuth Error: ' . $e->getMessage());
            
            // Check if mobile
            $isMobile = session('oauth_mobile', false);
            if ($isMobile) {
                session()->forget('oauth_mobile');
                $deepLink = 'agrinexsmartdrip://oauth/callback?error=' . urlencode($e->getMessage());
                return redirect($deepLink);
            }
            
            return redirect('/login')->withErrors(['error' => 'Gagal login dengan Google. Pastikan kredensial OAuth valid. (' . $e->getMessage() . ')']);
        }
    }
}
