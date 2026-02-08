<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

Route::get('/health', function () {
    $status = [
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
        'services' => []
    ];

    // Database check
    try {
        DB::connection()->getPdo();
        $status['services']['database'] = 'healthy';
    } catch (Exception $e) {
        $status['services']['database'] = 'unhealthy';
        $status['status'] = 'unhealthy';
    }

    // Cache check
    try {
        Cache::put('health_check', 'ok', 10);
        $status['services']['cache'] = Cache::get('health_check') === 'ok' ? 'healthy' : 'unhealthy';
    } catch (Exception $e) {
        $status['services']['cache'] = 'unhealthy';
        $status['status'] = 'unhealthy';
    }

    return response()->json($status, $status['status'] === 'healthy' ? 200 : 503);
});

Route::get('/performance', function () {
    return response()->json([
        'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB',
        'peak_memory' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB',
        'uptime' => app()->hasBeenBootstrapped() ? 'running' : 'starting',
        'environment' => app()->environment(),
    ]);
});