<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class LoadBalancingService
{
    public static function getServerHealth(): array
    {
        return [
            'cpu_usage' => sys_getloadavg()[0],
            'memory_usage' => memory_get_usage(true),
            'disk_usage' => disk_free_space('/'),
            'active_connections' => self::getActiveConnections(),
            'response_time' => self::getAverageResponseTime()
        ];
    }

    public static function shouldRedirectTraffic(): bool
    {
        $health = self::getServerHealth();
        
        return $health['cpu_usage'] > 0.8 || 
               $health['memory_usage'] > 1024 * 1024 * 1024 || // 1GB
               $health['active_connections'] > 1000;
    }

    private static function getActiveConnections(): int
    {
        return Cache::get('active_connections', 0);
    }

    private static function getAverageResponseTime(): float
    {
        $times = Cache::get('response_times', []);
        return empty($times) ? 0 : array_sum($times) / count($times);
    }

    public static function recordResponseTime($time)
    {
        $times = Cache::get('response_times', []);
        $times[] = $time;
        
        // Keep only last 100 response times
        if (count($times) > 100) {
            $times = array_slice($times, -100);
        }
        
        Cache::put('response_times', $times, 3600);
    }
}