<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Centralized Cache Service
 * Multi-layer caching dengan fallback dan error handling
 */
class CacheService
{
    // Cache TTL constants (seconds)
    const TTL_SHORT = 60;           // 1 minute - fast changing data
    const TTL_MEDIUM = 300;         // 5 minutes - moderate data
    const TTL_LONG = 1800;          // 30 minutes - stable data
    const TTL_VERY_LONG = 3600;     // 1 hour - static data
    const TTL_DAY = 86400;          // 24 hours - rarely changing

    // Cache keys
    const KEY_DASHBOARD_DEVICES = 'dashboard:devices';
    const KEY_DASHBOARD_WEATHER = 'dashboard:weather';
    const KEY_DASHBOARD_LAST_UPDATE = 'dashboard:last_update';
    const KEY_DEVICE_STATUS = 'device:status:%s';
    const KEY_DEVICE_TELEMETRY = 'device:telemetry:%s';
    const KEY_WEATHER_DATA = 'weather:latest';
    const KEY_USER_DEVICES = 'user:devices:%s';

    /**
     * Get from cache with fallback
     */
    public function remember(string $key, int $ttl, callable $callback, bool $tags = false)
    {
        try {
            return Cache::remember($key, $ttl, $callback);
        } catch (\Exception $e) {
            Log::error("Cache error for key: {$key}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback: execute callback directly
            return $callback();
        }
    }

    /**
     * Get from cache, null if not exists
     */
    public function get(string $key, $default = null)
    {
        try {
            return Cache::get($key, $default);
        } catch (\Exception $e) {
            Log::error("Cache get error: {$key}", ['error' => $e->getMessage()]);
            return $default;
        }
    }

    /**
     * Store in cache
     */
    public function put(string $key, $value, int $ttl = self::TTL_MEDIUM): bool
    {
        try {
            return Cache::put($key, $value, $ttl);
        } catch (\Exception $e) {
            Log::error("Cache put error: {$key}", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Forget cache key
     */
    public function forget(string $key): bool
    {
        try {
            return Cache::forget($key);
        } catch (\Exception $e) {
            Log::error("Cache forget error: {$key}", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Forget multiple keys by pattern
     */
    public function forgetPattern(string $pattern): int
    {
        $count = 0;
        try {
            $keys = $this->getKeysByPattern($pattern);
            foreach ($keys as $key) {
                if ($this->forget($key)) {
                    $count++;
                }
            }
        } catch (\Exception $e) {
            Log::error("Cache forget pattern error: {$pattern}", ['error' => $e->getMessage()]);
        }
        return $count;
    }

    /**
     * Get cache keys by pattern (file driver compatible)
     */
    protected function getKeysByPattern(string $pattern): array
    {
        // For file driver, we'll use a registry approach
        $registry = $this->get('cache:registry', []);
        return array_filter($registry, function($key) use ($pattern) {
            return fnmatch($pattern, $key);
        });
    }

    /**
     * Register cache key in registry
     */
    protected function registerKey(string $key): void
    {
        $registry = $this->get('cache:registry', []);
        if (!in_array($key, $registry)) {
            $registry[] = $key;
            $this->put('cache:registry', $registry, self::TTL_DAY);
        }
    }

    /**
     * Update dashboard last update timestamp
     */
    public function touchDashboard(): void
    {
        $this->put(self::KEY_DASHBOARD_LAST_UPDATE, now()->timestamp, self::TTL_MEDIUM);
    }

    /**
     * Get dashboard last update timestamp
     */
    public function getDashboardLastUpdate(): int
    {
        return (int) $this->get(self::KEY_DASHBOARD_LAST_UPDATE, 0);
    }

    /**
     * Invalidate all dashboard caches
     */
    public function invalidateDashboard(): void
    {
        $this->forget(self::KEY_DASHBOARD_DEVICES);
        $this->forget(self::KEY_DASHBOARD_WEATHER);
        $this->touchDashboard();
    }

    /**
     * Invalidate device-specific caches
     */
    public function invalidateDevice(int $deviceId): void
    {
        $this->forget(sprintf(self::KEY_DEVICE_STATUS, $deviceId));
        $this->forget(sprintf(self::KEY_DEVICE_TELEMETRY, $deviceId));
        $this->touchDashboard();
    }

    /**
     * Get cache statistics
     */
    public function getStats(): array
    {
        $registry = $this->get('cache:registry', []);
        
        return [
            'total_keys' => count($registry),
            'last_update' => $this->getDashboardLastUpdate(),
            'uptime' => now()->timestamp - $this->getDashboardLastUpdate(),
        ];
    }

    /**
     * Warm up critical caches
     */
    public function warmUp(): array
    {
        $warmed = [];
        
        try {
            // Pre-load dashboard data
            $this->touchDashboard();
            $warmed[] = 'dashboard_timestamp';
            
            Log::info('Cache warmed up', ['keys' => $warmed]);
        } catch (\Exception $e) {
            Log::error('Cache warm up failed', ['error' => $e->getMessage()]);
        }
        
        return $warmed;
    }

    /**
     * Health check
     */
    public function healthCheck(): array
    {
        $healthy = true;
        $checks = [];

        // Test write
        try {
            $testKey = 'health:test:' . time();
            $testValue = 'test';
            $this->put($testKey, $testValue, 60);
            $retrieved = $this->get($testKey);
            $this->forget($testKey);
            
            $checks['write'] = $retrieved === $testValue;
            $healthy = $healthy && $checks['write'];
        } catch (\Exception $e) {
            $checks['write'] = false;
            $checks['write_error'] = $e->getMessage();
            $healthy = false;
        }

        // Test read
        try {
            $this->get('non_existent_key');
            $checks['read'] = true;
        } catch (\Exception $e) {
            $checks['read'] = false;
            $checks['read_error'] = $e->getMessage();
            $healthy = false;
        }

        return [
            'healthy' => $healthy,
            'checks' => $checks,
            'driver' => config('cache.default'),
        ];
    }
}
