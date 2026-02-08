@extends('layouts.app')

@section('content')
<style>
    .website-builder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .builder-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    
    .builder-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    .builder-content {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 2rem;
    }
    
    .main-content {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    .sidebar-settings {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .section-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .section-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .section-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: between;
        align-items: center;
        cursor: pointer;
    }
    
    .section-title {
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-collapse {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    
    .section-collapse.open {
        max-height: 2000px;
    }
    
    .section-content {
        padding: 1.5rem;
    }
    
    .control-group {
        margin-bottom: 1.5rem;
    }
    
    .control-label {
        display: block;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #374151;
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        background-color: white;
        transition: all 0.2s ease;
    }
    
    .btn-add {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-add:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .list-item {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        position: relative;
        transition: all 0.2s ease;
    }
    
    .list-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .remove-btn {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ef4444;
        color: white;
        border: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    .remove-btn:hover {
        background: #dc2626;
        transform: scale(1.1);
    }
    
    .image-upload-zone {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
        background: #f9fafb;
    }
    
    .image-upload-zone:hover {
        border-color: #9ca3af;
        background: #f3f4f6;
    }
    
    .upload-text {
        color: #6b7280;
        font-weight: 500;
        margin-top: 0.5rem;
    }
    
    .color-picker-group {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .color-preview {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
    }
    
    .section-toggle {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .section-toggle.disabled {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    
    .collapse-toggle {
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    
    .collapse-toggle:hover {
        background: #f3f4f6;
        color: #374151;
    }
    
    .floating-actions {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        z-index: 1000;
    }
    
    .floating-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
    
    .floating-btn.preview {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }
    
    .floating-btn.save {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    
    .floating-btn:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    }
    
    .hero-section { border-left: 4px solid #3b82f6; }
    .about-section { border-left: 4px solid #10b981; }
    .services-section { border-left: 4px solid #f59e0b; }
    .gallery-section { border-left: 4px solid #ec4899; }
    .testimonials-section { border-left: 4px solid #8b5cf6; }
    .faq-section { border-left: 4px solid #06b6d4; }
    
    @media (max-width: 1024px) {
        .builder-content {
            grid-template-columns: 1fr;
        }
        
        .sidebar-settings {
            order: -1;
        }
    }
</style>

<div class="website-builder">
    <div class="builder-container">
        <!-- Header -->
        <div class="builder-header">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-globe text-blue-500 mr-3"></i>
                        DentalCare Pro Platform Builder
                    </h1>
                    <p class="text-gray-600">Manage the main DentalCare Pro marketing website (/) - not individual clinic pages</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">
                        <i class="fas fa-eye mr-2"></i>Preview
                    </button>
                    <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                        <i class="fas fa-save mr-2"></i>Save & Publish
                    </button>
                </div>
            </div>
        </div>

        <form id="website-form" action="{{ route('superadmin.content.landing.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="builder-content">
                <!-- Main Content Area -->
                <div class="main-content">
                    <div class="space-y-6">
                        
                        <!-- Hero Section -->
                        <div class="section-card hero-section">
                            <div class="section-header" onclick="toggleSection(this)">
                                <div class="section-title">
                                    <i class="fas fa-rocket text-blue-500"></i>
                                    Hero Section
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" class="section-toggle">
                                        <i class="fas fa-check-circle"></i> Enabled
                                    </button>
                                    <button type="button" class="collapse-toggle">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="section-collapse open">
                                <div class="section-content">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="control-group">
                                            <label class="control-label">Hero Title</label>
                                            <input type="text" class="form-control" name="hero_title" 
                                                   value="{{ $content->hero_title ?? "Nepal's Most Advanced Dental Platform" }}">
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Hero Subtitle</label>
                                            <textarea class="form-control" name="hero_subtitle" rows="3">{{ $content->hero_subtitle ?? 'Transform your dental clinic with our comprehensive management solution. Built by ABS Soft specifically for Nepal\'s healthcare industry.' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="control-group">
                                            <label class="control-label">Primary CTA Button</label>
                                            <input type="text" class="form-control" name="hero_cta_primary" 
                                                   value="{{ $content->hero_cta_primary ?? 'Start 14-Day Free Trial' }}">
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Secondary CTA Button</label>
                                            <input type="text" class="form-control" name="hero_cta_secondary" 
                                                   value="{{ $content->hero_cta_secondary ?? 'Watch Demo' }}">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Hero Carousel Images</label>
                                        <div class="image-upload-zone">
                                            <input type="file" name="hero_carousel_images[]" multiple accept="image/*" style="display: none;" id="hero-images">
                                            <label for="hero-images" style="cursor: pointer;">
                                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                                <div class="upload-text">Click to upload hero carousel images</div>
                                                <small class="text-gray-500">Multiple images supported</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Trust Indicators Section -->
                        <div class="section-card trust-section" style="border-left: 4px solid #10b981;">
                            <div class="section-header" onclick="toggleSection(this)">
                                <div class="section-title">
                                    <i class="fas fa-chart-line text-green-500"></i>
                                    Trust Indicators
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" class="section-toggle">
                                        <i class="fas fa-check-circle"></i> Enabled
                                    </button>
                                    <button type="button" class="collapse-toggle">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="section-collapse">
                                <div class="section-content">
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                        <div class="control-group">
                                            <label class="control-label">Clinics</label>
                                            <input type="text" class="form-control" name="trust_clinics" 
                                                   value="{{ $content->trust_clinics ?? '500+' }}">
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Patients</label>
                                            <input type="text" class="form-control" name="trust_patients" 
                                                   value="{{ $content->trust_patients ?? '50K+' }}">
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Appointments</label>
                                            <input type="text" class="form-control" name="trust_appointments" 
                                                   value="{{ $content->trust_appointments ?? '2M+' }}">
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Uptime</label>
                                            <input type="text" class="form-control" name="trust_uptime" 
                                                   value="{{ $content->trust_uptime ?? '99.9%' }}">
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Revenue</label>
                                            <input type="text" class="form-control" name="trust_revenue" 
                                                   value="{{ $content->trust_revenue ?? 'NPR 50Cr+' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Information Section -->
                        <div class="section-card company-section" style="border-left: 4px solid #f59e0b;">
                            <div class="section-header" onclick="toggleSection(this)">
                                <div class="section-title">
                                    <i class="fas fa-building text-yellow-500"></i>
                                    Company Information
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" class="section-toggle">
                                        <i class="fas fa-check-circle"></i> Enabled
                                    </button>
                                    <button type="button" class="collapse-toggle">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="section-collapse">
                                <div class="section-content">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="control-group">
                                            <label class="control-label">Company Name</label>
                                            <input type="text" class="form-control" name="company_name" 
                                                   value="{{ $content->company_name ?? 'ABS Soft' }}">
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Company Tagline</label>
                                            <input type="text" class="form-control" name="company_tagline" 
                                                   value="{{ $content->company_tagline ?? 'Leading Software Solutions Provider' }}">
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Company Rating</label>
                                            <input type="text" class="form-control" name="company_rating" 
                                                   value="{{ $content->company_rating ?? '4.9' }}">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Company Logo</label>
                                        <div class="image-upload-zone">
                                            <input type="file" name="company_logo" accept="image/*" style="display: none;" id="company-logo">
                                            <label for="company-logo" style="cursor: pointer;">
                                                <i class="fas fa-building text-3xl text-gray-400 mb-2"></i>
                                                <div class="upload-text">Click to upload company logo</div>
                                            </label>
                                        </div>
                                        @if($content->company_logo)
                                            <img src="{{ $content->getImageUrl('company_logo') }}" alt="Current Company Logo" class="mt-2 h-20 w-auto rounded-lg">
                                        @endif
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Company Description</label>
                                        <textarea class="form-control" name="company_description" rows="3">{{ $content->company_description ?? 'DentalCare Pro is proudly developed by ABS Soft, Nepal\'s premier software development company. With years of experience in healthcare technology, we deliver cutting-edge solutions tailored for Nepal\'s unique market needs.' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- About Section -->
                        <div class="section-card about-section">
                            <div class="section-header" onclick="toggleSection(this)">
                                <div class="section-title">
                                    <i class="fas fa-info-circle text-green-500"></i>
                                    About Section
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" class="section-toggle">
                                        <i class="fas fa-check-circle"></i> Enabled
                                    </button>
                                    <button type="button" class="collapse-toggle">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="section-collapse">
                                <div class="section-content">
                                    <div class="control-group">
                                        <label class="control-label">About Title</label>
                                        <input type="text" class="form-control" name="about_title" 
                                               value="{{ $content->about_title ?? 'Designed for Nepal\'s Healthcare Excellence' }}">
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">About Description</label>
                                        <textarea class="form-control" name="about_description" rows="4">{{ $content->about_description ?? 'Understanding Nepal\'s unique healthcare challenges, we\'ve built a comprehensive platform with local language support, NPR billing, and full regulatory compliance.' }}</textarea>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">About Image</label>
                                        <div class="image-upload-zone">
                                            <input type="file" name="about_image" accept="image/*" style="display: none;" id="about-image">
                                            <label for="about-image" style="cursor: pointer;">
                                                <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                                                <div class="upload-text">Click to upload about image</div>
                                            </label>
                                        </div>
                                        @if($content->about_image)
                                            <img src="{{ $content->getImageUrl('about_image') }}" alt="Current About Image" class="mt-2 h-20 w-auto rounded-lg">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Trusted Partners Section -->
                        <div class="section-card partners-section" style="border-left: 4px solid #10b981;">
                            <div class="section-header" onclick="toggleSection(this)">
                                <div class="section-title">
                                    <i class="fas fa-handshake text-green-500"></i>
                                    Trusted Partners
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" class="section-toggle">
                                        <i class="fas fa-check-circle"></i> Enabled
                                    </button>
                                    <button type="button" class="collapse-toggle">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="section-collapse">
                                <div class="section-content">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="font-semibold text-gray-700">Manage Trusted Partners</h4>
                                        <button type="button" class="btn-add" onclick="addPartner()">
                                            <i class="fas fa-plus"></i> Add Partner
                                        </button>
                                    </div>
                                    <div id="partners-list">
                                        @if($content->trusted_partners && count($content->trusted_partners) > 0)
                                            @foreach($content->trusted_partners as $index => $partner)
                                            <div class="list-item">
                                                <button type="button" class="remove-btn" onclick="removeItem(this)">×</button>
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <input type="text" class="form-control" name="partners[{{ $index }}][name]" 
                                                               placeholder="Partner Name" value="{{ $partner['name'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <input type="file" class="form-control" name="partners[{{ $index }}][logo]" accept="image/*">
                                                        <small class="text-gray-500">Partner logo</small>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="list-item">
                                                <button type="button" class="remove-btn" onclick="removeItem(this)">×</button>
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <input type="text" class="form-control" name="partners[0][name]" 
                                                               placeholder="Partner Name" value="Dental Care Plus">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <input type="file" class="form-control" name="partners[0][logo]" accept="image/*">
                                                        <small class="text-gray-500">Partner logo</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Services Section -->
                        <div class="section-card services-section">
                            <div class="section-header" onclick="toggleSection(this)">
                                <div class="section-title">
                                    <i class="fas fa-cogs text-orange-500"></i>
                                    Platform Features
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" class="section-toggle">
                                        <i class="fas fa-check-circle"></i> Enabled
                                    </button>
                                    <button type="button" class="collapse-toggle">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="section-collapse">
                                <div class="section-content">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="font-semibold text-gray-700">Manage Platform Features</h4>
                                        <button type="button" class="btn-add" onclick="addService()">
                                            <i class="fas fa-plus"></i> Add Feature
                                        </button>
                                    </div>
                                    <div id="services-list">
                                        @if($content->services_data && count($content->services_data) > 0)
                                            @foreach($content->services_data as $index => $service)
                                            <div class="list-item">
                                                <button type="button" class="remove-btn" onclick="removeItem(this)">×</button>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <input type="text" class="form-control" name="services[{{ $index }}][title]" 
                                                               placeholder="Service Name" value="{{ $service['title'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <input type="text" class="form-control" name="services[{{ $index }}][description]" 
                                                               placeholder="Service Description" value="{{ $service['description'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-2 mb-2">
                                                        <input type="text" class="form-control" name="services[{{ $index }}][icon]" 
                                                               placeholder="Icon Class" value="{{ $service['icon'] ?? 'fas fa-tooth' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="list-item">
                                                <button type="button" class="remove-btn" onclick="removeItem(this)">×</button>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <input type="text" class="form-control" name="services[0][title]" 
                                                               placeholder="Feature Name" value="Smart Appointments">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <input type="text" class="form-control" name="services[0][description]" 
                                                               placeholder="Feature Description" value="Online booking with Nepali calendar integration and automated SMS reminders">
                                                    </div>
                                                    <div class="col-md-2 mb-2">
                                                        <input type="text" class="form-control" name="services[0][icon]" 
                                                               placeholder="Icon Class" value="fas fa-calendar-alt">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Subscription Plans Section -->
                        <div class="section-card pricing-section" style="border-left: 4px solid #8b5cf6;">
                            <div class="section-header" onclick="toggleSection(this)">
                                <div class="section-title">
                                    <i class="fas fa-credit-card text-purple-500"></i>
                                    Subscription Plans
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" class="section-toggle">
                                        <i class="fas fa-check-circle"></i> Enabled
                                    </button>
                                    <button type="button" class="collapse-toggle">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="section-collapse">
                                <div class="section-content">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="control-group">
                                            <label class="control-label">Basic Plan Price</label>
                                            <input type="text" class="form-control" name="basic_plan_price" 
                                                   value="{{ $content->basic_plan_price ?? 'NPR 3,500' }}">
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Professional Plan Price</label>
                                            <input type="text" class="form-control" name="professional_plan_price" 
                                                   value="{{ $content->professional_plan_price ?? 'NPR 7,000' }}">
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Enterprise Plan Price</label>
                                            <input type="text" class="form-control" name="enterprise_plan_price" 
                                                   value="{{ $content->enterprise_plan_price ?? 'NPR 12,000' }}">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="control-group">
                                            <label class="control-label">Trial Period</label>
                                            <input type="text" class="form-control" name="trial_period" 
                                                   value="{{ $content->trial_period ?? '14-day free trial' }}">
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Support Availability</label>
                                            <input type="text" class="form-control" name="support_availability" 
                                                   value="{{ $content->support_availability ?? '24/7 Support' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Testimonials Section -->
                        <div class="section-card testimonials-section">
                            <div class="section-header" onclick="toggleSection(this)">
                                <div class="section-title">
                                    <i class="fas fa-quote-left text-purple-500"></i>
                                    Testimonials Section
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" class="section-toggle">
                                        <i class="fas fa-check-circle"></i> Enabled
                                    </button>
                                    <button type="button" class="collapse-toggle">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="section-collapse">
                                <div class="section-content">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="font-semibold text-gray-700">Manage Testimonials</h4>
                                        <button type="button" class="btn-add" onclick="addTestimonial()">
                                            <i class="fas fa-plus"></i> Add Testimonial
                                        </button>
                                    </div>
                                    <div id="testimonials-list">
                                        @if($content->testimonials_data && count($content->testimonials_data) > 0)
                                            @foreach($content->testimonials_data as $index => $testimonial)
                                            <div class="list-item">
                                                <button type="button" class="remove-btn" onclick="removeItem(this)">×</button>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <input type="text" class="form-control" name="testimonials[{{ $index }}][name]" 
                                                               placeholder="Client Name" value="{{ $testimonial['name'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <select class="form-select" name="testimonials[{{ $index }}][rating]">
                                                            <option value="5" {{ ($testimonial['rating'] ?? 5) == 5 ? 'selected' : '' }}>5 Stars</option>
                                                            <option value="4" {{ ($testimonial['rating'] ?? 5) == 4 ? 'selected' : '' }}>4 Stars</option>
                                                            <option value="3" {{ ($testimonial['rating'] ?? 5) == 3 ? 'selected' : '' }}>3 Stars</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <input type="text" class="form-control" name="testimonials[{{ $index }}][designation]" 
                                                               placeholder="Designation" value="{{ $testimonial['designation'] ?? 'Clinic Owner' }}">
                                                    </div>
                                                    <div class="col-12 mb-2">
                                                        <textarea class="form-control" name="testimonials[{{ $index }}][review]" rows="2" 
                                                                  placeholder="Testimonial text">{{ $testimonial['review'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="list-item">
                                                <button type="button" class="remove-btn" onclick="removeItem(this)">×</button>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <input type="text" class="form-control" name="testimonials[0][name]" 
                                                               placeholder="Client Name" value="Dr. Rajesh Shrestha">
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <select class="form-select" name="testimonials[0][rating]">
                                                            <option value="5" selected>5 Stars</option>
                                                            <option value="4">4 Stars</option>
                                                            <option value="3">3 Stars</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <input type="text" class="form-control" name="testimonials[0][designation]" 
                                                               placeholder="Designation" value="Smile Dental Clinic, Kathmandu">
                                                    </div>
                                                    <div class="col-12 mb-2">
                                                        <textarea class="form-control" name="testimonials[0][review]" rows="2" 
                                                                  placeholder="Testimonial text">This platform has completely transformed how we manage our dental clinic. The Nepali calendar integration and SMS notifications have made patient management so much easier.</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>

                <!-- Sidebar Settings -->
                <div class="sidebar-settings">
                    
                    <!-- Contact Settings -->
                    <div class="section-card">
                        <div class="section-header" onclick="toggleSection(this)">
                            <div class="section-title">
                                <i class="fas fa-envelope text-blue-500"></i>
                                Contact
                            </div>
                            <button type="button" class="collapse-toggle">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div class="section-collapse">
                            <div class="section-content">
                                <div class="control-group">
                                    <label class="control-label">Contact Title</label>
                                    <input type="text" class="form-control" name="contact_title" 
                                           value="{{ $content->contact_title ?? 'Ready to Transform Your Practice?' }}">
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Contact Subtitle</label>
                                    <input type="text" class="form-control" name="contact_subtitle" 
                                           value="{{ $content->contact_subtitle ?? 'Join hundreds of dental clinics across Nepal' }}">
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Phone</label>
                                    <input type="text" class="form-control" name="contact_phone" 
                                           value="{{ $content->contact_phone ?? '' }}">
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Email</label>
                                    <input type="email" class="form-control" name="contact_email" 
                                           value="{{ $content->contact_email ?? '' }}">
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Address</label>
                                    <input type="text" class="form-control" name="contact_address" 
                                           value="{{ $content->contact_address ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Theme Settings -->
                    <div class="section-card">
                        <div class="section-header" onclick="toggleSection(this)">
                            <div class="section-title">
                                <i class="fas fa-palette text-purple-500"></i>
                                Theme
                            </div>
                            <button type="button" class="collapse-toggle">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div class="section-collapse">
                            <div class="section-content">
                                <div class="color-picker-group">
                                    <div class="color-preview" style="background: {{ $content->theme_primary_color ?? '#3b82f6' }};"></div>
                                    <div>
                                        <label class="control-label">Primary Color</label>
                                        <input type="color" name="theme_primary_color" value="{{ $content->theme_primary_color ?? '#3b82f6' }}">
                                        <input type="text" class="form-control mt-1" value="{{ $content->theme_primary_color ?? '#3b82f6' }}" readonly>
                                    </div>
                                </div>
                                <div class="color-picker-group">
                                    <div class="color-preview" style="background: {{ $content->theme_secondary_color ?? '#06b6d4' }};"></div>
                                    <div>
                                        <label class="control-label">Secondary Color</label>
                                        <input type="color" name="theme_secondary_color" value="{{ $content->theme_secondary_color ?? '#06b6d4' }}">
                                        <input type="text" class="form-control mt-1" value="{{ $content->theme_secondary_color ?? '#06b6d4' }}" readonly>
                                    </div>
                                </div>
                                <div class="color-picker-group">
                                    <div class="color-preview" style="background: {{ $content->theme_accent_color ?? '#8b5cf6' }};"></div>
                                    <div>
                                        <label class="control-label">Accent Color</label>
                                        <input type="color" name="theme_accent_color" value="{{ $content->theme_accent_color ?? '#8b5cf6' }}">
                                        <input type="text" class="form-control mt-1" value="{{ $content->theme_accent_color ?? '#8b5cf6' }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings -->
                    <div class="section-card">
                        <div class="section-header" onclick="toggleSection(this)">
                            <div class="section-title">
                                <i class="fas fa-search text-green-500"></i>
                                SEO
                            </div>
                            <button type="button" class="collapse-toggle">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div class="section-collapse">
                            <div class="section-content">
                                <div class="control-group">
                                    <label class="control-label">Meta Title</label>
                                    <input type="text" class="form-control" name="meta_title" 
                                           value="{{ $content->meta_title ?? '' }}">
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Meta Description</label>
                                    <textarea class="form-control" name="meta_description" rows="3">{{ $content->meta_description ?? '' }}</textarea>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_keywords" 
                                           value="{{ $content->meta_keywords ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Settings -->
                    <div class="section-card">
                        <div class="section-header" onclick="toggleSection(this)">
                            <div class="section-title">
                                <i class="fas fa-copyright text-gray-500"></i>
                                Footer
                            </div>
                            <button type="button" class="collapse-toggle">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div class="section-collapse">
                            <div class="section-content">
                                <div class="control-group">
                                    <label class="control-label">Footer Description</label>
                                    <textarea class="form-control" name="footer_description" rows="3">{{ $content->footer_description ?? 'DentalCare Pro is proudly developed by ABS Soft, Nepal\'s leading software development company.' }}</textarea>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Copyright Text</label>
                                    <input type="text" class="form-control" name="footer_copyright" 
                                           value="{{ $content->footer_copyright ?? '© 2024 ABS Soft. All rights reserved. DentalCare Pro - Made with ❤️ in Nepal 🇳🇵' }}">
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Social Media Links</label>
                                    <input type="url" class="form-control mb-2" name="social_facebook" 
                                           value="{{ $content->social_facebook ?? '' }}" placeholder="Facebook URL">
                                    <input type="url" class="form-control mb-2" name="social_instagram" 
                                           value="{{ $content->social_instagram ?? '' }}" placeholder="Instagram URL">
                                    <input type="url" class="form-control mb-2" name="social_twitter" 
                                           value="{{ $content->social_twitter ?? '' }}" placeholder="Twitter URL">
                                    <input type="url" class="form-control" name="social_linkedin" 
                                           value="{{ $content->social_linkedin ?? '' }}" placeholder="LinkedIn URL">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<!-- Floating Action Buttons -->
<div class="floating-actions">
    <button type="button" class="floating-btn preview" onclick="previewWebsite()" title="Preview Website">
        <i class="fas fa-eye"></i>
    </button>
    <button type="button" class="floating-btn save" onclick="saveWebsite()" title="Save & Publish">
        <i class="fas fa-save"></i>
    </button>
</div>

<script>
    // Color picker functionality
    document.querySelectorAll('input[type="color"]').forEach(colorInput => {
        colorInput.addEventListener('change', function() {
            const preview = this.parentElement.querySelector('.color-preview');
            const textInput = this.parentElement.querySelector('input[type="text"]');
            preview.style.background = this.value;
            textInput.value = this.value;
        });
    });

    // Section toggle functionality
    document.querySelectorAll('.section-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            if (this.classList.contains('disabled')) {
                this.classList.remove('disabled');
                this.innerHTML = '<i class="fas fa-check-circle"></i> Enabled';
            } else {
                this.classList.add('disabled');
                this.innerHTML = '<i class="fas fa-times-circle"></i> Disabled';
            }
        });
    });

    // Toggle section function
    function toggleSection(header) {
        const sectionCard = header.closest('.section-card');
        const collapse = sectionCard.querySelector('.section-collapse');
        const icon = header.querySelector('.collapse-toggle i');
        
        if (collapse.classList.contains('open')) {
            collapse.classList.remove('open');
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        } else {
            collapse.classList.add('open');
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        }
    }

    // Dynamic list management
    function addService() {
        const container = document.getElementById('services-list');
        const count = container.children.length;
        const html = `
            <div class="list-item">
                <button type="button" class="remove-btn" onclick="removeItem(this)">×</button>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" class="form-control" name="services[${count}][title]" placeholder="Feature Name">
                    </div>
                    <div class="col-md-6 mb-2">
                        <input type="text" class="form-control" name="services[${count}][description]" placeholder="Feature Description">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" class="form-control" name="services[${count}][icon]" placeholder="Icon Class" value="fas fa-tooth">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function addPartner() {
        const container = document.getElementById('partners-list');
        const count = container.children.length;
        const html = `
            <div class="list-item">
                <button type="button" class="remove-btn" onclick="removeItem(this)">×</button>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <input type="text" class="form-control" name="partners[${count}][name]" placeholder="Partner Name">
                    </div>
                    <div class="col-md-6 mb-2">
                        <input type="file" class="form-control" name="partners[${count}][logo]" accept="image/*">
                        <small class="text-gray-500">Partner logo</small>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function addTestimonial() {
        const container = document.getElementById('testimonials-list');
        const count = container.children.length;
        const html = `
            <div class="list-item">
                <button type="button" class="remove-btn" onclick="removeItem(this)">×</button>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" class="form-control" name="testimonials[${count}][name]" placeholder="Client Name">
                    </div>
                    <div class="col-md-4 mb-2">
                        <select class="form-select" name="testimonials[${count}][rating]">
                            <option value="5">5 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="3">3 Stars</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="text" class="form-control" name="testimonials[${count}][designation]" placeholder="Designation" value="Clinic Owner">
                    </div>
                    <div class="col-12 mb-2">
                        <textarea class="form-control" name="testimonials[${count}][review]" rows="2" placeholder="Testimonial text"></textarea>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }



    function removeItem(button) {
        button.closest('.list-item').remove();
    }

    // Main actions
    function previewWebsite() {
        window.open('/', '_blank');
    }

    function saveWebsite() {
        const form = document.getElementById('website-form');
        const saveBtn = document.querySelector('.floating-btn.save');
        
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        form.submit();
    }

    // File upload feedback
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const files = this.files;
            const uploadZone = this.closest('.image-upload-zone');
            if (files.length > 0 && uploadZone) {
                uploadZone.style.borderColor = '#10b981';
                uploadZone.style.background = 'rgba(16, 185, 129, 0.1)';
                uploadZone.querySelector('.upload-text').textContent = `${files.length} file(s) selected`;
            }
        });
    });
</script>
@endsection