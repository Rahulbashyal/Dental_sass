<?php

namespace App\Http\Controllers;

use App\Models\LandingPageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClinicAdminController extends Controller
{
    public function dashboard()
    {
        return view('clinic-admin.dashboard');
    }

    public function landingPage()
    {
        $clinic = Auth::user()->clinic;
        $content = LandingPageContent::where('clinic_id', $clinic->id)->first();
        
        return view('clinic-admin.landing-page', compact('clinic', 'content'));
    }

    public function updateLandingPage(Request $request)
    {
        $clinic = Auth::user()->clinic;
        
        $validated = $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'hero_cta_primary' => 'nullable|string|max:100',
            'hero_cta_secondary' => 'nullable|string|max:100',
            'hero_video_url' => 'nullable|url',
            'about_title' => 'nullable|string|max:255',
            'about_description' => 'nullable|string',
            'about_years_experience' => 'nullable|integer|min:1|max:50',
            'about_doctor_name' => 'nullable|string|max:255',
            'services_title' => 'nullable|string|max:255',
            'services_description' => 'nullable|string',
            'services' => 'nullable|array',
            'gallery_title' => 'nullable|string|max:255',
            'gallery_layout' => 'nullable|string|in:grid,masonry,carousel',
            'testimonials_title' => 'nullable|string|max:255',
            'testimonials_style' => 'nullable|string|in:cards,carousel,grid',
            'testimonials' => 'nullable|array',
            'faq_title' => 'nullable|string|max:255',
            'faq_description' => 'nullable|string|max:255',
            'faq' => 'nullable|array',
            'contact_title' => 'nullable|string|max:255',
            'contact_subtitle' => 'nullable|string',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:255',
            'contact_address' => 'nullable|string',
            'business_hours' => 'nullable|string',
            'google_maps_url' => 'nullable|url',
            'theme_primary_color' => 'nullable|string|max:7',
            'theme_secondary_color' => 'nullable|string|max:7',
            'theme_accent_color' => 'nullable|string|max:7',
            'theme_font_family' => 'nullable|string|max:50',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'google_analytics_id' => 'nullable|string|max:50',
            'facebook_pixel_id' => 'nullable|string|max:50',
            'footer_description' => 'nullable|string',
            'footer_copyright' => 'nullable|string|max:255',
            'social_facebook' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
        ]);

        // Convert arrays to JSON
        if (isset($validated['services'])) {
            $validated['services_data'] = json_encode($validated['services']);
            unset($validated['services']);
        }
        if (isset($validated['testimonials'])) {
            $validated['testimonials_data'] = json_encode($validated['testimonials']);
            unset($validated['testimonials']);
        }
        if (isset($validated['faq'])) {
            $validated['faq_data'] = json_encode($validated['faq']);
            unset($validated['faq']);
        }

        $content = LandingPageContent::updateOrCreate(
            ['clinic_id' => $clinic->id],
            array_merge($validated, ['is_active' => true])
        );

        // Handle file uploads
        if ($request->hasFile('hero_carousel_images')) {
            $heroImages = [];
            foreach ($request->file('hero_carousel_images') as $image) {
                $heroImages[] = $image->store('landing-images/clinic-' . $clinic->id);
            }
            $content->hero_carousel_images = json_encode($heroImages);
        }

        if ($request->hasFile('about_image')) {
            if ($content->about_image) {
                Storage::delete($content->about_image);
            }
            $content->about_image = $request->file('about_image')->store('landing-images/clinic-' . $clinic->id);
        }

        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('landing-images/clinic-' . $clinic->id);
            }
            $existing = $content->gallery_images ? json_decode($content->gallery_images, true) : [];
            $content->gallery_images = json_encode(array_merge($existing, $galleryImages));
        }

        $content->save();

        return redirect()->route('landing-page-manager')
            ->with('success', 'Website updated successfully!');
    }
}