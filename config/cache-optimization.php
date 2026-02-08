<?php

return [
    'query_cache_ttl' => 300, // 5 minutes
    'view_cache_enabled' => true,
    'route_cache_enabled' => true,
    'config_cache_enabled' => true,
    
    'redis_settings' => [
        'max_connections' => 100,
        'timeout' => 5,
        'retry_interval' => 100,
    ],
    
    'database_optimization' => [
        'connection_pooling' => true,
        'query_timeout' => 30,
        'max_connections' => 50,
    ]
];