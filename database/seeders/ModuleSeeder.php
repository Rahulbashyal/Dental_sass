<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'name'         => 'CoreDental',
                'display_name' => 'Core Dental Services',
                'description'  => 'Core dental platform features required for platform operation.',
                'is_active'    => true,
                'is_core'      => true,
            ],
            [
                'name'         => 'Patients',
                'display_name' => 'Patient Management',
                'description'  => 'Full patient lifecycle management including records, history, and profiles.',
                'is_active'    => true,
                'is_core'      => false,
            ],
            [
                'name'         => 'Appointments',
                'display_name' => 'Appointment Scheduling',
                'description'  => 'Appointment booking, scheduling, confirmations, and reminders.',
                'is_active'    => true,
                'is_core'      => false,
            ],
            [
                'name'         => 'Treatments',
                'display_name' => 'Clinical Treatments',
                'description'  => 'Treatment plans, prescriptions, clinical notes, and dental procedures.',
                'is_active'    => true,
                'is_core'      => false,
            ],
            [
                'name'         => 'Financials',
                'display_name' => 'Financial Management',
                'description'  => 'Invoicing, billing, expenses, payments, and financial reporting.',
                'is_active'    => true,
                'is_core'      => false,
            ],
            [
                'name'         => 'Billing',
                'display_name' => 'Billing & Invoicing',
                'description'  => 'Detailed billing, invoice generation, and payment tracking.',
                'is_active'    => true,
                'is_core'      => false,
                'dependencies' => ['Financials'],
            ],
            [
                'name'         => 'Inventory',
                'display_name' => 'Inventory & Supplies',
                'description'  => 'Dental supplies, equipment tracking, and purchase orders.',
                'is_active'    => true,
                'is_core'      => false,
            ],
            [
                'name'         => 'MultiBranch',
                'display_name' => 'Multi-Branch Management',
                'description'  => 'Manage multiple clinic branches and cross-branch reporting.',
                'is_active'    => true,
                'is_core'      => false,
            ],
        ];

        foreach ($modules as $moduleData) {
            Module::updateOrCreate(
                ['name' => $moduleData['name']],
                $moduleData
            );
        }

        $this->command->info('Modules seeded: ' . count($modules) . ' modules active.');
    }
}
