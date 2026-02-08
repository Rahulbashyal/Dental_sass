<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Clinic, Patient, Appointment, Invoice};
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

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
        $superadmin = User::firstOrCreate(
            ['email' => 'admin@dentalcare.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $superadmin->assignRole('superadmin');

        // Create test clinic
        $clinic = Clinic::firstOrCreate(
            ['slug' => 'smile-dental-clinic'],
            [
                'name' => 'Smile Dental Clinic',
                'email' => 'info@smiledentalclinic.com',
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
            ]
        );

        // Create clinic admin
        $clinicAdmin = User::firstOrCreate(
            ['email' => 'admin@smiledentalclinic.com'],
            [
                'name' => 'Dr. Rajesh Sharma',
                'password' => Hash::make('password123'),
                'clinic_id' => $clinic->id,
                'phone' => '+977-9841234567',
                'is_active' => true,
            ]
        );
        $clinicAdmin->assignRole('clinic_admin');

        // Create dentist
        $dentist = User::firstOrCreate(
            ['email' => 'dentist@smiledentalclinic.com'],
            [
                'name' => 'Dr. Priya Patel',
                'password' => Hash::make('password123'),
                'clinic_id' => $clinic->id,
                'phone' => '+977-9841234568',
                'is_active' => true,
            ]
        );
        $dentist->assignRole('dentist');

        // Create receptionist
        $receptionist = User::firstOrCreate(
            ['email' => 'reception@smiledentalclinic.com'],
            [
                'name' => 'Sita Gurung',
                'password' => Hash::make('password123'),
                'clinic_id' => $clinic->id,
                'phone' => '+977-9841234569',
                'is_active' => true,
            ]
        );
        $receptionist->assignRole('receptionist');

        // Create test patients
        $patients = [
            [
                'first_name' => 'Ram',
                'last_name' => 'Bahadur',
                'email' => 'ram.bahadur@email.com',
                'phone' => '+977-9841111111',
                'date_of_birth' => '1985-05-15',
                'gender' => 'male',
                'address' => 'Thamel, Kathmandu',
                'city' => 'Kathmandu',
                'patient_id' => 'P000001',
            ],
            [
                'first_name' => 'Sita',
                'last_name' => 'Devi',
                'email' => 'sita.devi@email.com',
                'phone' => '+977-9841111112',
                'date_of_birth' => '1990-08-22',
                'gender' => 'female',
                'address' => 'Patan, Lalitpur',
                'city' => 'Lalitpur',
                'patient_id' => 'P000002',
            ],
            [
                'first_name' => 'Krishna',
                'last_name' => 'Thapa',
                'email' => 'krishna.thapa@email.com',
                'phone' => '+977-9841111113',
                'date_of_birth' => '1988-12-10',
                'gender' => 'male',
                'address' => 'Bhaktapur Durbar Square',
                'city' => 'Bhaktapur',
                'patient_id' => 'P000003',
            ]
        ];

        foreach ($patients as $patientData) {
            $patientData['clinic_id'] = $clinic->id;
            $patientData['is_active'] = true;
            Patient::firstOrCreate(
                ['email' => $patientData['email'], 'clinic_id' => $clinic->id],
                $patientData
            );
        }

        // Create test appointments
        $patients = Patient::where('clinic_id', $clinic->id)->get();
        foreach ($patients as $index => $patient) {
            Appointment::firstOrCreate(
                [
                    'patient_id' => $patient->id,
                    'appointment_date' => now()->addDays($index + 1)->format('Y-m-d')
                ],
                [
                    'clinic_id' => $clinic->id,
                    'dentist_id' => $dentist->id,
                    'appointment_time' => '10:00',
                    'type' => ['consultation', 'cleaning', 'checkup'][$index % 3],
                    'status' => 'scheduled',
                    'treatment_cost' => [2000, 1500, 1000][$index % 3],
                    'notes' => 'Regular appointment for ' . $patient->first_name,
                ]
            );
        }

        // Create test invoices
        $appointments = Appointment::where('clinic_id', $clinic->id)->get();
        foreach ($appointments as $appointment) {
            $amount = $appointment->treatment_cost ?? 1000;
            $taxAmount = $amount * 0.13; // 13% VAT
            $totalAmount = $amount + $taxAmount;
            
            Invoice::firstOrCreate(
                ['appointment_id' => $appointment->id],
                [
                    'clinic_id' => $clinic->id,
                    'patient_id' => $appointment->patient_id,
                    'invoice_number' => 'INV-' . str_pad(Invoice::count() + 1, 6, '0', STR_PAD_LEFT),
                    'description' => 'Dental treatment - ' . $appointment->type,
                    'amount' => $amount,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'due_date' => now()->addDays(30),
                    'issued_date' => now(),
                ]
            );
        }

        $this->command->info('Phase 1 test data seeded successfully!');
        $this->command->info('Superadmin: admin@dentalcare.com / password123');
        $this->command->info('Clinic Admin: admin@smiledentalclinic.com / password123');
        $this->command->info('Dentist: dentist@smiledentalclinic.com / password123');
        $this->command->info('Receptionist: reception@smiledentalclinic.com / password123');
        $this->command->info('Test Clinic: http://localhost:8000/smile-dental-clinic');
    }
}