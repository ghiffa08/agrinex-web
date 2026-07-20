<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectMobileApp
{
    /**
     * Detect if request is from Capacitor mobile app
     * and redirect to mobile web app
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if request is from Capacitor app
        $isCapacitor = $request->header('X-Capacitor') === 'true' 
                    || str_contains($request->header('User-Agent', ''), 'Capacitor');
        
        // Check if accessing root or login page
        $isRootOrLogin = $request->is('/') || $request->is('login');
        
        // If Capacitor app accessing root/login, redirect to mobile login
        if ($isCapacitor && $isRootOrLogin) {
            // Check if has valid session (already logged in)
            if (auth()->check()) {
                return redirect('/mobile/dashboard.html');
            }
            
            return redirect('/mobile/login.html');
        }

        return $next($request);
    }
}
