<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LandingPageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'hero_title',
        'hero_subtitle', 
        'hero_image',
        'hero_cta_primary',
        'hero_cta_secondary',
        'hero_carousel_images',
        'hero_video_url',
        'about_title',
        'about_description',
        'about_image',
        'about_years_experience',
        'about_doctor_name',
        'services_title',
        'services_description',
        'services_data',
        'gallery_title',
        'gallery_images',
        'gallery_layout',
        'testimonials_title',
        'testimonials_data',
        'testimonials_style',
        'faq_title',
        'faq_data',
        'faq_description',
        'contact_title',
        'contact_subtitle',
        'contact_phone',
        'contact_email',
        'contact_address',
        'contact_form_enabled',
        'business_hours',
        'google_maps_url',
        'theme_primary_color',
        'theme_secondary_color',
        'theme_accent_color',
        'theme_font_family',
        'theme_template',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'google_analytics_id',
        'facebook_pixel_id',
        'footer_description',
        'footer_copyright',
        'social_facebook',
        'social_instagram',
        'social_twitter',
        'social_linkedin',
        'custom_sections',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hero_carousel_images' => 'array',
        'gallery_images' => 'array',
        'services_data' => 'array',
        'faq_data' => 'array',
        'testimonials_data' => 'array',
        'custom_sections' => 'array',
        'contact_form_enabled' => 'boolean',
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public static function getContent($clinicId = null)
    {
        return self::where('clinic_id', $clinicId)
            ->where('is_active', true)
            ->first() ?? self::getDefaultContent();
    }

    public static function getDefaultContent()
    {
        return new self([
            'hero_title' => 'Nepal\'s Most Advanced Dental Platform',
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
            'theme_template' => 'default'
        ]);
    }

    public function getImageUrl($field)
    {
        $image = $this->$field;
        if (!$image) return null;
        
        // If it's already a full URL, return as is
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }
        
        // Otherwise, treat as stored file
        return Storage::url($image);
    }
    
    public function getGalleryImageUrl($filename)
    {
        if (!$filename) return null;
        
        // If it's already a full URL, return as is
        if (filter_var($filename, FILTER_VALIDATE_URL)) {
            return $filename;
        }
        
        // Otherwise, treat as stored file
        return Storage::url($filename);
    }
}