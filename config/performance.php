<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Performance Optimization Settings
    |--------------------------------------------------------------------------
    */

    'database' => [
        'query_cache_enabled' => true,
        'query_cache_size' => '64M',
        'max_connections' => 100,
        'innodb_buffer_pool_size' => '256M',
    ],

    'caching' => [
        'views_cache' => true,
        'config_cache' => true,
        'route_cache' => true,
        'query_cache_ttl' => 300, // 5 minutes
    ],

    'optimization' => [
        'eager_load_relations' => true,
        'pagination_limit' => 15,
        'max_query_time' => 2, // seconds
        'enable_query_log' => env('APP_DEBUG', false),
    ],
];