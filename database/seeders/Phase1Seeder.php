<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Clinic, Patient, Appointment, Invoice, Expense};
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class Phase1Seeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = ['superadmin', 'clinic_admin', 'dentist', 'receptionist', 'accountant'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create superadmin user
        $superadmin = User::updateOrCreate(
            ['email' => 'admin@dentalcare.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
                'clinic_id' => null,
                'email_verified_at' => now(),
            ]
        );
        $superadmin->assignRole('superadmin');

        // Create test clinic
        $clinic = Clinic::updateOrCreate(
            ['slug' => 'smile-dental-clinic'],
            [
                'name' => 'Smile Dental Clinic',
                'email' => 'info@clinic.com',
                'phone' => '+977-1-4567890',
                'address' => 'Durbar Marg, Kathmandu',
                'city' => 'Kathmandu',
                'state' => 'Bagmati',
                'country' => 'Nepal',
                'postal_code' => '44600',
                'primary_color' => '#3B82F6',
                'secondary_color' => '#1E40AF',
                'subscription_status' => 'active',
                'subscription_plan' => 'premium',
                'subscription_expires_at' => now()->addYear(),
                'is_active' => true,
                'enabled_modules' => ['patients', 'appointments', 'Financials', 'inventory', 'reports', 'crm', 'invoicing'],
                'enabled_roles' => ['dentist', 'receptionist', 'accountant'],
                'has_analytics' => true,
                'has_accounting' => true,
            ]
        );

        // Create core users assigned to the clinic
        $coreUsers = [
            ['email' => 'admin@clinic.com', 'name' => 'Dr. Rajesh Sharma', 'role' => 'clinic_admin'],
            ['email' => 'dentist@clinic.com', 'name' => 'Dr. Priya Patel', 'role' => 'dentist'],
            ['email' => 'receptionist@clinic.com', 'name' => 'Sita Gurung', 'role' => 'receptionist'],
            ['email' => 'accountant@clinic.com', 'name' => 'Bishal Thapa', 'role' => 'accountant'],
        ];

        foreach ($coreUsers as $uData) {
            $u = User::updateOrCreate(
                ['email' => $uData['email']],
                [
                    'name' => $uData['name'],
                    'password' => Hash::make('password'),
                    'clinic_id' => $clinic->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                    'phone' => '+977-980' . rand(1000000, 9999999),
                ]
            );
            $u->syncRoles([$uData['role']]);
        }

        // Create a large batch of test patients
        $firstNames = ['Ram', 'Sita', 'Hari', 'Gita', 'Shiva', 'Parvati', 'Anil', 'Sunita', 'Bishnu', 'Laxmi', 'Kiran', 'Maya', 'Nabin', 'Ritu', 'Suraj'];
        $lastNames = ['Bahadur', 'Devi', 'Prasad', 'Kumari', 'Thapa', 'Magar', 'Gurung', 'Rai', 'Limbu', 'Sherpa', 'Tamang', 'Shrestha', 'Joshi', 'Pandey'];
        
        $patientModels = [];
        for ($i = 1; $i <= 20; $i++) {
            $fName = $firstNames[array_rand($firstNames)];
            $lName = $lastNames[array_rand($lastNames)];
            $p = Patient::updateOrCreate(
                ['patient_id' => 'P' . str_pad($i, 6, '0', STR_PAD_LEFT)],
                [
                    'clinic_id' => $clinic->id,
                    'first_name' => $fName,
                    'last_name' => $lName,
                    'email' => strtolower($fName . '.' . $lName . $i . '@example.com'),
                    'phone' => '+977-98' . rand(41000000, 41999999),
                    'date_of_birth' => Carbon::now()->subYears(rand(5, 75))->subMonths(rand(0, 11)),
                    'gender' => rand(0, 1) ? 'male' : 'female',
                    'address' => 'Street ' . $i . ', Kathmandu',
                    'city' => 'Kathmandu',
                    'is_active' => true,
                ]
            );
            $patientModels[] = $p;
        }

        $dentist = User::where('email', 'dentist@clinic.com')->first();
        
        // Create a large batch of appointments (Past, Today, Future)
        $appointmentTypes = ['consultation', 'cleaning', 'checkup', 'extraction', 'root-canal', 'filling'];
        
        foreach ($patientModels as $index => $patient) {
            // Past appointment
            Appointment::create([
                'patient_id' => $patient->id,
                'clinic_id' => $clinic->id,
                'dentist_id' => $dentist->id,
                'appointment_date' => Carbon::now()->subDays(rand(1, 30)),
                'appointment_time' => rand(9, 16) . ':00:00',
                'type' => $appointmentTypes[array_rand($appointmentTypes)],
                'status' => 'completed',
                'treatment_cost' => rand(5, 50) * 100,
                'notes' => 'Past checkup completed.',
            ]);

            // Today's or future appointments
            $daysOffset = rand(0, 14);
            $status = ($daysOffset === 0) ? 'scheduled' : (rand(0, 1) ? 'scheduled' : 'confirmed');
            
            Appointment::create([
                'patient_id' => $patient->id,
                'clinic_id' => $clinic->id,
                'dentist_id' => $dentist->id,
                'appointment_date' => Carbon::now()->addDays($daysOffset),
                'appointment_time' => rand(9, 16) . ':30:00',
                'type' => $appointmentTypes[array_rand($appointmentTypes)],
                'status' => $status,
                'treatment_cost' => rand(10, 100) * 100,
                'notes' => 'Generated appointment.',
            ]);
        }

        // Create test invoices for completed appointments
        $completedAppointments = Appointment::where('status', 'completed')->get();
        foreach ($completedAppointments as $index => $app) {
            $amount = $app->treatment_cost ?? 1500;
            $taxAmount = $amount * 0.13;
            $total = $amount + $taxAmount;
            
            Invoice::create([
                'clinic_id' => $clinic->id,
                'patient_id' => $app->patient_id,
                'appointment_id' => $app->id,
                'invoice_number' => 'INV-' . Carbon::now()->format('Y') . '-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'description' => 'Dental Services: ' . ucfirst($app->type),
                'amount' => $amount,
                'tax_amount' => $taxAmount,
                'total_amount' => $total,
                'status' => rand(0, 3) === 0 ? 'pending' : 'paid',
                'due_date' => Carbon::parse($app->appointment_date)->addDays(7),
                'issued_date' => $app->appointment_date,
            ]);
        }

        // Create test expenses to make the finance dashboard look alive
        $expenseCategories = ['Supplies', 'Rent', 'Utilities', 'Salaries', 'Marketing', 'Equipment Maintenance'];
        for ($i = 0; $i < 10; $i++) {
            Expense::create([
                'clinic_id' => $clinic->id,
                'category' => $expenseCategories[array_rand($expenseCategories)],
                'amount' => rand(500, 5000),
                'description' => 'Monthly ' . $expenseCategories[array_rand($expenseCategories)] . ' expense',
                'expense_date' => Carbon::now()->subDays(rand(1, 30)),
                'status' => 'paid',
            ]);
        }

        $this->command->info('Test data seeded successfully for all roles!');
        $this->command->info('Emails: admin@clinic.com, dentist@clinic.com, receptionist@clinic.com, accountant@clinic.com');
        $this->command->info('Password: "password" for all.');
    }
}