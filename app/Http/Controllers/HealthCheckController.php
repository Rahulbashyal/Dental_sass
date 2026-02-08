<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HealthCheckController extends Controller
{
    public function check()
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'security' => $this->checkSecurity(),
        ];

        $allHealthy = collect($checks)->every(fn($check) => $check['status'] === 'ok');

        return response()->json([
            'status' => $allHealthy ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toISOString(),
            'checks' => $checks
        ], $allHealthy ? 200 : 503);
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'ok', 'message' => 'Database connection successful'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Database connection failed'];
        }
    }

    private function checkCache(): array
    {
        try {
            Cache::put('health_check', 'test', 10);
            $value = Cache::get('health_check');
            return $value === 'test' 
                ? ['status' => 'ok', 'message' => 'Cache working']
                : ['status' => 'error', 'message' => 'Cache not working'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Cache error'];
        }
    }

    private function checkStorage(): array
    {
        try {
            $testFile = storage_path('app/health_check.txt');
            file_put_contents($testFile, 'test');
            $content = file_get_contents($testFile);
            unlink($testFile);
            
            return $content === 'test'
                ? ['status' => 'ok', 'message' => 'Storage writable']
                : ['status' => 'error', 'message' => 'Storage not writable'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Storage error'];
        }
    }

    private function checkSecurity(): array
    {
        $issues = [];
        
        if (config('app.debug')) {
            $issues[] = 'Debug mode enabled';
        }
        
        if (!config('app.url') || str_starts_with(config('app.url'), 'http://')) {
            $issues[] = 'HTTPS not enforced';
        }

        return empty($issues)
            ? ['status' => 'ok', 'message' => 'Security checks passed']
            : ['status' => 'warning', 'message' => 'Security issues: ' . implode(', ', $issues)];
    }
}