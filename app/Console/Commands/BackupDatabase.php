<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database {--encrypt}';
    protected $description = 'Create encrypted database backup';

    public function handle()
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$timestamp}.sql";
        $encryptedFilename = "backup_{$timestamp}.sql.enc";
        
        $dbConfig = config('database.connections.mysql');
        
        $command = sprintf(
            'mysqldump -h%s -u%s -p%s %s > %s',
            $dbConfig['host'],
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['database'],
            storage_path("app/backups/{$filename}")
        );

        if (!Storage::exists('backups')) {
            Storage::makeDirectory('backups');
        }

        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            if ($this->option('encrypt')) {
                $this->encryptBackup($filename, $encryptedFilename);
                unlink(storage_path("app/backups/{$filename}"));
                $this->info("Encrypted backup created: {$encryptedFilename}");
            } else {
                $this->info("Backup created: {$filename}");
            }
            
            $this->cleanOldBackups();
        } else {
            $this->error('Backup failed');
        }
    }

    private function encryptBackup($filename, $encryptedFilename)
    {
        $key = config('app.key');
        $data = file_get_contents(storage_path("app/backups/{$filename}"));
        $encrypted = encrypt($data);
        file_put_contents(storage_path("app/backups/{$encryptedFilename}"), $encrypted);
    }

    private function cleanOldBackups()
    {
        $files = Storage::disk('local')->files('backups');
        $oldFiles = array_filter($files, function($file) {
            return Storage::lastModified($file) < now()->subDays(30)->timestamp;
        });
        
        foreach ($oldFiles as $file) {
            Storage::delete($file);
        }
    }
}