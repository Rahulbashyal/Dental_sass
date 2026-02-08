<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PerformanceMonitor
{
    public static function trackQueryPerformance()
    {
        DB::listen(function ($query) {
            if ($query->time > 1000) { // Queries over 1 second
                logger()->warning('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . 'ms'
                ]);
            }
        });
    }

    public static function getSystemMetrics(): array
    {
        return [
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            'db_connections' => DB::connection()->getPdo()->getAttribute(\PDO::ATTR_CONNECTION_STATUS),
            'cache_hits' => Cache::get('cache_hits', 0),
            'cache_misses' => Cache::get('cache_misses', 0),
            'response_time' => microtime(true) - LARAVEL_START,
        ];
    }

    public static function logPerformanceMetrics()
    {
        $metrics = self::getSystemMetrics();
        
        Cache::put('performance_metrics_' . now()->format('Y-m-d-H'), $metrics, 3600);
        
        if ($metrics['memory_usage'] > 128 * 1024 * 1024) { // 128MB
            logger()->warning('High memory usage detected', $metrics);
        }
    }
}