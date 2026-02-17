<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\LandingPageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClinicController extends Controller
{
    public function index()
    {
        $clinics = Clinic::latest()->paginate(10);
        return view('clinics.index', compact('clinics'));
    }

    public function create()
    {
        return view('clinics.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clinics',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'required|string',
            'plan_type' => 'required|in:full_suite,landing_only',
            'theme_template' => 'required|string',
            // Configuration fields
            'enabled_modules' => 'array',
            'enabled_roles' => 'array',
            'subscription_tier' => 'string|in:basic,professional,enterprise',
            'max_users' => 'integer|min:1',
            'max_patients' => 'integer|min:1',
            'max_appointments_per_month' => 'integer|min:1',
            'business_type' => 'required|in:dental_clinic,polyclinic,individual_practice,hospital_department',
            'timezone' => 'string',
            'currency' => 'string',
            'admin_password' => 'required|string|min:8|confirmed',
        ]);

        // Handle boolean checkboxes
        $booleanFields = [
            'has_landing_page', 'has_crm', 'has_patient_portal', 'has_email_system',
            'has_notifications', 'has_analytics', 'has_accounting', 'has_inventory',
            'has_lab_integration', 'has_telemedicine', 'has_custom_branding',
            'has_api_access', 'has_priority_support'
        ];
        
        foreach ($booleanFields as $field) {
            $validated[$field] = $request->has($field);
        }

        // Set modules based on plan type
        if ($request->plan_type === 'landing_only') {
            $validated['enabled_modules'] = ['crm'];
            $validated['has_landing_page'] = true;
            $validated['has_crm'] = true;
            // Disable other features
            $validated['has_patient_portal'] = false;
            $validated['has_accounting'] = false;
            $validated['has_inventory'] = false;
        } else {
            // Set defaults for arrays if not provided
            $validated['enabled_modules'] = $validated['enabled_modules'] ?? ['appointments', 'patients', 'invoicing', 'reports'];
        }
        
        $validated['enabled_roles'] = $validated['enabled_roles'] ?? ['clinic_admin', 'dentist', 'receptionist', 'accountant'];
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        $clinic = Clinic::create($validated);

        // Create landing page content with selected theme
        $content = LandingPageContent::getDefaultContent();
        $content->clinic_id = $clinic->id;
        $content->theme_template = $request->theme_template;
        $content->save();

        // Create Clinic Admin User with provided password
        $admin = \App\Models\User::create([
            'name' => $clinic->name . ' Admin',
            'email' => $clinic->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->admin_password),
            'clinic_id' => $clinic->id,
            'is_active' => true,
            'phone' => $clinic->phone,
            'email_verified_at' => now(), // Auto-verify clinic admins
        ]);

        $admin->assignRole('clinic_admin');

        return redirect()->route('clinics.index')->with('success', 'Clinic created successfully.')
            ->with('new_clinic_info', [
                'email' => $admin->email,
                'login_url' => route('login')
            ]);
    }

    public function show(Clinic $clinic)
    {
        // Use comprehensive landing page
        $content = \App\Models\LandingPageContent::getContent($clinic->id);
        return view('clinic.comprehensive-landing', compact('content', 'clinic'));
    }

    public function landing($slug)
    {
        $clinic = Clinic::where('slug', $slug)->firstOrFail();
        return view('clinic.landing', compact('clinic'));
    }

    public function edit(Clinic $clinic)
    {
        return view('clinics.edit', compact('clinic'));
    }

    public function update(Request $request, Clinic $clinic)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clinics,email,' . $clinic->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'required|string',
            'admin_password' => 'nullable|string|min:8|confirmed',
        ]);

        $clinic->update($validated);

        // Handle Password Reset
        if ($request->filled('admin_password')) {
            $admin = \App\Models\User::where('clinic_id', $clinic->id)
                ->where('email', $clinic->email)
                ->first();

            if ($admin) {
                $admin->update([
                    'password' => \Illuminate\Support\Facades\Hash::make($request->admin_password)
                ]);
                return redirect()->route('clinics.index')->with('success', 'Clinic updated and admin password reset successfully.');
            } else {
                return redirect()->route('clinics.index')->with('warning', 'Clinic updated but admin user not found for password reset.');
            }
        }

        return redirect()->route('clinics.index')->with('success', 'Clinic updated successfully.');
    }

    public function destroy(Clinic $clinic)
    {
        $clinic->delete();
        return redirect()->route('clinics.index')->with('success', 'Clinic deleted successfully.');
    }
}