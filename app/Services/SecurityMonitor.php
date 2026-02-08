<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class SecurityMonitor
{
    public function detectSuspiciousActivity($userId, $action, $ipAddress)
    {
        $key = "security_events_{$userId}_{$ipAddress}";
        $events = Cache::get($key, []);
        
        $events[] = [
            'action' => $action,
            'timestamp' => now(),
            'ip' => $ipAddress
        ];
        
        Cache::put($key, $events, 3600); // 1 hour
        
        if ($this->isSuspicious($events)) {
            $this->alertSecurity($userId, $events);
        }
    }

    private function isSuspicious($events): bool
    {
        if (count($events) > 10) return true; // Too many actions
        
        $failedLogins = array_filter($events, fn($e) => $e['action'] === 'failed_login');
        if (count($failedLogins) > 5) return true; // Multiple failed logins
        
        $recentEvents = array_filter($events, fn($e) => 
            $e['timestamp']->diffInMinutes(now()) < 5
        );
        if (count($recentEvents) > 5) return true; // Rapid actions
        
        return false;
    }

    private function alertSecurity($userId, $events)
    {
        AuditLog::create([
            'user_id' => $userId,
            'action' => 'security_alert',
            'model_type' => 'SecurityEvent',
            'new_values' => ['events' => $events],
            'ip_address' => request()->ip(),
        ]);

        // Send alert to admins
        $admins = \App\Models\User::where('role', 'superadmin')->get();
        foreach ($admins as $admin) {
            // Mail notification would go here
        }
    }
}