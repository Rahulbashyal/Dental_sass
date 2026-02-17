<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Clinic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $clinicAdmin = Role::firstOrCreate(['name' => 'clinic_admin']);
        $dentist = Role::firstOrCreate(['name' => 'dentist']);
        $receptionist = Role::firstOrCreate(['name' => 'receptionist']);
        $accountant = Role::firstOrCreate(['name' => 'accountant']);

        // Create permissions
        Permission::firstOrCreate(['name' => 'manage clinics']);
        Permission::firstOrCreate(['name' => 'manage patients']);
        Permission::firstOrCreate(['name' => 'manage appointments']);
        Permission::firstOrCreate(['name' => 'manage billing']);
        Permission::firstOrCreate(['name' => 'view reports']);

        // Assign permissions to roles
        $superadmin->givePermissionTo(['manage clinics', 'view reports']);
        $clinicAdmin->givePermissionTo(['manage patients', 'manage appointments', 'manage billing', 'view reports']);
        $dentist->givePermissionTo(['manage patients', 'manage appointments']);
        $receptionist->givePermissionTo(['manage patients', 'manage appointments']);
        $accountant->givePermissionTo(['manage billing', 'view reports']);

        // Create superadmin user
        $superadminUser = User::firstOrCreate(['email' => 'admin@dentalcare.com'], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $superadminUser->assignRole('superadmin');

        // Create sample clinic
        $clinic = Clinic::firstOrCreate(['email' => 'info@smiledentalclinic.com'], [
            'name' => 'Smile Dental Clinic',
            'slug' => 'smile-dental-clinic',
            'phone' => '+977-1-4567890',
            'address' => 'Kathmandu, Nepal',
            'city' => 'Kathmandu',
            'state' => 'Bagmati',
            'country' => 'Nepal',
            'subscription_status' => 'active',
            'subscription_plan' => 'standard',
        ]);

        // Create clinic admin
        $clinicAdminUser = User::firstOrCreate(['email' => 'admin@clinic.com'], [
            'name' => 'Dr. John Doe',
            'password' => Hash::make('password'),
            'clinic_id' => $clinic->id,
            'is_active' => true,
        ]);
        $clinicAdminUser->assignRole('clinic_admin');

        // Create dentist
        $dentist = User::firstOrCreate(['email' => 'dentist@clinic.com'], [
            'name' => 'Dr. Sarah Smith',
            'password' => Hash::make('password'),
            'clinic_id' => $clinic->id,
            'is_active' => true,
        ]);
        $dentist->assignRole('dentist');

        // Create receptionist
        $receptionist = User::firstOrCreate(['email' => 'receptionist@clinic.com'], [
            'name' => 'Mary Johnson',
            'password' => Hash::make('password'),
            'clinic_id' => $clinic->id,
            'is_active' => true,
        ]);
        $receptionist->assignRole('receptionist');

        // Create accountant
        $accountant = User::firstOrCreate(['email' => 'accountant@clinic.com'], [
            'name' => 'Mike Wilson',
            'password' => Hash::make('password'),
            'clinic_id' => $clinic->id,
            'is_active' => true,
        ]);
        $accountant->assignRole('accountant');
    }
}