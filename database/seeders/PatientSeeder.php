<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Clinic;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds - Create sample patients for testing
     */
    public function run(): void
    {
        // Get first clinic for testing
        $clinic = Clinic::first();
        
        if (!$clinic) {
            $this->command->warn('No clinic found. Please create a clinic first.');
            return;
        }

        $patients = [
            [
                'clinic_id' => $clinic->id,
                'first_name' => 'Ram',
                'last_name' => 'Sharma',
                'email' => 'ram.sharma@example.com',
                'phone' => '9841234567',
                'date_of_birth' => '1990-05-15',
                'gender' => 'male',
                'address' => 'Kathmandu, Nepal',
                'medical_history' => 'No significant medical history',
                'allergies' => null,
                'is_active' => true,
            ],
            [
                'clinic_id' => $clinic->id,
                'first_name' => 'Sita',
                'last_name' => 'Thapa',
                'email' => 'sita.thapa@example.com',
                'phone' => '9851234567',
                'date_of_birth' => '1985-08-22',
                'gender' => 'female',
                'address' => 'Lalitpur, Nepal',
                'medical_history' => 'Type 2 Diabetes - on Metformin 500mg',
                'allergies' => 'Penicillin',
                'is_active' => true,
            ],
            [
                'clinic_id' => $clinic->id,
                'first_name' => 'Hari',
                'last_name' => 'Bahadur',
                'email' => 'hari.bahadur@example.com',
                'phone' => '9861234567',
                'date_of_birth' => '1978-12-10',
                'gender' => 'male',
                'address' => 'Bhaktapur, Nepal',
                'medical_history' => 'Hypertension - on Amlodipine 5mg',
                'allergies' => 'Aspirin',
                'is_active' => true,
            ],
            [
                'clinic_id' => $clinic->id,
                'first_name' => 'Maya',
                'last_name' => 'Gurung',
                'email' => 'maya.gurung@example.com',
                'phone' => '9871234567',
                'date_of_birth' => '1995-03-28',
                'gender' => 'female',
                'address' => 'Pokhara, Nepal',
                'medical_history' => 'No medical conditions',
                'allergies' => null,
                'is_active' => true,
            ],
            [
                'clinic_id' => $clinic->id,
                'first_name' => 'Krishna',
                'last_name' => 'Rai',
                'email' => 'krishna.rai@example.com',
                'phone' => '9881234567',
                'date_of_birth' => '2000-07-14',
                'gender' => 'male',
                'address' => 'Biratnagar, Nepal',
                'medical_history' => 'Asthma - uses inhaler occasionally',
                'allergies' => null,
                'is_active' => true,
            ],
            [
                'clinic_id' => $clinic->id,
                'first_name' => 'Gita',
                'last_name' => 'Pradhan',
                'email' => 'gita.pradhan@example.com',
                'phone' => '9801234567',
                'date_of_birth' => '1988-11-30',
                'gender' => 'female',
                'address' => 'Patan, Nepal',
                'medical_history' => 'Gastritis',
                'allergies' => 'Ibuprofen',
                'is_active' => true,
            ],
            [
                'clinic_id' => $clinic->id,
                'first_name' => 'Rajesh',
                'last_name' => 'Maharjan',
                'email' => 'rajesh.maharjan@example.com',
                'phone' => '9811234567',
                'date_of_birth' => '1972-04-18',
                'gender' => 'male',
                'address' => 'Kirtipur, Nepal',
                'medical_history' => 'Chronic tooth sensitivity',
                'allergies' => null,
                'is_active' => true,
            ],
            [
                'clinic_id' => $clinic->id,
                'first_name' => 'Sunita',
                'last_name' => 'Shrestha',
                'email' => 'sunita.shrestha@example.com',
                'phone' => '9821234567',
                'date_of_birth' => '1998-09-05',
                'gender' => 'female',
                'address' => 'Budhanilkantha, Nepal',
                'medical_history' => 'No significant history',
                'allergies' => null,
                'is_active' => true,
            ],
        ];

        foreach ($patients as $patientData) {
            Patient::create($patientData);
        }

        $this->command->info('Successfully created 8 sample patients for clinic: ' . $clinic->name);
    }
}
