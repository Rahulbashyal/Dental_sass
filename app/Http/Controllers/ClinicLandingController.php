<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ClinicLandingController extends Controller
{
    public function edit()
    {
        $clinic = Auth::user()->clinic;
        return view('clinic.landing-editor', compact('clinic'));
    }

    public function update(Request $request)
    {
        $clinic = Auth::user()->clinic;
        
        $validated = $request->validate([
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'about' => 'nullable|string',
            'theme_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
            'website_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'services' => 'nullable|array',
            'services.*.name' => 'nullable|string|max:255',
            'services.*.description' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($clinic->logo) {
                Storage::disk('public')->delete($clinic->logo);
            }
            
            $file = $request->file('logo');
            $filename = 'clinic_' . $clinic->id . '_logo.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('clinic-logos', $filename, 'public');
            $validated['logo'] = $path;
        }
        
        // Filter out empty services
        if (isset($validated['services'])) {
            $validated['services'] = array_filter($validated['services'], function($service) {
                return !empty($service['name']) || !empty($service['description']);
            });
        }

        $clinic->update($validated);

        return redirect()->back()->with('success', 'Landing page updated successfully!');
    }
}