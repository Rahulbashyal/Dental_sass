<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Database\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;

class CreateTenant extends Command
{
    protected $signature = 'tenant:create {id} {domain} {--email=} {--password=} {--smtp-host=} {--smtp-port=} {--smtp-username=} {--smtp-password=} {--smtp-encryption=} {--from-address=} {--from-name=}';

    protected $description = 'Create a tenant, provision database, run tenant migrations and seed default admin.';

    public function handle()
    {
        $id = $this->argument('id');
        $domain = $this->argument('domain');
        $email = $this->option('email') ?: "admin@{$id}.local";
        $password = $this->option('password') ?: 'password';

        $this->info("Creating tenant: {$id}");

        if (Tenant::find($id)) {
            $this->error("Tenant with id {$id} already exists. Aborting.");
            return 1;
        }

        $tenant = Tenant::create([
            'id' => $id,
            'data' => [
                'name' => $id,
            ],
        ]);

        Domain::create(['domain' => $domain, 'tenant_id' => $tenant->id]);

        $this->info('Provisioning tenant database and running tenant migrations...');

        // Collect SMTP config from options
        $smtpHost = $this->option('smtp-host');
        $smtpPort = $this->option('smtp-port');
        $smtpUsername = $this->option('smtp-username');
        $smtpPassword = $this->option('smtp-password');
        $smtpEncryption = $this->option('smtp-encryption');
        $fromAddress = $this->option('from-address') ?: $email;
        $fromName = $this->option('from-name') ?: 'Tenant Admin';

        $tenant->run(function () use ($email, $password) {
            Artisan::call('migrate', ['--path' => 'database/migrations/tenant', '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\TenantSeeder', '--force' => true]);
        });

        // Store tenant-scoped mail config in tenant data under `config` so TenantConfig feature applies it.
        $mailConfig = [
            'mail' => [
                'default' => 'smtp',
                'mailers' => [
                    'smtp' => [
                        'transport' => 'smtp',
                        'host' => $smtpHost ?? null,
                        'port' => $smtpPort ? (int) $smtpPort : null,
                        'encryption' => $smtpEncryption ?? null,
                        'username' => $smtpUsername ?? null,
                        'password' => $smtpPassword ?? null,
                    ],
                ],
                'from' => [
                    'address' => $fromAddress,
                    'name' => $fromName,
                ],
            ],
        ];

        // Merge into tenant data
        $existing = is_array($tenant->data) ? $tenant->data : (array) ($tenant->data ?? []);
        $existing['config'] = array_merge($existing['config'] ?? [], $mailConfig);
        $tenant->update(['data' => $existing]);

        // Ensure the admin user exists inside tenant context
        $tenant->run(function () use ($email, $password) {
            if (class_exists('\App\\Models\\User')) {
                $userModel = \App\Models\User::class;
                if (! $userModel::where('email', $email)->exists()) {
                    $user = $userModel::create([
                        'name' => 'Tenant Admin',
                        'email' => $email,
                        'password' => Hash::make($password),
                    ]);

                    if (method_exists($user, 'assignRole')) {
                        $user->assignRole('clinic_admin');
                    }
                }
            }
        });

        $this->info("Tenant {$id} created and seeded. Domain: {$domain} | Admin: {$email}");

        return 0;
    }
}
