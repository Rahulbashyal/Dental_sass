<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Clinic extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql';
    
    protected static function booted()
    {
        static::created(function ($clinic) {
            // Create clinic-specific directory for landing page images
            $clinicDir = "landing-images/clinic-{$clinic->id}";
            if (!Storage::disk('public')->exists($clinicDir)) {
                Storage::disk('public')->makeDirectory($clinicDir);
            }
        });
        
        static::deleted(function ($clinic) {
            // Note: Directory deletion is handled by SuperAdmin decision
            // Storage::disk('public')->deleteDirectory("landing-images/clinic-{$clinic->id}");
        });
    }

    protected $fillable = [
        'name', 'slug', 'email', 'phone', 'address', 'city', 'state', 'country',
        'postal_code', 'website', 'logo', 'primary_color', 'secondary_color',
        'theme_color', 'accent_color', 'tagline', 'description', 'about', 'services',
        'gallery_images', 'facebook_url', 'instagram_url', 'website_url',
        'subscription_status', 'subscription_plan', 'subscription_expires_at', 'is_active',
        'subscription_price', 'subscription_features',
        // Configuration fields
        'enabled_modules', 'enabled_roles', 'has_landing_page', 'has_crm',
        'has_patient_portal', 'has_email_system', 'has_notifications', 'has_analytics',
        'has_accounting', 'has_inventory', 'has_lab_integration', 'has_telemedicine',
        'subscription_tier', 'max_users', 'max_patients', 'max_appointments_per_month',
        'has_custom_branding', 'has_api_access', 'has_priority_support',
        'business_type', 'timezone', 'currency', 'business_hours', 'appointment_settings',
        'payment_gateways', 'sms_providers', 'email_providers',
        'appointment_duration', 'working_hours_start', 'working_hours_end', 'working_days',
        'linkedin_url', 'youtube_url',
        'show_in_landing_menu', 'show_services_menu', 'show_team_menu',
        'show_gallery_menu', 'show_contact_menu', 'show_booking_button',
        'booking_button_text', 'booking_button_style',
    ];

    protected function casts(): array
    {
        return [
            'subscription_expires_at' => 'datetime',
            'is_active' => 'boolean',
            'services' => 'array',
            'gallery_images' => 'array',
            'enabled_modules' => 'array',
            'enabled_roles' => 'array',
            'has_landing_page' => 'boolean',
            'has_crm' => 'boolean',
            'has_patient_portal' => 'boolean',
            'has_email_system' => 'boolean',
            'has_notifications' => 'boolean',
            'show_in_landing_menu' => 'boolean',
            'show_services_menu' => 'boolean',
            'show_team_menu' => 'boolean',
            'show_gallery_menu' => 'boolean',
            'show_contact_menu' => 'boolean',
            'show_booking_button' => 'boolean',
            'has_analytics' => 'boolean',
            'has_accounting' => 'boolean',
            'has_inventory' => 'boolean',
            'has_lab_integration' => 'boolean',
            'has_telemedicine' => 'boolean',
            'has_custom_branding' => 'boolean',
            'has_api_access' => 'boolean',
            'has_priority_support' => 'boolean',
            'business_hours' => 'array',
            'appointment_settings' => 'array',
            'payment_gateways' => 'array',
            'sms_providers' => 'array',
            'email_providers' => 'array',
            'working_days' => 'array',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function admins()
    {
        return $this->hasMany(User::class)->role('clinic_admin');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function treatmentPlans()
    {
        return $this->hasMany(TreatmentPlan::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function landingPageContent()
    {
        return $this->hasOne(LandingPageContent::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    
    public function settings()
    {
        return $this->hasOne(ClinicSettings::class);
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Helper methods for configuration checks
    public function hasModule($module)
    {
        return in_array($module, $this->enabled_modules ?? []);
    }

    public function hasRole($role)
    {
        return in_array($role, $this->enabled_roles ?? []);
    }

    public function hasFeature($feature)
    {
        return $this->$feature ?? false;
    }

    public function canCreateUser()
    {
        return $this->users()->count() < ($this->max_users ?? 5);
    }

    public function canCreatePatient()
    {
        return $this->patients()->count() < ($this->max_patients ?? 1000);
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }

    public function isProvisioned()
    {
        return $this->tenant()->exists();
    }
}