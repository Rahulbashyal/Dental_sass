<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Scalability Configuration
    |--------------------------------------------------------------------------
    */

    'rate_limiting' => [
        // Per-IP limits (prevents single IP from overwhelming)
        'login_per_ip' => [
            'attempts' => 20,
            'decay_minutes' => 15,
        ],
        
        // Per-user limits (prevents account brute force)
        'login_per_user' => [
            'attempts' => 5,
            'decay_minutes' => 15,
        ],
        
        // Global system limits
        'global_requests_per_minute' => 10000,
        'concurrent_sessions_per_clinic' => 100,
        'max_concurrent_bookings' => 50,
    ],

    'database' => [
        'connection_pool_size' => 20,
        'query_timeout_seconds' => 30,
        'max_connections_per_clinic' => 10,
        'read_replica_enabled' => false, // Enable for production
    ],

    'caching' => [
        'enabled' => true,
        'driver' => 'redis', // Use Redis for production
        'clinic_data_ttl' => 3600, // 1 hour
        'user_session_ttl' => 28800, // 8 hours
        'appointment_cache_ttl' => 300, // 5 minutes
    ],

    'load_balancing' => [
        'max_users_per_server' => 1000,
        'auto_scaling_threshold' => 80, // CPU percentage
        'health_check_interval' => 30, // seconds
    ],

    'performance_limits' => [
        'max_patients_per_clinic' => 10000,
        'max_appointments_per_day' => 500,
        'max_concurrent_bookings' => 20,
        'pagination_limit' => 50,
    ],
];