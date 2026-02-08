<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupVerification extends Command
{
    protected $signature = 'backup:verify';
    protected $description = 'Verify backup integrity';

    public function handle()
    {
        $backups = Storage::files('backups');
        $verified = 0;
        $failed = 0;

        foreach ($backups as $backup) {
            if ($this->verifyBackup($backup)) {
                $verified++;
                $this->info("✓ {$backup} - Valid");
            } else {
                $failed++;
                $this->error("✗ {$backup} - Corrupted");
            }
        }

        $this->info("Verification complete: {$verified} valid, {$failed} failed");
    }

    private function verifyBackup($backup): bool
    {
        try {
            $content = Storage::get($backup);
            
            if (str_ends_with($backup, '.enc')) {
                $decrypted = decrypt($content);
                return str_contains($decrypted, 'CREATE TABLE');
            }
            
            return str_contains($content, 'CREATE TABLE');
        } catch (\Exception $e) {
            return false;
        }
    }
}