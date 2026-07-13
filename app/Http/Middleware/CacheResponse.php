<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Response Cache Middleware
 * Menambahkan HTTP cache headers untuk optimasi browser caching
 */
class CacheResponse
{
    /**
     * Cache durations (in seconds)
     */
    const CACHE_SHORT = 300;      // 5 minutes
    const CACHE_MEDIUM = 900;     // 15 minutes
    const CACHE_LONG = 3600;      // 1 hour
    const CACHE_STATIC = 2592000; // 30 days

    public function handle(Request $request, Closure $next, ?string $duration = 'short'): Response
    {
        $response = $next($request);

        // Jangan cache jika response error
        if ($response->getStatusCode() >= 400) {
            return $response->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                           ->header('Pragma', 'no-cache')
                           ->header('Expires', '0');
        }

        // Jangan cache authenticated requests
        if ($request->user()) {
            return $response;
        }

        // Set cache duration
        $ttl = match($duration) {
            'short' => self::CACHE_SHORT,
            'medium' => self::CACHE_MEDIUM,
            'long' => self::CACHE_LONG,
            'static' => self::CACHE_STATIC,
            default => self::CACHE_SHORT,
        };

        // Set cache headers
        return $response
            ->header('Cache-Control', "public, max-age={$ttl}")
            ->header('Expires', gmdate('D, d M Y H:i:s', time() + $ttl) . ' GMT')
            ->header('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
    }
}
