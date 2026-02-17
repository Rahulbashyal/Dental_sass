<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run()
    {
        // Create default clinic settings or records here.
        // This runs within the tenant database context.

        if (! class_exists('\App\\Models\\User')) {
            return;
        }

        $userModel = \App\Models\User::class;
        $email = env('TENANT_ADMIN_EMAIL', 'admin@tenant.local');
        $password = env('TENANT_ADMIN_PASSWORD', 'password');

        if (! $userModel::where('email', $email)->exists()) {
            $userModel::create([
                'name' => 'Tenant Admin',
                'email' => $email,
                'password' => Hash::make($password),
            ]);
        }
    }
}
