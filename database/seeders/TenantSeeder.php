<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed Roles and Permissions
        $this->call(PermissionsSeeder::class);

        // 2. Initialize default settings if needed
        // ...
    }
}

