<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ErrorTrackingService
{
    public static function logError(\Throwable $exception, $context = [])
    {
        $errorData = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'url' => request()->fullUrl(),
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'context' => $context
        ];

        Log::error('Application Error', $errorData);

        if (self::isCriticalError($exception)) {
            self::notifyAdmins($errorData);
        }
    }

    private static function isCriticalError(\Throwable $exception): bool
    {
        $criticalErrors = [
            'PDOException',
            'QueryException',
            'FatalErrorException',
            'ErrorException'
        ];

        return in_array(get_class($exception), $criticalErrors);
    }

    private static function notifyAdmins($errorData)
    {
        $admins = \App\Models\User::where('role', 'superadmin')->get();
        
        foreach ($admins as $admin) {
            try {
                Mail::raw(
                    "Critical error occurred:\n\n" . json_encode($errorData, JSON_PRETTY_PRINT),
                    function ($message) use ($admin) {
                        $message->to($admin->email)
                               ->subject('Critical System Error - ' . config('app.name'));
                    }
                );
            } catch (\Exception $e) {
                Log::error('Failed to send error notification', ['error' => $e->getMessage()]);
            }
        }
    }

    public static function getErrorStats()
    {
        $logFile = storage_path('logs/laravel.log');
        if (!file_exists($logFile)) return [];

        $errors = [];
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach (array_slice($lines, -1000) as $line) {
            if (str_contains($line, 'ERROR')) {
                $errors[] = $line;
            }
        }

        return array_slice($errors, -50); // Last 50 errors
    }
}