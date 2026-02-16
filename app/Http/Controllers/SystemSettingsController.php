<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Clinic;
use App\Models\LandingPageContent;

class SystemSettingsController extends Controller
{
    /**
     * Display the System Settings dashboard
     */
    public function index(Request $request)
    {
        $clinic = Auth::user()->clinic;
        
        // Debug log to confirm controller entry
        \Illuminate\Support\Facades\Log::info('SystemSettingsController@index accessed for clinic ID: '.($clinic->id ?? 'none'));
        
        if (!$clinic) {
            return redirect()->route('dashboard')
                ->with('error', 'No clinic assigned to your account.');
        }

        // Get or create landing page content
        $landingContent = LandingPageContent::firstOrCreate(
            ['clinic_id' => $clinic->id],
            $this->getDefaultLandingContent($clinic->id)
        );

        // Determine active tab based on route
        $defaultTab = $request->routeIs('clinic.settings.*') ? 'general' : 'features';
        $activeTab = $request->get('tab', $defaultTab);

        return view('admin.system-settings.index', compact('clinic', 'landingContent', 'activeTab'));
    }

    /**
     * Update General Settings (Clinic Info)
     */
    public function updateGeneral(Request $request)
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic) {
            return redirect()->route('dashboard')
                ->with('error', 'No clinic assigned to your account.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'about' => 'nullable|string',
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|in:NPR,USD',
            'appointment_duration' => 'nullable|integer|min:15|max:180',
            'working_hours_start'  => 'nullable|date_format:H:i',
            'working_hours_end'    => 'nullable|date_format:H:i',
            'working_days'         => 'nullable|array',
            'is_active'            => 'nullable|boolean',
            'facebook_url'         => 'nullable|url|max:255',
            'instagram_url'        => 'nullable|url|max:255',
            'linkedin_url'         => 'nullable|url|max:255',
            'youtube_url'          => 'nullable|url|max:255',
        ]);

        $data = $validated;
        if ($request->has('working_days')) {
            $data['working_days'] = json_encode($request->working_days);
        }
        
        $clinic->update($data);

        return redirect()->route('clinic.system-settings.index', ['tab' => 'general'])
            ->with('success', 'General settings updated successfully.');
    }

    /**
     * Update Branding Settings (Logo, Colors, Theme)
     */
    public function updateBranding(Request $request)
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic) {
            return redirect()->route('dashboard')
                ->with('error', 'No clinic assigned to your account.');
        }

        $validated = $request->validate([
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
            'theme_color' => 'nullable|string|max:7',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $request->validate([
                'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            
            // Delete old logo if exists
            if ($clinic->logo && Storage::disk('public')->exists($clinic->logo)) {
                Storage::disk('public')->delete($clinic->logo);
            }
            
            $logoPath = $request->file('logo')->store('clinic-logos', 'public');
            $validated['logo'] = $logoPath;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $request->validate([
                'favicon' => 'image|mimes:ico,png,jpeg|max:512',
            ]);
            
            $faviconPath = $request->file('favicon')->store('clinic-logos', 'public');
            $validated['favicon'] = $faviconPath;
        }

        // Update clinic branding
        $clinic->update([
            'logo' => $validated['logo'] ?? $clinic->logo,
            'favicon' => $validated['favicon'] ?? $clinic->favicon,
            'primary_color' => $request->primary_color ?? $clinic->primary_color,
            'secondary_color' => $request->secondary_color ?? $clinic->secondary_color,
            'accent_color' => $request->accent_color ?? $clinic->accent_color,
            'theme_color' => $request->theme_color ?? $clinic->theme_color,
        ]);

        // Also update landing page theme colors (this is where the landing page reads from)
        $landingContent = LandingPageContent::firstOrCreate(
            ['clinic_id' => $clinic->id],
            $this->getDefaultLandingContent($clinic->id)
        );
        
        $landingContent->update([
            'theme_primary_color' => $request->primary_color ?? $landingContent->theme_primary_color ?? '#2563eb',
            'theme_secondary_color' => $request->secondary_color ?? $landingContent->theme_secondary_color ?? '#1d4ed8',
            'theme_accent_color' => $request->accent_color ?? $landingContent->theme_accent_color ?? '#3b82f6',
        ]);

        return redirect()->route('clinic.system-settings.index', ['tab' => 'branding'])
            ->with('success', 'Branding settings updated successfully.');
    }

    /**
     * Update Landing Page Content
     */
    public function updateLandingPage(Request $request)
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic) {
            return redirect()->route('dashboard')
                ->with('error', 'No clinic assigned to your account.');
        }

        $landingContent = LandingPageContent::firstOrCreate(
            ['clinic_id' => $clinic->id],
            $this->getDefaultLandingContent($clinic->id)
        );

        $validated = $request->validate([
            // Section Visibility
            'show_hero' => 'nullable|boolean',
            'show_stats' => 'nullable|boolean',
            'show_about' => 'nullable|boolean',
            'show_services' => 'nullable|boolean',
            'show_gallery' => 'nullable|boolean',
            'show_testimonials' => 'nullable|boolean',
            'show_faq' => 'nullable|boolean',
            'show_contact' => 'nullable|boolean',
            'show_footer' => 'nullable|boolean',
            
            // Navigation & Header
            'navbar_title' => 'nullable|string|max:255',
            'navbar_tagline' => 'nullable|string|max:255',
            'nav_home_label' => 'nullable|string|max:50',
            'nav_about_label' => 'nullable|string|max:50',
            'nav_services_label' => 'nullable|string|max:50',
            'nav_gallery_label' => 'nullable|string|max:50',
            'nav_testimonials_label' => 'nullable|string|max:50',
            'nav_faq_label' => 'nullable|string|max:50',
            'nav_contact_label' => 'nullable|string|max:50',
            'nav_booking_cta' => 'nullable|string|max:100',
            
            // Stats Section
            'stats_experience' => 'nullable|string|max:20',
            'stats_experience_label' => 'nullable|string|max:50',
            'stats_patients' => 'nullable|string|max:20',
            'stats_patients_label' => 'nullable|string|max:50',
            'stats_success_rate' => 'nullable|string|max:20',
            'stats_success_rate_label' => 'nullable|string|max:50',
            'stats_emergency' => 'nullable|string|max:20',
            'stats_emergency_label' => 'nullable|string|max:50',
            
            // Hero Section
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'hero_cta_primary' => 'nullable|string|max:100',
            'hero_cta_secondary' => 'nullable|string|max:100',
            'hero_video_url' => 'nullable|url|max:255',
            
            // About Section
            'about_title' => 'nullable|string|max:255',
            'about_description' => 'nullable|string',
            'about_years_experience' => 'nullable|integer|min:0',
            'about_doctor_name' => 'nullable|string|max:255',
            
            // Services Section
            'services_title' => 'nullable|string|max:255',
            'services_description' => 'nullable|string',
            
            // Gallery Section
            'gallery_title' => 'nullable|string|max:255',
            'gallery_description' => 'nullable|string',
            'gallery_layout' => 'nullable|string|in:grid,masonry,carousel',
            
            // Testimonials Section
            'testimonials_title' => 'nullable|string|max:255',
            'testimonials_description' => 'nullable|string',
            'testimonials_style' => 'nullable|string|in:slider,grid,list',
            
            // FAQ Section
            'faq_title' => 'nullable|string|max:255',
            'faq_description' => 'nullable|string',
            
            // Contact Section
            'contact_title' => 'nullable|string|max:255',
            'contact_subtitle' => 'nullable|string',
            'contact_get_in_touch_title' => 'nullable|string|max:255',
            'contact_send_message_title' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'contact_address' => 'nullable|string',
            'contact_form_enabled' => 'nullable|boolean',
            'google_maps_url' => 'nullable|url|max:500',
            
            // Theme Settings
            'theme_primary_color' => 'nullable|string|max:7',
            'theme_secondary_color' => 'nullable|string|max:7',
            'theme_accent_color' => 'nullable|string|max:7',
            'theme_font_family' => 'nullable|string|max:100',
            'theme_template' => 'nullable|string|max:50',
            
            // SEO Settings
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'google_analytics_id' => 'nullable|string|max:50',
            'facebook_pixel_id' => 'nullable|string|max:50',
            
            // Footer Settings
            'footer_description' => 'nullable|string',
            'footer_copyright' => 'nullable|string|max:255',
            'footer_tagline' => 'nullable|string|max:255',
            
            // Social Media
            'social_facebook' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',

            // Dynamic Lists (Files are validated separately below)
            'services' => 'nullable|array',
            'testimonials' => 'nullable|array',
            'faq' => 'nullable|array',
            
            // Status
            'is_active' => 'nullable|boolean',
            'show_hero' => 'nullable|boolean',
            'show_stats' => 'nullable|boolean',
            'show_about' => 'nullable|boolean',
            'show_services' => 'nullable|boolean',
            'show_gallery' => 'nullable|boolean',
            'show_testimonials' => 'nullable|boolean',
            'show_faq' => 'nullable|boolean',
            'show_contact' => 'nullable|boolean',
            'show_footer' => 'nullable|boolean',
        ]);

        // Handle boolean fields
        $validated['contact_form_enabled'] = $request->has('contact_form_enabled');
        $validated['is_active'] = $request->has('is_active');
        $validated['show_hero'] = $request->has('show_hero');
        $validated['show_stats'] = $request->has('show_stats');
        $validated['show_about'] = $request->has('show_about');
        $validated['show_services'] = $request->has('show_services');
        $validated['show_gallery'] = $request->has('show_gallery');
        $validated['show_testimonials'] = $request->has('show_testimonials');
        $validated['show_faq'] = $request->has('show_faq');
        $validated['show_contact'] = $request->has('show_contact');
        $validated['show_footer'] = $request->has('show_footer');

        // Handle dynamic data arrays (convert and map to correct column names)
        if ($request->has('services')) {
            $validated['services_data'] = $request->services;
        }
        if ($request->has('testimonials')) {
            $validated['testimonials_data'] = $request->testimonials;
        }
        if ($request->has('faq')) {
            $validated['faq_data'] = $request->faq;
        }

        // Handle hero image upload
        if ($request->hasFile('hero_image')) {
            $request->validate(['hero_image' => 'image|mimes:jpeg,png,jpg,webp|max:20480']);
            
            if ($landingContent->hero_image && Storage::disk('public')->exists($landingContent->hero_image)) {
                Storage::disk('public')->delete($landingContent->hero_image);
            }
            
            $imagePath = $request->file('hero_image')->store('landing-images/clinic-' . $clinic->id, 'public');
            $validated['hero_image'] = $imagePath;
        }

        // Handle about image upload
        if ($request->hasFile('about_image')) {
            $request->validate(['about_image' => 'image|mimes:jpeg,png,jpg,webp|max:20480']);
            
            if ($landingContent->about_image && Storage::disk('public')->exists($landingContent->about_image)) {
                Storage::disk('public')->delete($landingContent->about_image);
            }
            
            $imagePath = $request->file('about_image')->store('landing-images/clinic-' . $clinic->id, 'public');
            $validated['about_image'] = $imagePath;
        }

        // Handle hero carousel images (Merge with existing)
        if ($request->hasFile('hero_carousel_images')) {
            $request->validate(['hero_carousel_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:20480']);
            $carouselImages = [];
            foreach ($request->file('hero_carousel_images') as $image) {
                $path = $image->store('landing-images/clinic-' . $clinic->id, 'public');
                $carouselImages[] = $path;
            }
            $existing = is_array($landingContent->hero_carousel_images) ? $landingContent->hero_carousel_images : [];
            $validated['hero_carousel_images'] = array_merge($existing, $carouselImages);
        }

        // Process existing gallery descriptions before new uploads
        $currentGallery = is_array($landingContent->gallery_images) ? $landingContent->gallery_images : [];
        if ($request->has('gallery_meta')) {
            $meta = $request->input('gallery_meta');
            foreach ($currentGallery as &$item) {
                $path = is_array($item) ? ($item['path'] ?? '') : $item;
                if (isset($meta[$path])) {
                    $item = [
                        'path' => $path,
                        'description' => $meta[$path]
                    ];
                } elseif (is_string($item)) {
                     // Convert legacy string to array format even if no meta provided
                     $item = ['path' => $item, 'description' => ''];
                }
            }
            $validated['gallery_images'] = $currentGallery;
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            $request->validate(['gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:20480']);
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('landing-images/clinic-' . $clinic->id, 'public');
                $galleryImages[] = [
                    'path' => $path,
                    'description' => '' // Initial empty description
                ];
            }
            $existing = isset($validated['gallery_images']) ? $validated['gallery_images'] : $currentGallery;
            $validated['gallery_images'] = array_merge($existing, $galleryImages);
        }

        $landingContent->update($validated);

        return redirect()->route('clinic.system-settings.index', ['tab' => 'landing-page'])
            ->with('success', 'Landing page content updated successfully.');
    }
    
    /**
     * Delete a specific image from the landing page arrays.
     */
    public function deleteLandingImage(Request $request)
    {
        $clinic = Auth::user()->clinic;
        if (!$clinic) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $landingContent = LandingPageContent::where('clinic_id', $clinic->id)->firstOrFail();
        
        $type = $request->input('type'); // 'hero_carousel_images' or 'gallery_images'
        $path = $request->input('path');

        if (!in_array($type, ['hero_carousel_images', 'gallery_images'])) {
            return redirect()->back()->with('error', 'Invalid image type.');
        }

        // Get the current array
        $images = $landingContent->$type;
        if (!is_array($images)) {
            $images = [];
        }
        
        // Find the index of the path
        $foundKey = -1;
        foreach ($images as $key => $image) {
            $currentPath = is_array($image) ? ($image['path'] ?? '') : $image;
            if ($currentPath === $path) {
                $foundKey = $key;
                break;
            }
        }

        if ($foundKey !== -1) {
            // Remove from array
            unset($images[$foundKey]);
            
            // Re-index array to prevent gaps
            $newImages = array_values($images);
            
            // Update the model field
            $landingContent->$type = $newImages;
            $landingContent->save();

            // Delete physically from storage
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()->back()->with('success', 'Image removed successfully.');
        }

        return redirect()->back()->with('error', 'Image not found in this collection.');
    }


    /**
     * Update Navigation Settings
     */
    public function updateNavigation(Request $request)
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic) {
            return redirect()->route('dashboard')
                ->with('error', 'No clinic assigned to your account.');
        }

        $validated = $request->validate([
            'navigation_menu' => 'nullable|array',
            'navigation_menu.*.label' => 'required|string|max:50',
            'navigation_menu.*.url' => 'required|string|max:100',
            'navigation_menu.*.visible' => 'nullable|boolean',
            'navigation_menu.*.order' => 'nullable|integer|min:0',
            'show_in_landing_menu' => 'nullable|boolean',
            'show_services_menu' => 'nullable|boolean',
            'show_team_menu' => 'nullable|boolean',
            'show_gallery_menu' => 'nullable|boolean',
            'show_contact_menu' => 'nullable|boolean',
            'show_booking_button' => 'nullable|boolean',
            'booking_button_text' => 'nullable|string|max:50',
            'booking_button_style' => 'nullable|string|in:primary,secondary,outline',
        ]);

        // Get landing content
        $landingContent = LandingPageContent::firstOrCreate(
            ['clinic_id' => $clinic->id],
            $this->getDefaultLandingContent($clinic->id)
        );

        // Update custom navigation menu
        $navigationMenu = $validated['navigation_menu'] ?? [];
        $landingContent->update(['custom_navigation' => $navigationMenu]);

        // Update clinic settings
        $clinic->update([
            'show_in_landing_menu' => $request->has('show_in_landing_menu'),
            'show_services_menu' => $request->has('show_services_menu'),
            'show_team_menu' => $request->has('show_team_menu'),
            'show_gallery_menu' => $request->has('show_gallery_menu'),
            'show_contact_menu' => $request->has('show_contact_menu'),
            'show_booking_button' => $request->has('show_booking_button'),
            'booking_button_text' => $validated['booking_button_text'] ?? 'Book Appointment',
            'booking_button_style' => $validated['booking_button_style'] ?? 'primary',
        ]);

        return redirect()->route('clinic.system-settings.index', ['tab' => 'navigation'])
            ->with('success', 'Navigation settings updated successfully.');
    }

    /**
     * Update SEO Settings
     */
    public function updateSeo(Request $request)
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic) {
            return redirect()->route('dashboard')
                ->with('error', 'No clinic assigned to your account.');
        }

        $landingContent = LandingPageContent::firstOrCreate(
            ['clinic_id' => $clinic->id],
            $this->getDefaultLandingContent($clinic->id)
        );

        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'google_analytics_id' => 'nullable|string|max:50',
            'facebook_pixel_id' => 'nullable|string|max:50',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle OG Image upload
        if ($request->hasFile('og_image')) {
            // Delete old image if exists
            if ($landingContent->og_image && Storage::disk('public')->exists($landingContent->og_image)) {
                Storage::disk('public')->delete($landingContent->og_image);
            }
            
            $ogImagePath = $request->file('og_image')->store('landing-images/' . $clinic->id, 'public');
            $validated['og_image'] = $ogImagePath;
        }

        $landingContent->update($validated);

        return redirect()->route('clinic.system-settings.index', ['tab' => 'seo'])
            ->with('success', 'SEO settings updated successfully.');
    }

    /**
     * Update Business Hours
     */
    public function updateBusinessHours(Request $request)
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic) {
            return redirect()->route('dashboard')
                ->with('error', 'No clinic assigned to your account.');
        }

        $validated = $request->validate([
            'business_hours' => 'nullable|array',
            'business_hours.*.day' => 'required|string',
            'business_hours.*.open' => 'nullable|date_format:H:i',
            'business_hours.*.close' => 'nullable|date_format:H:i',
            'business_hours.*.is_closed' => 'nullable|boolean',
        ]);

        $businessHours = [];
        if ($request->has('business_hours')) {
            foreach ($request->input('business_hours') as $hours) {
                $day = strtolower($hours['day']);
                $businessHours[$day] = [
                    'open' => $hours['open'] ?? '09:00',
                    'close' => $hours['close'] ?? '17:00',
                    'closed' => isset($hours['is_closed']) && $hours['is_closed'] == '1',
                ];
            }
        }

        $landingContent = LandingPageContent::firstOrCreate(
            ['clinic_id' => $clinic->id],
            $this->getDefaultLandingContent($clinic->id)
        );

        $landingContent->update(['business_hours' => $businessHours]);

        return redirect()->route('clinic.system-settings.index', ['tab' => 'business-hours'])
            ->with('success', 'Business hours updated successfully.');
    }

    /**
     * Update Feature Settings (Modules, Features)
     */
    public function updateFeatures(Request $request)
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic) {
            return redirect()->route('dashboard')
                ->with('error', 'No clinic assigned to your account.');
        }

        $validated = $request->validate([
            'enabled_modules' => 'nullable|array',
            'has_landing_page' => 'nullable|boolean',
            'has_crm' => 'nullable|boolean',
            'has_patient_portal' => 'nullable|boolean',
            'has_email_system' => 'nullable|boolean',
            'has_notifications' => 'nullable|boolean',
            'has_analytics' => 'nullable|boolean',
            'has_accounting' => 'nullable|boolean',
            'has_inventory' => 'nullable|boolean',
            'has_lab_integration' => 'nullable|boolean',
            'has_telemedicine' => 'nullable|boolean',
        ]);

        $clinic->update([
            'enabled_modules' => $validated['enabled_modules'] ?? [],
            'has_landing_page' => $request->has('has_landing_page'),
            'has_crm' => $request->has('has_crm'),
            'has_patient_portal' => $request->has('has_patient_portal'),
            'has_email_system' => $request->has('has_email_system'),
            'has_notifications' => $request->has('has_notifications'),
            'has_analytics' => $request->has('has_analytics'),
            'has_accounting' => $request->has('has_accounting'),
            'has_inventory' => $request->has('has_inventory'),
            'has_lab_integration' => $request->has('has_lab_integration'),
            'has_telemedicine' => $request->has('has_telemedicine'),
        ]);

        return redirect()->route('clinic.system-settings.index', ['tab' => 'features'])
            ->with('success', 'Feature settings updated successfully.');
    }

    /**
     * Preview Landing Page
     */
    public function preview()
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic) {
            return redirect()->route('dashboard')
                ->with('error', 'No clinic assigned to your account.');
        }

        $landingContent = LandingPageContent::firstOrCreate(
            ['clinic_id' => $clinic->id],
            $this->getDefaultLandingContent($clinic->id)
        );

        // Generate preview URL based on clinic slug
        $previewUrl = route('clinic.landing', ['slug' => $clinic->slug]);

        return redirect($previewUrl);
    }

    /**
     * Toggle Landing Page Status
     */
    public function toggleStatus(Request $request)
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic) {
            return redirect()->route('dashboard')
                ->with('error', 'No clinic assigned to your account.');
        }

        $landingContent = LandingPageContent::where('clinic_id', $clinic->id)->first();
        
        if ($landingContent) {
            $landingContent->update(['is_active' => !$landingContent->is_active]);
            $status = $landingContent->is_active ? 'activated' : 'deactivated';
        } else {
            LandingPageContent::create([
                'clinic_id' => $clinic->id,
                'is_active' => true,
            ]);
            $status = 'activated';
        }

        return redirect()->route('clinic.system-settings.index')
            ->with('success', "Landing page {$status} successfully.");
    }

    /**
     * Get default landing page content
     */
    private function getDefaultLandingContent($clinicId)
    {
        return [
            'clinic_id' => $clinicId,
            'hero_title' => 'Welcome to Our Dental Clinic',
            'hero_subtitle' => 'Your smile is our priority. We provide comprehensive dental care for the whole family.',
            'hero_cta_primary' => 'Book Appointment',
            'hero_cta_secondary' => 'Learn More',
            'about_title' => 'About Our Clinic',
            'about_description' => 'We are committed to providing the highest quality dental care in a comfortable and relaxing environment.',
            'services_title' => 'Our Services',
            'services_description' => 'Comprehensive dental services for all your needs',
            'gallery_title' => 'Our Gallery',
            'testimonials_title' => 'What Our Patients Say',
            'faq_title' => 'Frequently Asked Questions',
            'contact_title' => 'Contact Us',
            'contact_subtitle' => 'Get in touch with us',
            'contact_form_enabled' => true,
            'is_active' => true,
            'show_hero' => true,
            'show_stats' => true,
            'show_about' => true,
            'show_services' => true,
            'show_gallery' => true,
            'show_testimonials' => true,
            'show_faq' => true,
            'show_contact' => true,
            'show_footer' => true,
            'theme_primary_color' => '#3b82f6',
            'theme_secondary_color' => '#06b6d4',
            'theme_accent_color' => '#8b5cf6',
            'footer_copyright' => '© ' . date('Y') . ' All rights reserved.',
        ];
    }
}
