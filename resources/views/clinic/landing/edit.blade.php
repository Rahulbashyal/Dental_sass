@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Landing Page</h1>
        <p class="text-gray-600">Customize your clinic's landing page at <strong>{{ url('/' . $clinic->slug) }}</strong></p>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl p-6 mb-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $clinic->name }} Landing Page</h3>
                    <p class="text-sm text-gray-600">Your public page: <a href="{{ url('/' . $clinic->slug) }}" target="_blank" class="text-blue-600 hover:underline">{{ url('/' . $clinic->slug) }}</a></p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('clinic.landing.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-rocket text-blue-500 mr-2"></i>
                    Hero Section
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading text-blue-500 mr-2"></i>
                            Hero Title
                        </label>
                        <input type="text" name="hero_title" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Enter hero title" value="{{ $content->hero_title ?? $clinic->name . ' - Professional Dental Care' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image text-purple-500 mr-2"></i>
                            Hero Image
                        </label>
                        <input type="file" name="hero_image" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" accept="image/*">
                        @if($content->hero_image)
                            <img src="{{ $content->getImageUrl('hero_image') }}" alt="Current Hero Image" class="mt-2 h-20 w-auto rounded-lg">
                        @endif
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left text-green-500 mr-2"></i>
                        Hero Subtitle
                    </label>
                    <textarea name="hero_subtitle" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" placeholder="Enter hero subtitle">{{ $content->hero_subtitle ?? 'Experience exceptional dental care with our state-of-the-art facility and expert team. Your smile is our priority.' }}</textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-mouse-pointer text-orange-500 mr-2"></i>
                            Primary CTA Button
                        </label>
                        <input type="text" name="hero_cta_primary" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" placeholder="Button text" value="{{ $content->hero_cta_primary ?? 'Book Appointment' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-mouse-pointer text-cyan-500 mr-2"></i>
                            Secondary CTA Button
                        </label>
                        <input type="text" name="hero_cta_secondary" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" placeholder="Button text" value="{{ $content->hero_cta_secondary ?? 'Learn More' }}">
                    </div>
                </div>
            </div>
            
            <!-- Trust Indicators -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl border border-green-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-line text-green-500 mr-2"></i>
                    Trust Indicators
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Years Experience</label>
                        <input type="text" name="trust_clinics" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" value="{{ $content->trust_clinics ?? '10+' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Happy Patients</label>
                        <input type="text" name="trust_patients" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" value="{{ $content->trust_patients ?? '1000+' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Treatments</label>
                        <input type="text" name="trust_appointments" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" value="{{ $content->trust_appointments ?? '5000+' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Success Rate</label>
                        <input type="text" name="trust_uptime" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" value="{{ $content->trust_uptime ?? '98%' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <input type="text" name="trust_revenue" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" value="{{ $content->trust_revenue ?? '4.9/5' }}">
                    </div>
                </div>
            </div>
            
            <!-- About Section -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-xl border border-purple-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-purple-500 mr-2"></i>
                    About Section
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">About Title</label>
                        <input type="text" name="about_title" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" value="{{ $content->about_title ?? 'About ' . $clinic->name }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">About Image</label>
                        <input type="file" name="about_image" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" accept="image/*">
                        @if($content->about_image)
                            <img src="{{ $content->getImageUrl('about_image') }}" alt="Current About Image" class="mt-2 h-20 w-auto rounded-lg">
                        @endif
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">About Description</label>
                    <textarea name="about_description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ $content->about_description ?? 'We are committed to providing exceptional dental care in a comfortable and modern environment. Our experienced team uses the latest technology to ensure the best outcomes for our patients.' }}</textarea>
                </div>
            </div>
            
            <!-- Clinic Info -->
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-6 rounded-xl border border-yellow-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-building text-yellow-500 mr-2"></i>
                    Clinic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Clinic Name</label>
                        <input type="text" name="company_name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500" value="{{ $content->company_name ?? $clinic->name }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Clinic Tagline</label>
                        <input type="text" name="company_tagline" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500" value="{{ $content->company_tagline ?? 'Your Trusted Dental Care Partner' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <input type="text" name="company_rating" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500" value="{{ $content->company_rating ?? '4.9' }}">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Clinic Logo</label>
                    <input type="file" name="company_logo" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500" accept="image/*">
                    @if($content->company_logo)
                        <img src="{{ $content->getImageUrl('company_logo') }}" alt="Current Clinic Logo" class="mt-2 h-20 w-auto rounded-lg">
                    @endif
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Clinic Description</label>
                    <textarea name="company_description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">{{ $content->company_description ?? $clinic->name . ' is dedicated to providing comprehensive dental care with a focus on patient comfort and satisfaction. Our modern facility and experienced team ensure you receive the highest quality treatment.' }}</textarea>
                </div>
            </div>
            
            <!-- Contact & Footer -->
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-6 rounded-xl border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-envelope text-blue-500 mr-2"></i>
                    Contact & Footer
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Title</label>
                        <input type="text" name="contact_title" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ $content->contact_title ?? 'Ready to Schedule Your Visit?' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Subtitle</label>
                        <input type="text" name="contact_subtitle" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ $content->contact_subtitle ?? 'Book your appointment today and experience quality dental care' }}">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                        <input type="text" name="contact_phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ $content->contact_phone ?? $clinic->phone }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                        <input type="email" name="contact_email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ $content->contact_email ?? $clinic->email }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Address</label>
                        <input type="text" name="contact_address" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ $content->contact_address ?? $clinic->address }}">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Footer Description</label>
                    <textarea name="footer_description" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ $content->footer_description ?? $clinic->name . ' is committed to providing exceptional dental care with modern technology and personalized service.' }}</textarea>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Footer Copyright</label>
                    <input type="text" name="footer_copyright" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="{{ $content->footer_copyright ?? '© ' . date('Y') . ' ' . $clinic->name . '. All rights reserved.' }}">
                </div>
            </div>
            
            <!-- Gallery Section -->
            <div class="bg-gradient-to-r from-pink-50 to-rose-50 p-6 rounded-xl border border-pink-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-images text-pink-500 mr-2"></i>
                    Gallery Images
                </h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gallery Images</label>
                    <input type="file" name="gallery_images[]" multiple class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500" accept="image/*">
                    <p class="text-xs text-gray-500 mt-1">You can select multiple images for the gallery</p>
                    @if($content->gallery_images && count($content->gallery_images) > 0)
                        <div class="mt-4 grid grid-cols-4 gap-2">
                            @foreach($content->gallery_images as $image)
                                <img src="{{ asset('storage/landing-images/clinic-' . $clinic->id . '/' . $image) }}" alt="Gallery Image" class="h-20 w-20 object-cover rounded-lg">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Theme Colors -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-xl border border-indigo-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-palette text-indigo-500 mr-2"></i>
                    Theme Colors
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                        <input type="color" name="theme_primary_color" class="w-full h-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" value="{{ $content->theme_primary_color ?? '#3b82f6' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                        <input type="color" name="theme_secondary_color" class="w-full h-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" value="{{ $content->theme_secondary_color ?? '#06b6d4' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Accent Color</label>
                        <input type="color" name="theme_accent_color" class="w-full h-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" value="{{ $content->theme_accent_color ?? '#8b5cf6' }}">
                    </div>
                </div>
            </div>
            
            <!-- SEO Settings -->
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 p-6 rounded-xl border border-teal-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-search text-teal-500 mr-2"></i>
                    SEO Settings
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" name="meta_title" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500" value="{{ $content->meta_title ?? $clinic->name . ' - Professional Dental Care' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea name="meta_description" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ $content->meta_description ?? 'Experience exceptional dental care at ' . $clinic->name . '. Professional treatments, modern technology, and personalized service for your oral health needs.' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500" value="{{ $content->meta_keywords ?? 'dental care, dentist, ' . strtolower($clinic->name) . ', dental clinic, oral health' }}">
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ url('/' . $clinic->slug) }}" target="_blank" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Preview Page
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all font-medium shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection