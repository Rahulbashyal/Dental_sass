<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\ErrorTrackingService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function activityDashboard()
    {
        $recentActivity = AuditLog::with('user')
            ->latest()
            ->limit(50)
            ->get();

        $securityEvents = AuditLog::where('action', 'LIKE', '%security%')
            ->orWhere('action', 'failed_login')
            ->latest()
            ->limit(20)
            ->get();

        $userStats = [
            'total_users' => User::count(),
            '2fa_enabled' => User::where('two_factor_enabled', true)->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'active_sessions' => User::whereNotNull('last_login_at')
                ->where('last_login_at', '>', now()->subHours(24))
                ->count()
        ];

        $errorStats = ErrorTrackingService::getErrorStats();

        return view('admin.activity-dashboard', compact(
            'recentActivity', 'securityEvents', 'userStats', 'errorStats'
        ));
    }

    public function securityReport()
    {
        $vulnerabilities = $this->checkSecurityVulnerabilities();
        $recommendations = $this->getSecurityRecommendations();
        
        return response()->json([
            'vulnerabilities' => $vulnerabilities,
            'recommendations' => $recommendations,
            'security_score' => $this->calculateSecurityScore()
        ]);
    }

    private function checkSecurityVulnerabilities(): array
    {
        $issues = [];
        
        if (config('app.debug')) {
            $issues[] = ['level' => 'high', 'message' => 'Debug mode enabled in production'];
        }
        
        $unverifiedUsers = User::whereNull('email_verified_at')->count();
        if ($unverifiedUsers > 0) {
            $issues[] = ['level' => 'medium', 'message' => "{$unverifiedUsers} unverified users"];
        }
        
        $no2faUsers = User::where('two_factor_enabled', false)->count();
        if ($no2faUsers > 0) {
            $issues[] = ['level' => 'low', 'message' => "{$no2faUsers} users without 2FA"];
        }
        
        return $issues;
    }

    private function getSecurityRecommendations(): array
    {
        return [
            'Enable 2FA for all admin users',
            'Regular security audits',
            'Update dependencies regularly',
            'Monitor failed login attempts',
            'Implement IP whitelisting for admin access'
        ];
    }

    private function calculateSecurityScore(): int
    {
        $score = 100;
        $vulnerabilities = $this->checkSecurityVulnerabilities();
        
        foreach ($vulnerabilities as $vuln) {
            $score -= match($vuln['level']) {
                'high' => 30,
                'medium' => 15,
                'low' => 5,
                default => 0
            };
        }
        
        return max(0, $score);
    }
}