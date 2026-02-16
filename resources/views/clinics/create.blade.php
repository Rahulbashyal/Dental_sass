@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="p-2.5 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg shadow-blue-500/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Create New Clinic</h1>
                <p class="text-slate-500 text-sm mt-0.5">Configure all aspects of the new clinic including modules, features, and subscription settings</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('clinics.store') }}" class="space-y-8">
        @csrf
        
        <!-- Plan & Theme Configuration -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-white px-6 py-4 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </span>
                    Plan & Theme
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <label class="form-label mb-3">Plan Type <span class="text-red-500">*</span></label>
                        <div class="space-y-3">
                            <label class="flex items-center gap-4 p-4 border-2 border-slate-200 rounded-xl hover:border-indigo-300 hover:bg-indigo-50/30 cursor-pointer transition-all">
                                <input type="radio" name="plan_type" value="full_suite" checked onchange="toggleModules(this.value)" class="w-5 h-5 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                <div>
                                    <span class="block text-sm font-semibold text-slate-900">Full Suite</span>
                                    <span class="block text-xs text-slate-500">All features (Appointments, Billing, etc.)</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-4 p-4 border-2 border-slate-200 rounded-xl hover:border-indigo-300 hover:bg-indigo-50/30 cursor-pointer transition-all">
                                <input type="radio" name="plan_type" value="landing_only" onchange="toggleModules(this.value)" class="w-5 h-5 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                                <div>
                                    <span class="block text-sm font-semibold text-slate-900">Landing Page Only</span>
                                    <span class="block text-xs text-slate-500">Only Landing Page & CRM (Leads)</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label for="theme_template" class="form-label">Landing Page Theme <span class="text-red-500">*</span></label>
                        <select name="theme_template" id="theme_template" class="form-input" required>
                            <option value="default">Default (Clean White)</option>
                            <option value="modern_blue">Modern Blue</option>
                            <option value="dark_premium">Dark Premium</option>
                            <option value="medical_classic">Medical Classic</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-2">Select the initial visual theme for the clinic.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-white px-6 py-4 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </span>
                    Basic Information
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="name" class="form-label">Clinic Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" placeholder="Enter clinic name" required>
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" placeholder="clinic@example.com" required>
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-input" value="{{ old('phone') }}" placeholder="+977-98XXXXXXXX">
                    </div>
                    <div>
                        <label for="city" class="form-label">City</label>
                        <input type="text" name="city" id="city" class="form-input" value="{{ old('city') }}" placeholder="Kathmandu">
                        @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="state" class="form-label">Province/State</label>
                        <input type="text" name="state" id="state" class="form-input" value="{{ old('state') }}" placeholder="Bagmati">
                        @error('state')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="country" class="form-label">Country <span class="text-red-500">*</span></label>
                        <select name="country" id="country" class="form-input" required>
                            <option value="Nepal" {{ old('country') == 'Nepal' ? 'selected' : '' }}>Nepal</option>
                            <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                            <option value="USA" {{ old('country') == 'USA' ? 'selected' : '' }}>USA</option>
                            <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('country')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" name="postal_code" id="postal_code" class="form-input" value="{{ old('postal_code') }}" placeholder="44600">
                        @error('postal_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="business_type" class="form-label">Practice Category <span class="text-red-500">*</span></label>
                        <select name="business_type" id="business_type" class="form-input" required>
                            <option value="dental_clinic" {{ old('business_type') == 'dental_clinic' ? 'selected' : '' }}>Dental Clinic</option>
                            <option value="polyclinic" {{ old('business_type') == 'polyclinic' ? 'selected' : '' }}>Polyclinic</option>
                            <option value="individual_practice" {{ old('business_type') == 'individual_practice' ? 'selected' : '' }}>Individual Practice</option>
                            <option value="hospital_department" {{ old('business_type') == 'hospital_department' ? 'selected' : '' }}>Hospital Department</option>
                        </select>
                        @error('business_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="mt-6">
                    <label for="address" class="form-label">Full Address</label>
                    <input type="text" name="address" id="address" class="form-input" value="{{ old('address') }}" placeholder="Street Name, Area">
                    @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Clinic Admin Credentials -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-white px-6 py-4 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </span>
                    Clinic Admin Credentials
                </h2>
            </div>
            <div class="p-6">
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-blue-800">
                                The clinic admin will use the <strong>clinic email</strong> and this password to login.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="admin_password" class="form-label">Admin Password <span class="text-red-500">*</span></label>
                        <input type="password" name="admin_password" id="admin_password" class="form-input" placeholder="Enter secure password" required autocomplete="new-password">
                        @error('admin_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="admin_password_confirmation" class="form-label">Confirm Password <span class="text-red-500">*</span></label>
                        <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" class="form-input" placeholder="Confirm password" required autocomplete="new-password">
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription Configuration -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-white px-6 py-4 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    Subscription & Limits
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="subscription_tier" class="form-label">Subscription Tier</label>
                        <select name="subscription_tier" id="subscription_tier" class="form-input">
                            <option value="basic">Basic</option>
                            <option value="professional">Professional</option>
                            <option value="enterprise">Enterprise</option>
                        </select>
                    </div>
                    <div>
                        <label for="max_users" class="form-label">Max Users</label>
                        <input type="number" name="max_users" id="max_users" class="form-input" value="5" min="1">
                    </div>
                    <div>
                        <label for="max_patients" class="form-label">Max Patients</label>
                        <input type="number" name="max_patients" id="max_patients" class="form-input" value="1000" min="1">
                    </div>
                    <div>
                        <label for="max_appointments_per_month" class="form-label">Max Appointments/Month</label>
                        <input type="number" name="max_appointments_per_month" id="max_appointments_per_month" class="form-input" value="500" min="1">
                    </div>
                </div>
            </div>
        </div>

        <!-- Core Modules -->
        <div id="core-modules-section" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-white px-6 py-4 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </span>
                    Core Modules
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach(['appointments' => 'Appointments', 'patients' => 'Patient Management', 'invoicing' => 'Invoicing & Billing', 'reports' => 'Reports & Analytics'] as $module => $label)
                    <label class="flex items-center gap-3 p-4 border-2 border-slate-200 rounded-xl hover:border-purple-300 hover:bg-purple-50/30 cursor-pointer transition-all has-[:checked]:border-purple-500 has-[:checked]:bg-purple-50/50">
                        <input type="checkbox" name="enabled_modules[]" value="{{ $module }}" checked class="w-5 h-5 rounded border-slate-300 text-purple-600 focus:ring-purple-500 cursor-pointer">
                        <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- User Roles -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-white px-6 py-4 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </span>
                    Enabled User Roles
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach(['clinic_admin' => 'Clinic Admin', 'dentist' => 'Dentist', 'receptionist' => 'Receptionist', 'accountant' => 'Accountant'] as $role => $label)
                    <label class="flex items-center gap-3 p-4 border-2 border-slate-200 rounded-xl hover:border-indigo-300 hover:bg-indigo-50/30 cursor-pointer transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50/50">
                        <input type="checkbox" name="enabled_roles[]" value="{{ $role }}" checked class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Features & Add-ons -->
        <div id="features-section" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-white px-6 py-4 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </span>
                    Features & Add-ons
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    @foreach([
                        'has_landing_page' => 'Landing Page',
                        'has_crm' => 'CRM System',
                        'has_patient_portal' => 'Patient Portal',
                        'has_email_system' => 'Email System',
                        'has_notifications' => 'Notifications',
                        'has_analytics' => 'Analytics',
                        'has_accounting' => 'Accounting',
                        'has_inventory' => 'Inventory',
                        'has_lab_integration' => 'Lab Integration',
                        'has_telemedicine' => 'Telemedicine'
                    ] as $feature => $label)
                    <label class="flex items-center gap-2 p-3 border-2 border-slate-200 rounded-xl hover:border-yellow-300 hover:bg-yellow-50/30 cursor-pointer transition-all has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50/50">
                        <input type="checkbox" name="{{ $feature }}" value="1" class="w-4 h-4 rounded border-slate-300 text-yellow-600 focus:ring-yellow-500 cursor-pointer" {{ in_array($feature, ['has_landing_page', 'has_patient_portal', 'has_email_system', 'has_notifications', 'has_analytics', 'has_accounting']) ? 'checked' : '' }}>
                        <span class="text-xs font-medium text-slate-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Premium Features -->
        <div id="premium-section" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-white px-6 py-4 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 bg-gradient-to-br from-orange-100 to-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </span>
                    Premium Features
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach(['has_custom_branding' => 'Custom Branding', 'has_api_access' => 'API Access', 'has_priority_support' => 'Priority Support'] as $feature => $label)
                    <label class="flex items-center gap-3 p-4 border-2 border-slate-200 rounded-xl hover:border-orange-300 hover:bg-orange-50/30 cursor-pointer transition-all has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/50">
                        <input type="checkbox" name="{{ $feature }}" value="1" class="w-5 h-5 rounded border-slate-300 text-orange-600 focus:ring-orange-500 cursor-pointer">
                        <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Business Configuration -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-white px-6 py-4 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 bg-rose-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V8m8 0V6a2 2 0 00-2-2H10a2 2 0 00-2 2v2"></path>
                        </svg>
                    </span>
                    Business Configuration
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="business_type_detail" class="form-label">Business Type Detail</label>
                        <select name="business_type_detail" id="business_type_detail" class="form-input">
                            <option value="">Select type...</option>
                            <option value="dental_clinic">General Dental Clinic</option>
                            <option value="orthodontic">Orthodontic Practice</option>
                            <option value="oral_surgery">Oral Surgery</option>
                            <option value="pediatric_dentistry">Pediatric Dentistry</option>
                            <option value="cosmetic_dentistry">Cosmetic Dentistry</option>
                        </select>
                    </div>
                    <div>
                        <label for="timezone" class="form-label">Timezone</label>
                        <select name="timezone" id="timezone" class="form-input">
                            <option value="Asia/Kathmandu">Asia/Kathmandu (Nepal)</option>
                            <option value="Asia/Kolkata">Asia/Kolkata (India)</option>
                            <option value="UTC">UTC</option>
                        </select>
                    </div>
                    <div>
                        <label for="currency" class="form-label">Currency</label>
                        <select name="currency" id="currency" class="form-input">
                            <option value="NPR">NPR (Nepalese Rupee)</option>
                            <option value="INR">INR (Indian Rupee)</option>
                            <option value="USD">USD (US Dollar)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-4 pb-8">
            <a href="{{ route('clinics.index') }}" class="px-6 py-2.5 border border-slate-300 rounded-xl text-slate-700 font-medium hover:bg-slate-50 hover:border-slate-400 transition-all">Cancel</a>
            <button type="submit" class="px-8 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 hover:-translate-y-0.5 transition-all">Create Clinic</button>
        </div>
    </form>
</div>

<style>
.form-input {
    display: block;
    width: 100%;
    border-radius: 0.75rem;
    border: 2px solid #e2e8f0;
    background-color: #fff;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    color: #0f172a;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    transition: all 0.2s;
}

.form-input:hover {
    border-color: #cbd5e1;
    background-color: #f8fafc;
}

.form-input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
}

.form-input::placeholder {
    color: #94a3b8;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 0.5rem;
}
</style>

<script>
// Auto-adjust limits based on subscription tier
document.getElementById('subscription_tier').addEventListener('change', function() {
    const tier = this.value;
    const limits = {
        basic: { users: 5, patients: 1000, appointments: 500 },
        professional: { users: 15, patients: 5000, appointments: 2000 },
        enterprise: { users: 50, patients: 20000, appointments: 10000 }
    };
    
    if (limits[tier]) {
        document.getElementById('max_users').value = limits[tier].users;
        document.getElementById('max_patients').value = limits[tier].patients;
        document.getElementById('max_appointments_per_month').value = limits[tier].appointments;
    }
});

function toggleModules(planType) {
    const coreModules = document.getElementById('core-modules-section');
    const features = document.getElementById('features-section');
    const premium = document.getElementById('premium-section');
    
    if (planType === 'landing_only') {
        coreModules.style.display = 'none';
        features.style.display = 'none';
        premium.style.display = 'none';
    } else {
        coreModules.style.display = 'block';
        features.style.display = 'block';
        premium.style.display = 'block';
    }
}
</script>
@endsection
