<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Clinic;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        Role::firstOrCreate(['name' => 'superadmin']);
        Role::firstOrCreate(['name' => 'clinic_admin']);
        Role::firstOrCreate(['name' => 'dentist']);
        Role::firstOrCreate(['name' => 'receptionist']);

        // Create superadmin user
        $superadmin = User::firstOrCreate([
            'email' => 'admin@dentalcare.com'
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
            'email_verified_at' => now()
        ]);
        $superadmin->assignRole('superadmin');

        // Create sample clinic
        $clinic = Clinic::firstOrCreate([
            'email' => 'clinic@example.com'
        ], [
            'name' => 'Sample Dental Clinic',
            'slug' => 'sample-dental-clinic',
            'phone' => '+1234567890',
            'address' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'country' => 'USA',
            'is_active' => true
        ]);

        // Create clinic admin
        $clinicAdmin = User::firstOrCreate([
            'email' => 'clinic@dentalcare.com'
        ], [
            'name' => 'Clinic Admin',
            'password' => bcrypt('password'),
            'clinic_id' => $clinic->id,
            'email_verified_at' => now()
        ]);
        $clinicAdmin->assignRole('clinic_admin');
    }
}