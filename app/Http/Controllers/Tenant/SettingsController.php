<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display the clinic settings page.
     */
    public function index()
    {
        $clinic = tenant()->clinic;
        return view('tenant.settings.index', compact('clinic'));
    }

    /**
     * Update the clinic profile/general settings.
     */
    public function update(Request $request)
    {
        $clinic = tenant()->clinic;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'email' => "required|email|unique:clinics,email,{$clinic->id}",
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'timezone' => 'required|string',
            'currency' => 'required|string|max:10',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($clinic->logo) {
                Storage::disk('public')->delete($clinic->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $clinic->update($validated);

        return back()->with('status', 'Clinic settings updated successfully.');
    }

    /**
     * Update business hours.
     */
    public function updateBusinessHours(Request $request)
    {
        $clinic = tenant()->clinic;
        
        $validated = $request->validate([
            'business_hours' => 'required|array',
        ]);

        $clinic->update([
            'business_hours' => $validated['business_hours']
        ]);

        return back()->with('status', 'Business hours updated successfully.');
    }
}
