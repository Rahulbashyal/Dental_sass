<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clinic;
use App\Models\Tenant;
use App\Models\LandingPageContent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SiddharthaClinicSeeder extends Seeder
{
    public function run()
    {
        $slug = 'siddhartha-dental-home';
        
        // 1. Create Clinic
        $clinic = Clinic::updateOrCreate(
            ['slug' => $slug],
            [
                'name' => 'Siddhartha Dental Home',
                'email' => 'contact@siddharthadental.com.np',
                'phone' => '+977-71-XXXXXX',
                'address' => 'Butwal, Rupandehi',
                'city' => 'Butwal',
                'country' => 'Nepal',
                'is_active' => true,
                'has_landing_page' => true,
                'business_type' => 'dental_clinic',
                'timezone' => 'Asia/Kathmandu',
                'currency' => 'NPR',
                'subscription_tier' => 'enterprise',
                'enabled_modules' => ['appointments', 'patients', 'billing', 'crm', 'inventory'],
                'enabled_roles' => ['clinic_admin', 'dentist', 'receptionist'],
            ]
        );

        // 2. Create Tenant (Multi-domain support)
        $tenant = Tenant::find($slug);
        if (!$tenant) {
            $tenant = Tenant::create([
                'id' => $slug,
                'clinic_id' => $clinic->id,
                'data' => []
            ]);
        }
        
        // Domain mapping
        if (!$tenant->domains()->where('domain', 'siddharthadental.localhost')->exists()) {
            $tenant->domains()->create(['domain' => 'siddharthadental.localhost']);
        }

        // 3. Create/Update Landing Page Content with Siddhartha 3D Theme
        LandingPageContent::updateOrCreate(
            ['clinic_id' => $clinic->id],
            [
                'theme_template' => 'siddhartha',
                'vision_hook' => 'Precision is our Practice. Your Smile is our Story.',
                'hero_title' => 'Siddhartha Dental Home',
                'hero_subtitle' => 'The Pinnacle of Specialized Dental Care in Butwal.',
                'navbar_title' => 'Siddhartha',
                'footer_description' => 'A premier 5-specialist dental ecosystem committed to clinical excellence and patient-first storytelling.',
                'theme_primary_color' => '#3b82f6',
                'theme_secondary_color' => '#1d4ed8',
                'is_active' => true,
                'custom_sections' => [
                    'specialists' => [
                        ['name' => 'Dr. Santosh Kandel', 'title' => 'Oral & Maxillofacial Surgeon', 'focus' => 'Implants'],
                        ['name' => 'Dr. Gyanendra Jha', 'title' => 'Endodontist', 'focus' => 'Root Canal'],
                        ['name' => 'Dr. Raju Shrestha', 'title' => 'Orthodontist', 'focus' => 'Braces'],
                        ['name' => 'Dr. Reecha Bhadel', 'title' => 'Prosthodontist', 'focus' => 'Aesthetics'],
                        ['name' => 'Dr. Suman Chhetri', 'title' => 'Senior Surgeon', 'focus' => 'Clinical'],
                    ]
                ]
            ]
        );

        // 4. Create Admin User for this Clinic
        User::updateOrCreate(
            ['email' => 'admin@siddharthadental.com.np'],
            [
                'name' => 'Siddhartha Admin',
                'password' => 'password', // Auto-hashed by model
                'clinic_id' => $clinic->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        )->assignRole('clinic_admin');
    }
}
