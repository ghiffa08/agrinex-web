<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Custom cache settings untuk AgriNex Smart Drip
    |
    */

    // Cache durations (dalam detik)
    'ttl' => [
        'dashboard_devices' => env('CACHE_TTL_DEVICES', 30),      // 30 detik
        'dashboard_weather' => env('CACHE_TTL_WEATHER', 300),     // 5 menit
        'sensor_data' => env('CACHE_TTL_SENSOR', 60),             // 1 menit
        'chart_data' => env('CACHE_TTL_CHART', 30),               // 30 detik
        'irrigation_sessions' => env('CACHE_TTL_IRRIGATION', 300), // 5 menit
        'usage_history' => env('CACHE_TTL_USAGE', 300),           // 5 menit
    ],

    // Cache keys
    'keys' => [
        'dashboard_devices' => 'dashboard_devices',
        'dashboard_weather' => 'dashboard_weather',
        'dashboard_last_update' => 'dashboard_last_update',
    ],

    // Enabled/disabled toggle
    'enabled' => env('APP_CACHE_ENABLED', true),

    // Query caching (disable in development)
    'query_cache' => env('QUERY_CACHE_ENABLED', true),

    // Response caching
    'response_cache' => env('RESPONSE_CACHE_ENABLED', true),
];
