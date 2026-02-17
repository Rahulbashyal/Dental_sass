<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SecurityAudit extends Command
{
    protected $signature = 'security:audit {--fix : Automatically fix issues}';
    protected $description = 'Perform comprehensive security audit';

    public function handle()
    {
        $this->info('🔍 Starting Security Audit...');
        
        $issues = [];
        
        // Check environment configuration
        $issues = array_merge($issues, $this->checkEnvironment());
        
        // Check file permissions
        $issues = array_merge($issues, $this->checkFilePermissions());
        
        // Check database security
        $issues = array_merge($issues, $this->checkDatabase());
        
        // Check for dangerous files
        $issues = array_merge($issues, $this->checkDangerousFiles());
        
        // Display results
        if (empty($issues)) {
            $this->info('✅ Security audit passed! No issues found.');
            return 0;
        }
        
        $this->error('🚨 Security issues found:');
        foreach ($issues as $issue) {
            $this->line("  - {$issue}");
        }
        
        if ($this->option('fix')) {
            $this->info('🔧 Attempting to fix issues...');
            $this->fixIssues($issues);
        }
        
        return count($issues);
    }
    
    private function checkEnvironment(): array
    {
        $issues = [];
        
        if (env('APP_DEBUG', false)) {
            $issues[] = 'APP_DEBUG is enabled in production';
        }
        
        if (env('APP_ENV') !== 'production') {
            $issues[] = 'APP_ENV is not set to production';
        }
        
        if (!env('APP_KEY')) {
            $issues[] = 'APP_KEY is not set';
        }
        
        return $issues;
    }
    
    private function checkFilePermissions(): array
    {
        $issues = [];
        
        $sensitiveFiles = [
            '.env',
            'config/database.php',
            'storage/logs',
        ];
        
        foreach ($sensitiveFiles as $file) {
            $path = base_path($file);
            if (File::exists($path)) {
                $perms = substr(sprintf('%o', fileperms($path)), -4);
                if ($perms > '0644' && !is_dir($path)) {
                    $issues[] = "File {$file} has overly permissive permissions: {$perms}";
                }
            }
        }
        
        return $issues;
    }
    
    private function checkDatabase(): array
    {
        $issues = [];
        
        try {
            // Check for default passwords
            $defaultUsers = DB::table('users')
                ->where('email', 'admin@example.com')
                ->orWhere('email', 'test@example.com')
                ->count();
                
            if ($defaultUsers > 0) {
                $issues[] = 'Default test users found in database';
            }
            
        } catch (\Exception $e) {
            $issues[] = 'Database connection failed: ' . $e->getMessage();
        }
        
        return $issues;
    }
    
    private function checkDangerousFiles(): array
    {
        $issues = [];
        
        $dangerousFiles = [
            'public/phpinfo.php',
            'security_test.php',
            'test_phase1.php',
            'public/test.php',
        ];
        
        foreach ($dangerousFiles as $file) {
            if (File::exists(base_path($file))) {
                $issues[] = "Dangerous file found: {$file}";
            }
        }
        
        return $issues;
    }
    
    private function fixIssues(array $issues): void
    {
        foreach ($issues as $issue) {
            if (str_contains($issue, 'Dangerous file found:')) {
                $file = str_replace('Dangerous file found: ', '', $issue);
                File::delete(base_path($file));
                $this->info("✅ Deleted dangerous file: {$file}");
            }
        }
    }
}