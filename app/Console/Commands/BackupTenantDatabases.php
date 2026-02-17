<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Stancl\Tenancy\Database\Models\Tenant;

class BackupTenantDatabases extends Command
{
    protected $signature = 'tenants:backup {--tenant=} {--all} {--retention=7} {--s3}';

    protected $description = 'Create backups for tenant databases. Use --tenant=id or --all. Optionally upload to S3 with --s3.';

    public function handle()
    {
        $tenantId = $this->option('tenant');
        $all = $this->option('all');
        $retention = (int) $this->option('retention');
        $uploadS3 = (bool) $this->option('s3');

        if (! $all && ! $tenantId) {
            return $this->error('Specify --tenant=ID or --all');
        }

        $tenants = $all ? Tenant::all() : Tenant::where('id', $tenantId)->get();

        if ($tenants->isEmpty()) {
            return $this->error('No tenants found for backup.');
        }

        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        $dbUser = env('DB_USERNAME', 'root');
        $dbPass = env('DB_PASSWORD', '');
        $dbPrefix = config('tenancy.database.prefix', 'tenant');
        $dbSuffix = config('tenancy.database.suffix', '');

        $backupBase = storage_path('app/backups/tenants');
        if (! is_dir($backupBase)) {
            mkdir($backupBase, 0755, true);
        }

        foreach ($tenants as $tenant) {
            $dbName = $dbPrefix . $tenant->id . $dbSuffix;
            $timestamp = date('Ymd_His');
            $filename = "{$tenant->id}_{$timestamp}.sql.gz";
            $path = "{$backupBase}/{$tenant->id}";
            if (! is_dir($path)) mkdir($path, 0755, true);

            $fullpath = "{$path}/{$filename}";

            $this->info("Backing up tenant: {$tenant->id} -> {$dbName}");

            // Build mysqldump command
            $passPart = $dbPass !== '' ? "-p'" . addcslashes($dbPass, "'\\") . "'" : '';
            $cmd = "mysqldump -h {$dbHost} -P {$dbPort} -u {$dbUser} {$passPart} --single-transaction --quick --lock-tables=false {$dbName} 2>/dev/null | gzip > {$fullpath}";

            $this->line("Running: {$cmd}");
            $ret = null;
            system($cmd, $ret);
            if ($ret !== 0 || ! file_exists($fullpath)) {
                $this->error("Backup failed for tenant {$tenant->id}");
                continue;
            }

            $this->info("Backup saved: {$fullpath}");

            if ($uploadS3) {
                if (! Storage::disk('s3')->exists('/')) {
                    $this->error('S3 disk not configured. Skipping upload.');
                } else {
                    $s3Path = "tenants/{$tenant->id}/{$filename}";
                    Storage::disk('s3')->put($s3Path, fopen($fullpath, 'r'));
                    $this->info("Uploaded to s3: {$s3Path}");
                }
            }

            // Prune old backups
            $files = array_filter(scandir($path), function ($f) use ($path) {
                return is_file("{$path}/{$f}") && preg_match('/\.sql\.gz$/', $f);
            });
            usort($files, function ($a, $b) use ($path) {
                return filemtime("{$path}/{$b}") <=> filemtime("{$path}/{$a}");
            });

            $keep = $retention;
            $toDelete = array_slice($files, $keep);
            foreach ($toDelete as $old) {
                @unlink("{$path}/{$old}");
                $this->line("Pruned old backup: {$old}");
            }
        }

        return 0;
    }
}
