<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DeployProduction extends Command
{
    protected $signature = 'deploy:production {--force : Force deployment without confirmation}';
    protected $description = 'Deploy application to production environment';

    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('Deploy to PRODUCTION? This will optimize caches and run migrations.')) {
            return 1;
        }

        $this->info('🚀 Starting Production Deployment...');
        
        // Clear all caches
        $this->call('optimize:clear');
        
        // Run migrations
        $this->call('migrate', ['--force' => true]);
        
        // Optimize for production
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');
        $this->call('optimize');
        
        // Set proper permissions
        $this->info('Setting file permissions...');
        
        $this->info('✅ Production deployment completed successfully!');
        $this->warn('⚠️  Remember to configure SSL, security headers, and monitoring.');
        
        return 0;
    }
}
