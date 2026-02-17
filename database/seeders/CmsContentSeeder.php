<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clinic;
use App\Models\ClinicService;
use App\Models\TeamMember;

class CmsContentSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get first clinic (or create one for demo)
        $clinic = Clinic::first();
        
        if (!$clinic) {
            $this->command->error('No clinic found! Please create a clinic first.');
            return;
        }

        $this->command->info("Seeding CMS content for clinic: {$clinic->name}");

        // Seed Services
        $this->seedServices($clinic);
        
        // Seed Team Members
        $this->seedTeamMembers($clinic);

        $this->command->info('✅ CMS content seeded successfully!');
    }

    private function seedServices($clinic)
    {
        $services = [
            [
                'name' => 'Dental Cleaning & Checkup',
                'category' => 'Preventive Care',
                'description' => 'Regular dental cleaning and comprehensive oral examination to maintain optimal dental health. Includes plaque removal, polishing, and fluoride treatment.',
                'short_description' => 'Professional teeth cleaning and oral health assessment',
                'duration_minutes' => 60,
                'price' => 2500.00,
                'show_pricing' => true,
                'featured_image' => 'services/cleaning.jpg', // User can add this
                'seo_title' => 'Professional Dental Cleaning in Kathmandu | Expert Dental Care',
                'seo_description' => 'Get professional dental cleaning and checkup services. Our experienced dentists provide thorough oral examinations and treatments.',
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'Teeth Whitening',
                'category' => 'Cosmetic Dentistry',
                'description' => 'Advanced teeth whitening treatment using safe and effective methods. Get a brighter, whiter smile in just one session. Our professional-grade whitening removes years of stains.',
                'short_description' => 'Professional teeth whitening for a brighter smile',
                'duration_minutes' => 90,
                'price' => 15000.00,
                'show_pricing' => true,
                'featured_image' => 'services/whitening.jpg',
                'seo_title' => 'Teeth Whitening Services in Nepal | Smile Dental Clinic',
                'seo_description' => 'Professional teeth whitening services for a brighter, confident smile. Safe and effective treatments.',
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'Root Canal Treatment',
                'category' => 'Restorative Dentistry',
                'description' => 'Expert root canal therapy to save infected or damaged teeth. Our painless procedure removes infection while preserving your natural tooth structure.',
                'short_description' => 'Painless root canal treatment to save your tooth',
                'duration_minutes' => 120,
                'price' => 12000.00,
                'show_pricing' => true,
                'featured_image' => 'services/root-canal.jpg',
                'seo_title' => 'Root Canal Treatment in Kathmandu | Pain-Free Dentistry',
                'seo_description' => 'Expert root canal treatment with advanced techniques. Save your natural teeth with our painless procedures.',
                'is_active' => true,
                'display_order' => 3,
            ],
            [
                'name' => 'Dental Implants',
                'category' => 'Restorative Dentistry',
                'description' => 'Permanent tooth replacement solution with dental implants. Restore your smile with natural-looking, durable implants that function like real teeth.',
                'short_description' => 'Permanent tooth replacement with dental implants',
                'duration_minutes' => 180,
                'price' => 50000.00,
                'show_pricing' => true,
                'featured_image' => 'services/implants.jpg',
                'seo_title' => 'Dental Implants in Nepal | Permanent Tooth Replacement',
                'seo_description' => 'Get natural-looking dental implants. Permanent solution for missing teeth with expert care.',
                'is_active' => true,
                'display_order' => 4,
            ],
            [
                'name' => 'Orthodontic Braces',
                'category' => 'Orthodontics',
                'description' => 'Straighten your teeth with modern orthodontic braces. We offer metal braces, ceramic braces, and invisible aligners for all ages.',
                'short_description' => 'Teeth straightening with modern braces and aligners',
                'duration_minutes' => 60,
                'price' => 0.00, // Contact for pricing
                'show_pricing' => false,
                'featured_image' => 'services/braces.jpg',
                'seo_title' => 'Orthodontic Braces in Kathmandu | Teeth Straightening',
                'seo_description' => 'Professional orthodontic treatment with braces and aligners. Achieve a perfect smile at any age.',
                'is_active' => true,
                'display_order' => 5,
            ],
            [
                'name' => 'Wisdom Tooth Extraction',
                'category' => 'Oral Surgery',
                'description' => 'Safe and painless wisdom tooth removal by experienced oral surgeons. We handle simple to complex extractions with minimal discomfort.',
                'short_description' => 'Safe wisdom tooth removal with expert care',
                'duration_minutes' => 90,
                'price' => 8000.00,
                'show_pricing' => true,
                'featured_image' => 'services/extraction.jpg',
                'seo_title' => 'Wisdom Tooth Extraction in Nepal | Painless Surgery',
                'seo_description' => 'Expert wisdom tooth extraction with minimal pain. Safe surgical procedures by experienced dentists.',
                'is_active' => true,
                'display_order' => 6,
            ],
        ];

        foreach ($services as $serviceData) {
            $serviceData['clinic_id'] = $clinic->id;
            ClinicService::create($serviceData);
        }

        $this->command->info('  ✓ Created ' . count($services) . ' services');
    }

    private function seedTeamMembers($clinic)
    {
        $teamMembers = [
            [
                'name' => 'Dr. Rajesh Sharma',
                'title' => 'Chief Dental Surgeon',
                'specialization' => 'Cosmetic & Restorative Dentistry',
                'bio' => 'Dr. Rajesh Sharma is a highly experienced dental surgeon with over 15 years of practice. Specialized in cosmetic procedures and smile makeovers, he has transformed thousands of smiles across Nepal.',
                'photo' => 'team/dr-rajesh.jpg',
                'education' => 'BDS - Tribhuvan University, MDS - India, Fellowship in Cosmetic Dentistry',
                'experience_years' => 15,
                'languages' => 'English, Nepali, Hindi',
                'email' => 'dr.rajesh@smiledental.com',
                'phone' => '+977-1-4567890',
                'is_featured' => true,
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'Dr. Anita Thapa',
                'title' => 'Orthodontist',
                'specialization' => 'Orthodontics & Dentofacial Orthopedics',
                'bio' => 'Dr. Anita Thapa specializes in orthodontic treatments including braces and aligners. With a gentle approach and attention to detail, she helps patients achieve perfectly aligned smiles.',
                'photo' => 'team/dr-anita.jpg',
                'education' => 'BDS, MDS (Orthodontics) - India',
                'experience_years' => 10,
                'languages' => 'English, Nepali',
                'email' => 'dr.anita@smiledental.com',
                'phone' => '+977-1-4567891',
                'is_featured' => true,
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'Dr. Suresh Karki',
                'title' => 'Endodontist',
                'specialization' => 'Root Canal Specialist',
                'bio' => 'Dr. Suresh Karki is an expert in painless root canal treatments. Using the latest rotary endodontic systems, he ensures comfortable and efficient procedures.',
                'photo' => 'team/dr-suresh.jpg',
                'education' => 'BDS - BPKIHS, MDS (Conservative Dentistry & Endodontics)',
                'experience_years' => 12,
                'languages' => 'English, Nepali',
                'email' => 'dr.suresh@smiledental.com',
                'phone' => '+977-1-4567892',
                'is_featured' => false,
                'is_active' => true,
                'display_order' => 3,
            ],
            [
                'name' => 'Dr. Priya Nepal',
                'title' => 'Pediatric Dentist',
                'specialization' => 'Children\'s Dentistry',
                'bio' => 'Dr. Priya Nepal has a special way with children. She creates a fun and comfortable environment for young patients while providing excellent dental care.',
                'photo' => 'team/dr-priya.jpg',
                'education' => 'BDS, Certificate in Pediatric Dentistry',
                'experience_years' => 8,
                'languages' => 'English, Nepali, Hindi',
                'email' => 'dr.priya@smiledental.com',
                'phone' => '+977-1-4567893',
                'is_featured' => false,
                'is_active' => true,
                'display_order' => 4,
            ],
        ];

        foreach ($teamMembers as $memberData) {
            $memberData['clinic_id'] = $clinic->id;
            TeamMember::create($memberData);
        }

        $this->command->info('  ✓ Created ' . count($teamMembers) . ' team members');
    }
}
