<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DisasterRecovery extends Command
{
    protected $signature = 'disaster:recover {backup}';
    protected $description = 'Restore system from backup';

    public function handle()
    {
        $backup = $this->argument('backup');
        
        if (!Storage::exists("backups/{$backup}")) {
            $this->error("Backup file not found: {$backup}");
            return 1;
        }

        if (!$this->confirm('This will restore the database. Continue?')) {
            return 0;
        }

        $this->info('Starting disaster recovery...');
        
        try {
            $this->restoreDatabase($backup);
            $this->clearCache();
            $this->regenerateKeys();
            
            $this->info('Disaster recovery completed successfully!');
        } catch (\Exception $e) {
            $this->error('Recovery failed: ' . $e->getMessage());
            return 1;
        }
    }

    private function restoreDatabase($backup)
    {
        $backupPath = storage_path("app/backups/{$backup}");
        $dbConfig = config('database.connections.mysql');
        
        $command = sprintf(
            'mysql -h%s -u%s -p%s %s < %s',
            $dbConfig['host'],
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['database'],
            $backupPath
        );

        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new \Exception('Database restoration failed');
        }
    }

    private function clearCache()
    {
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');
    }

    private function regenerateKeys()
    {
        $this->call('key:generate', ['--force' => true]);
    }
}