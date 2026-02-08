<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DataRetentionService;

class CleanupOldData extends Command
{
    protected $signature = 'data:cleanup';
    protected $description = 'Clean up old data according to retention policies';

    public function handle()
    {
        $this->info('Starting data cleanup...');
        
        $service = new DataRetentionService();
        $service->cleanupOldData();
        
        $this->info('Data cleanup completed successfully.');
    }
}