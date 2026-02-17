<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingPageContent;

class LandingPageContentSeeder extends Seeder
{
    public function run()
    {
        LandingPageContent::create([
            'clinic_id' => null, // Global content
            'hero_title' => "Nepal's Most Advanced Dental Platform",
            'hero_subtitle' => 'Transform your dental clinic with our comprehensive management solution. Built by ABS Soft specifically for Nepal\'s healthcare industry.',
            'hero_cta_primary' => 'Start 14-Day Free Trial',
            'hero_cta_secondary' => 'Watch Demo',
            'trust_clinics' => '500+',
            'trust_patients' => '50K+',
            'trust_appointments' => '2M+',
            'trust_uptime' => '99.9%',
            'trust_revenue' => 'NPR 50Cr+',
            'about_title' => 'Designed for Nepal\'s Healthcare Excellence',
            'about_description' => 'Understanding Nepal\'s unique healthcare challenges, we\'ve built a comprehensive platform with local language support, NPR billing, and full regulatory compliance.',
            'company_name' => 'ABS Soft',
            'company_tagline' => 'Leading Software Solutions Provider',
            'company_rating' => '4.9',
            'company_description' => 'DentalCare Pro is proudly developed by ABS Soft, Nepal\'s premier software development company. With years of experience in healthcare technology, we deliver cutting-edge solutions tailored for Nepal\'s unique market needs.',
            'contact_title' => 'Ready to Transform Your Practice?',
            'contact_subtitle' => 'Join hundreds of dental clinics across Nepal',
            'footer_description' => 'DentalCare Pro is proudly developed by ABS Soft, Nepal\'s leading software development company.',
            'footer_copyright' => '© 2024 ABS Soft. All rights reserved. DentalCare Pro - Made with ❤️ in Nepal 🇳🇵',
            'theme_primary_color' => '#3b82f6',
            'theme_secondary_color' => '#06b6d4',
            'theme_accent_color' => '#8b5cf6',
            'theme_template' => 'default',
            'is_active' => true
        ]);
    }
}