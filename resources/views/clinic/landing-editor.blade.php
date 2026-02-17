@extends('layouts.app')

@section('page-title', 'Customize Landing Page')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-slate-600">Customize your clinic's landing page at <strong>{{ url('/' . $clinic->slug) }}</strong></p>
        </div>
        <a href="{{ route('clinic.landing', $clinic->slug) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7l10 10M17 7v4"></path>
            </svg>
            Preview Landing Page
        </a>
    </div>

    <form action="{{ route('clinic.landing.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2h4a1 1 0 011 1v4a1 1 0 01-1 1H3a1 1 0 01-1-1V5a1 1 0 011-1h4z"></path>
                </svg>
                Hero Section
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Clinic Name</label>
                    <input type="text" name="name" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('name', $clinic->name) }}" placeholder="Clinic Name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tagline</label>
                    <input type="text" name="tagline" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('tagline', $clinic->tagline) }}" placeholder="Professional Dental Care">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Short Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Brief description of your clinic">{{ old('description', $clinic->description) }}</textarea>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">About Section</label>
                <textarea name="about" rows="5" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Detailed information about your clinic, services, and team">{{ old('about', $clinic->about) }}</textarea>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl border border-green-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                Contact Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Phone</label>
                    <input type="text" name="phone" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" value="{{ old('phone', $clinic->phone) }}" placeholder="Phone Number">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" value="{{ old('email', $clinic->email) }}" placeholder="Email Address">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Website URL</label>
                    <input type="url" name="website_url" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" value="{{ old('website_url', $clinic->website_url) }}" placeholder="https://yourwebsite.com">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Address</label>
                <textarea name="address" rows="2" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Full Address">{{ old('address', $clinic->address) }}</textarea>
            </div>
        </div>

        <!-- Logo & Images -->
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-xl border border-purple-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Logo & Images
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Clinic Logo</label>
                    <input type="file" name="logo" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" accept="image/*">
                    @if($clinic->logo)
                        <img src="{{ Storage::url($clinic->logo) }}" alt="Current Logo" class="mt-2 h-20 w-auto rounded-lg">
                    @endif
                    <p class="text-xs text-slate-500 mt-1">Upload JPG, PNG (max 2MB)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Hero Image</label>
                    <input type="file" name="hero_image" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" accept="image/*">
                    @if($clinic->hero_image)
                        <img src="{{ Storage::url($clinic->hero_image) }}" alt="Current Hero Image" class="mt-2 h-20 w-auto rounded-lg">
                    @endif
                    <p class="text-xs text-slate-500 mt-1">Main banner image</p>
                </div>
            </div>
        </div>

        <!-- Theme Colors -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-xl border border-indigo-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4 4 4 0 004-4V5z"></path>
                </svg>
                Theme Colors
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Primary Color</label>
                    <input type="color" id="theme_color" name="theme_color" class="w-full h-12 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500" value="{{ old('theme_color', $clinic->theme_color ?? '#2563eb') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Secondary Color</label>
                    <input type="color" id="secondary_color" name="secondary_color" class="w-full h-12 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500" value="{{ old('secondary_color', $clinic->secondary_color ?? '#1d4ed8') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Accent Color</label>
                    <input type="color" id="accent_color" name="accent_color" class="w-full h-12 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500" value="{{ old('accent_color', $clinic->accent_color ?? '#3b82f6') }}">
                </div>
            </div>
        </div>

        <!-- Services -->
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-6 rounded-xl border border-yellow-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Services Offered
            </h3>
            <div id="services-container" class="space-y-4">
                @if($clinic->services)
                    @foreach($clinic->services as $index => $service)
                    <div class="service-item bg-white p-4 rounded-lg border border-slate-200">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                            <div class="md:col-span-4">
                                <input type="text" name="services[{{ $index }}][name]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-yellow-500" value="{{ $service['name'] ?? '' }}" placeholder="Service Name">
                            </div>
                            <div class="md:col-span-6">
                                <input type="text" name="services[{{ $index }}][description]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-yellow-500" value="{{ $service['description'] ?? '' }}" placeholder="Service Description">
                            </div>
                            <div class="md:col-span-2">
                                <button type="button" class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors remove-service">Remove</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
            <button type="button" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" id="add-service">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Service
            </button>
        </div>

        <!-- Social Media -->
        <div class="bg-gradient-to-r from-pink-50 to-rose-50 p-6 rounded-xl border border-pink-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-pink-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2h4a1 1 0 011 1v4a1 1 0 01-1 1H3a1 1 0 01-1-1V5a1 1 0 011-1h4z"></path>
                </svg>
                Social Media Links
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Facebook URL</label>
                    <input type="url" name="facebook_url" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent" value="{{ old('facebook_url', $clinic->facebook_url) }}" placeholder="https://facebook.com/yourpage">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Instagram URL</label>
                    <input type="url" name="instagram_url" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent" value="{{ old('instagram_url', $clinic->instagram_url) }}" placeholder="https://instagram.com/yourpage">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Twitter URL</label>
                    <input type="url" name="twitter_url" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent" value="{{ old('twitter_url', $clinic->twitter_url) }}" placeholder="https://twitter.com/yourpage">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent" value="{{ old('linkedin_url', $clinic->linkedin_url) }}" placeholder="https://linkedin.com/company/yourpage">
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="bg-gradient-to-r from-teal-50 to-cyan-50 p-6 rounded-xl border border-teal-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                SEO Settings
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Meta Title</label>
                    <input type="text" name="meta_title" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500" value="{{ old('meta_title', $clinic->meta_title ?? $clinic->name . ' - Professional Dental Care') }}" placeholder="SEO Title">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Meta Description</label>
                    <textarea name="meta_description" rows="2" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500" placeholder="SEO Description">{{ old('meta_description', $clinic->meta_description ?? 'Experience exceptional dental care at ' . $clinic->name . '. Professional treatments and personalized service.') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500" value="{{ old('meta_keywords', $clinic->meta_keywords ?? 'dental care, dentist, ' . strtolower($clinic->name)) }}" placeholder="Keywords separated by commas">
                </div>
            </div>
        </div>

        <!-- Preview -->
        <div class="bg-gradient-to-r from-gray-50 to-slate-50 p-6 rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Live Preview
            </h3>
            <div class="preview-box rounded-lg p-6 text-center text-white" style="background: linear-gradient(135deg, {{ $clinic->theme_color ?? '#2563eb' }}, {{ $clinic->secondary_color ?? '#1d4ed8' }});">
                <h4 class="text-xl font-bold mb-2">{{ $clinic->name }}</h4>
                <p class="mb-0">{{ $clinic->tagline ?? 'Professional Dental Care' }}</p>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-slate-200">
            <a href="{{ route('dashboard') }}" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-all font-medium">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all font-medium shadow-lg">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h2m0 0h2m0 0h2m0 0h2M9 7h6m0 0v2m0 0v2m0 0v2M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2"></path>
                </svg>
                Save Landing Page
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let serviceIndex = {{ $clinic->services ? count($clinic->services) : 0 }};
    
    // Add service
    document.getElementById('add-service').addEventListener('click', function() {
        const container = document.getElementById('services-container');
        const serviceHtml = `
            <div class="service-item bg-white p-4 rounded-lg border border-slate-200">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                    <div class="md:col-span-4">
                        <input type="text" name="services[${serviceIndex}][name]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-yellow-500" placeholder="Service Name">
                    </div>
                    <div class="md:col-span-6">
                        <input type="text" name="services[${serviceIndex}][description]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-yellow-500" placeholder="Service Description">
                    </div>
                    <div class="md:col-span-2">
                        <button type="button" class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors remove-service">Remove</button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', serviceHtml);
        serviceIndex++;
    });
    
    // Remove service
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-service')) {
            e.target.closest('.service-item').remove();
        }
    });
    
    // Color preview update
    ['theme_color', 'secondary_color', 'accent_color'].forEach(colorId => {
        const element = document.getElementById(colorId);
        if (element) {
            element.addEventListener('change', function() {
                const preview = document.querySelector('.preview-box');
                const themeColor = document.getElementById('theme_color').value;
                const secondaryColor = document.getElementById('secondary_color').value;
                if (preview) {
                    preview.style.background = `linear-gradient(135deg, ${themeColor}, ${secondaryColor})`;
                }
            });
        }
    });
});
</script>
@endsection