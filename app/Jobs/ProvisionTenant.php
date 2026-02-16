<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\ProvisioningLog;
use App\Models\Tenant;
use Illuminate\Auth\Events\Registered;
use App\Notifications\ClinicProvisioned;

class ProvisionTenant implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tenantId;
    public $adminEmail;
    public $adminPassword;

    public function __construct(string $tenantId, string $adminEmail, string $adminPassword)
    {
        $this->tenantId = $tenantId;
        $this->adminEmail = $adminEmail;
        $this->adminPassword = $adminPassword;
        $this->onQueue('provisioning');
    }

    public function handle()
    {
        $tenant = Tenant::find($this->tenantId);
        if (! $tenant) {
            Log::warning("ProvisionTenant: tenant {$this->tenantId} not found");
            ProvisioningLog::create(['tenant_id' => $this->tenantId, 'level' => 'warning', 'message' => 'Tenant not found']);
            return;
        }

        // mark pending
        $existing = is_array($tenant->data) ? $tenant->data : (array) ($tenant->data ?? []);
        $existing['provision_status'] = 'in_progress';
        $tenant->update(['data' => $existing]);
        ProvisioningLog::create(['tenant_id' => $tenant->id, 'level' => 'info', 'message' => 'Provisioning started']);


        try {
            $tenantId = $tenant->id;
            $adminEmail = $this->adminEmail;
            $adminPassword = $this->adminPassword;

            $tenant->run(function () use ($tenantId) {
                ProvisioningLog::create(['tenant_id' => $tenantId, 'level' => 'info', 'message' => 'Running tenant migrations/seeds']);
                Artisan::call('migrate', ['--path' => 'database/migrations/tenant', '--force' => true]);
                Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\TenantSeeder', '--force' => true]);
            });

            ProvisioningLog::create(['tenant_id' => $tenant->id, 'level' => 'info', 'message' => 'Migrations and seeder completed']);

            // create admin inside tenant if missing
            $tenant->run(function () use ($tenantId, $adminEmail, $adminPassword) {
                if (class_exists(\App\Models\User::class)) {
                    $userModel = \App\Models\User::class;
                    if (! $userModel::where('email', $adminEmail)->exists()) {
                        $user = $userModel::create([
                            'name' => 'Tenant Admin',
                            'email' => $adminEmail,
                            'password' => Hash::make($adminPassword),
                        ]);

                        if (method_exists($user, 'assignRole')) {
                            $user->assignRole('clinic_admin');
                        }

                        event(new Registered($user));

                        // Notify the admin that the clinic is ready
                        $tenantModel = \App\Models\Clinic::where('slug', $tenantId)->first();
                        if ($tenantModel) {
                            $user->notify(new ClinicProvisioned($tenantModel, $adminPassword));
                        }

                        ProvisioningLog::create(['tenant_id' => $tenantId, 'level' => 'info', 'message' => 'Tenant admin created & notified: ' . $adminEmail]);
                    } else {
                        ProvisioningLog::create(['tenant_id' => $tenantId, 'level' => 'info', 'message' => 'Tenant admin already exists: ' . $adminEmail]);
                    }
                }
            });

            $existing = is_array($tenant->data) ? $tenant->data : (array) ($tenant->data ?? []);
            $existing['provision_status'] = 'completed';
            unset($existing['provision_error']);
            $tenant->update(['data' => $existing]);
            ProvisioningLog::create(['tenant_id' => $tenant->id, 'level' => 'info', 'message' => 'Provisioning completed successfully']);
        } catch (\Throwable $e) {
            Log::error('ProvisionTenant failed: ' . $e->getMessage());
            ProvisioningLog::create(['tenant_id' => $tenant->id, 'level' => 'error', 'message' => substr($e->getMessage(), 0, 2000)]);
            $existing = is_array($tenant->data) ? $tenant->data : (array) ($tenant->data ?? []);
            $existing['provision_status'] = 'failed';
            $existing['provision_error'] = substr($e->getMessage(), 0, 1000);
            $tenant->update(['data' => $existing]);
        }
    }
}
