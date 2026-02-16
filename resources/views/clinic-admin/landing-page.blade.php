@extends('layouts.app')

@section('title', 'Website Builder')

@section('content')
<style>
    .main-content {
        margin-left: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }
    
    .website-builder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    
    .builder-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        overflow: hidden;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .builder-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 30px;
        position: relative;
    }
    
    .builder-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .builder-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 10px;
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }
    
    .header-btn {
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    
    .section-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        margin-bottom: 25px;
        transition: all 0.3s ease;
        overflow: hidden;
        border-left: 4px solid transparent;
    }
    
    .section-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.12);
    }
    
    .section-card.active {
        border-left-color: #667eea;
    }
    
    .section-header {
        color: white;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .section-header.hero { background: linear-gradient(135deg, #ef4444, #f97316); }
    .section-header.about { background: linear-gradient(135deg, #10b981, #059669); }
    .section-header.services { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .section-header.gallery { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .section-header.testimonials { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .section-header.faq { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .section-header.contact { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .section-header.theme { background: linear-gradient(135deg, #ec4899, #be185d); }
    .section-header.seo { background: linear-gradient(135deg, #84cc16, #65a30d); }
    .section-header.footer { background: linear-gradient(135deg, #374151, #1f2937); }

    .section-header h5 {
        display: flex;
        align-items: center;
        margin: 0;
        font-weight: 600;
    }
    
    .section-indicator {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        margin-right: 10px;
        font-size: 12px;
    }
    
    .section-icon {
        margin-right: 10px;
        font-size: 18px;
    }
    
    .section-toggle {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        border-radius: 20px;
        padding: 5px 15px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .section-toggle:hover {
        background: rgba(255,255,255,0.3);
    }
    
    .collapse-toggle {
        background: none;
        border: none;
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-left: 10px;
    }
    
    .section-collapse {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    
    .section-collapse.open {
        max-height: 2000px;
    }
    
    .form-section {
        padding: 25px;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .form-grid-full {
        grid-column: 1 / -1;
    }
    
    .control-group {
        margin-bottom: 20px;
    }
    
    .control-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
    }
    
    .form-control, .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafafa;
        width: 100%;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: white;
        outline: none;
    }
    
    .help-text {
        font-size: 12px;
        color: #6b7280;
        margin-top: 5px;
    }
    
    .image-upload-zone {
        border: 3px dashed #d1d5db;
        border-radius: 15px;
        padding: 40px 20px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #f9fafb;
    }
    
    .image-upload-zone:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }
    
    .upload-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #9ca3af;
    }
    
    .upload-text {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .upload-subtext {
        color: #6b7280;
        margin-bottom: 0;
    }
    
    .dynamic-list {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        margin-top: 15px;
    }
    
    .list-item {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        border: 1px solid #e5e7eb;
        position: relative;
    }
    
    .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .add-btn {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    
    .add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }
    
    .color-picker-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .color-preview {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        border: 3px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .color-preview:hover {
        transform: scale(1.05);
        border-color: #667eea;
    }
    
    .floating-actions {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .floating-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .floating-btn.save {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    
    .floating-btn.preview {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }
    
    .floating-btn:hover {
        transform: scale(1.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }
</style>

<div class="main-content">
    <div class="website-builder">
        <div class="builder-container">
            <!-- Header -->
            <div class="builder-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="builder-title">🌐 Website Builder</h1>
                        <p class="builder-subtitle">Complete control over your clinic's landing page</p>
                        <span class="status-badge">
                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                            Published
                        </span>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="/clinic/{{ $clinic->slug }}" target="_blank" class="btn btn-light header-btn me-2">
                            <i class="fas fa-external-link-alt"></i>Preview Live
                        </a>
                        <button type="button" class="btn btn-success header-btn" onclick="publishWebsite()">
                            <i class="fas fa-rocket"></i>Publish
                        </button>
                    </div>
                </div>
            </div>

            <form id="website-form" action="{{ route('clinic.landing-page-manager.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="p-4">
                    <div class="row">
                        <!-- Main Content Sections -->
                        <div class="col-lg-8">
                            
                            <!-- Hero Section -->
                            <div class="section-card active">
                                <div class="section-header hero" onclick="toggleSectionCollapse(this)">
                                    <h5 class="mb-0">
                                        <span class="section-indicator">1</span>
                                        <i class="fas fa-star section-icon"></i>Hero Section
                                    </h5>
                                    <div>
                                        <button type="button" class="section-toggle" data-section="hero" onclick="event.stopPropagation()">
                                            <i class="fas fa-check-circle"></i> Enabled
                                        </button>
                                        <button type="button" class="collapse-toggle">
                                            <i class="fas fa-chevron-up"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="section-collapse open">
                                    <div class="form-section">
                                        <div class="form-grid">
                                            <div class="control-group">
                                                <label class="control-label">Main Headline</label>
                                                <input type="text" class="form-control" name="hero_title" 
                                                       value="{{ $content->hero_title ?? 'Welcome to ' . $clinic->name }}"
                                                       placeholder="Your compelling headline">
                                                <div class="help-text">The main title visitors see first</div>
                                            </div>
                                            <div class="control-group form-grid-full">
                                                <label class="control-label">Subtitle</label>
                                                <textarea class="form-control" name="hero_subtitle" rows="2" 
                                                          placeholder="Describe your clinic's unique value">{{ $content->hero_subtitle ?? 'Experience exceptional dental care with our expert team.' }}</textarea>
                                                <div class="help-text">Supporting text under the headline</div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Primary Button Text</label>
                                                <input type="text" class="form-control" name="hero_cta_primary" 
                                                       value="{{ $content->hero_cta_primary ?? 'Book Appointment' }}">
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Secondary Button Text</label>
                                                <input type="text" class="form-control" name="hero_cta_secondary" 
                                                       value="{{ $content->hero_cta_secondary ?? 'Our Services' }}">
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label class="control-label">Hero Background Images</label>
                                            <div class="image-upload-zone" onclick="document.getElementById('hero-images').click()">
                                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                                <h6 class="upload-text">Upload Hero Images</h6>
                                                <p class="upload-subtext">Multiple images for carousel effect</p>
                                                <input type="file" id="hero-images" name="hero_carousel_images[]" accept="image/*" multiple style="display: none;">
                                            </div>
                                            <div class="help-text">Recommended: 1920x1080px, JPG/PNG format</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- About Section -->
                            <div class="section-card">
                                <div class="section-header about" onclick="toggleSectionCollapse(this)">
                                    <h5 class="mb-0">
                                        <span class="section-indicator">2</span>
                                        <i class="fas fa-info-circle section-icon"></i>About Section
                                    </h5>
                                    <div>
                                        <button type="button" class="section-toggle" data-section="about" onclick="event.stopPropagation()">
                                            <i class="fas fa-check-circle"></i> Enabled
                                        </button>
                                        <button type="button" class="collapse-toggle">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="section-collapse">
                                    <div class="form-section">
                                        <div class="form-grid">
                                            <div class="control-group">
                                                <label class="control-label">Section Title</label>
                                                <input type="text" class="form-control" name="about_title" 
                                                       value="{{ $content->about_title ?? 'About ' . $clinic->name }}">
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Years of Experience</label>
                                                <input type="number" class="form-control" name="about_years_experience" 
                                                       value="{{ $content->about_years_experience ?? '10' }}" min="1" max="50">
                                            </div>
                                            <div class="control-group form-grid-full">
                                                <label class="control-label">About Description</label>
                                                <textarea class="form-control" name="about_description" rows="4" 
                                                          placeholder="Tell your story, expertise, and what makes you special">{{ $content->about_description ?? 'We are committed to providing exceptional dental care in a comfortable and modern environment.' }}</textarea>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">About Image</label>
                                                <input type="file" class="form-control" name="about_image" accept="image/*">
                                                <div class="help-text">Professional photo of clinic or team</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Services Section -->
                            <div class="section-card">
                                <div class="section-header services" onclick="toggleSectionCollapse(this)">
                                    <h5 class="mb-0">
                                        <span class="section-indicator">3</span>
                                        <i class="fas fa-cogs section-icon"></i>Services Section
                                    </h5>
                                    <div>
                                        <button type="button" class="section-toggle" data-section="services" onclick="event.stopPropagation()">
                                            <i class="fas fa-check-circle"></i> Enabled
                                        </button>
                                        <button type="button" class="collapse-toggle">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="section-collapse">
                                    <div class="form-section">
                                        <div class="form-grid">
                                            <div class="control-group">
                                                <label class="control-label">Section Title</label>
                                                <input type="text" class="form-control" name="services_title" 
                                                       value="{{ $content->services_title ?? 'Our Services' }}">
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Section Description</label>
                                                <textarea class="form-control" name="services_description" rows="2">{{ $content->services_description ?? 'Comprehensive dental care for your entire family' }}</textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="dynamic-list">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0">Custom Services</h6>
                                                <button type="button" class="add-btn" onclick="addService()">
                                                    <i class="fas fa-plus"></i>Add Service
                                                </button>
                                            </div>
                                            <div id="services-list">
                                                @php
                                                    $services = $content && $content->services_data ? json_decode($content->services_data, true) : [
                                                        ['title' => 'Teeth Cleaning', 'description' => 'Professional cleaning and polishing', 'icon' => 'fas fa-tooth'],
                                                        ['title' => 'Dental Fillings', 'description' => 'Tooth-colored fillings for cavities', 'icon' => 'fas fa-teeth']
                                                    ];
                                                @endphp
                                                @foreach($services as $index => $service)
                                                <div class="list-item">
                                                    <button type="button" class="remove-btn" onclick="removeItem(this)">×</button>
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2">
                                                            <input type="text" class="form-control" name="services[{{ $index }}][title]" value="{{ $service['title'] }}" placeholder="Service Name">
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <input type="text" class="form-control" name="services[{{ $index }}][description]" value="{{ $service['description'] }}" placeholder="Service Description">
                                                        </div>
                                                        <div class="col-md-2 mb-2">
                                                            <input type="text" class="form-control" name="services[{{ $index }}][icon]" value="{{ $service['icon'] }}" placeholder="Icon Class">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Gallery Section -->
                            <div class="section-card">
                                <div class="section-header gallery" onclick="toggleSectionCollapse(this)">
                                    <h5 class="mb-0">
                                        <span class="section-indicator">4</span>
                                        <i class="fas fa-images section-icon"></i>Gallery Section
                                    </h5>
                                    <div>
                                        <button type="button" class="section-toggle" data-section="gallery" onclick="event.stopPropagation()">
                                            <i class="fas fa-check-circle"></i> Enabled
                                        </button>
                                        <button type="button" class="collapse-toggle">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="section-collapse">
                                    <div class="form-section">
                                        <div class="form-grid">
                                            <div class="control-group">
                                                <label class="control-label">Gallery Title</label>
                                                <input type="text" class="form-control" name="gallery_title" 
                                                       value="{{ $content->gallery_title ?? 'Our Gallery' }}">
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Gallery Layout Style</label>
                                                <select class="form-select" name="gallery_layout">
                                                    <option value="grid" selected>Grid Layout</option>
                                                    <option value="masonry">Masonry Layout</option>
                                                    <option value="carousel">Carousel Layout</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label class="control-label">Gallery Images</label>
                                            <div class="image-upload-zone" onclick="document.getElementById('gallery-images').click()">
                                                <i class="fas fa-images upload-icon"></i>
                                                <h6 class="upload-text">Upload Gallery Images</h6>
                                                <p class="upload-subtext">Showcase your clinic and work</p>
                                                <input type="file" id="gallery-images" name="gallery_images[]" accept="image/*" multiple style="display: none;">
                                            </div>
                                            <div class="help-text">Recommended: 400x300px, multiple images</div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- FAQ Section -->
                            <div class="section-card">
                                <div class="section-header faq" onclick="toggleSectionCollapse(this)">
                                    <h5 class="mb-0">
                                        <span class="section-indicator">5</span>
                                        <i class="fas fa-question-circle section-icon"></i>FAQ Section
                                    </h5>
                                    <div>
                                        <button type="button" class="section-toggle" data-section="faq" onclick="event.stopPropagation()">
                                            <i class="fas fa-check-circle"></i> Enabled
                                        </button>
                                        <button type="button" class="collapse-toggle">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="section-collapse">
                                    <div class="form-section">
                                        <div class="form-grid">
                                            <div class="control-group">
                                                <label class="control-label">Section Title</label>
                                                <input type="text" class="form-control" name="faq_title" 
                                                       value="{{ $content->faq_title ?? 'Frequently Asked Questions' }}">
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Section Description</label>
                                                <input type="text" class="form-control" name="faq_description" 
                                                       value="{{ $content->faq_description ?? 'Find answers to common questions' }}">
                                            </div>
                                        </div>
                                        
                                        <div class="dynamic-list">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0">FAQ Items</h6>
                                                <button type="button" class="add-btn" onclick="addFAQ()">
                                                    <i class="fas fa-plus"></i>Add FAQ
                                                </button>
                                            </div>
                                            <div id="faq-list">
                                                @php
                                                    $faqs = $content && $content->faq_data ? json_decode($content->faq_data, true) : [
                                                        ['question' => 'How often should I visit the dentist?', 'answer' => 'We recommend visiting the dentist every six months for a routine checkup and cleaning.']
                                                    ];
                                                @endphp
                                                @foreach($faqs as $index => $faq)
                                                <div class="list-item">
                                                    <button type="button" class="remove-btn" onclick="removeItem(this)">×</button>
                                                    <div class="row">
                                                        <div class="col-12 mb-2">
                                                            <input type="text" class="form-control" name="faq[{{ $index }}][question]" value="{{ $faq['question'] }}" placeholder="Question">
                                                        </div>
                                                        <div class="col-12 mb-2">
                                                            <textarea class="form-control" name="faq[{{ $index }}][answer]" rows="2" placeholder="Answer">{{ $faq['answer'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Sidebar Settings -->
                        <div class="col-lg-4">
                            
                            <!-- Contact Information -->
                            <div class="section-card">
                                <div class="section-header contact">
                                    <h5 class="mb-0">
                                        <i class="fas fa-phone section-icon"></i>Contact Information
                                    </h5>
                                </div>
                                <div class="form-section">
                                    <div class="control-group">
                                        <label class="control-label">Contact Section Title</label>
                                        <input type="text" class="form-control" name="contact_title" 
                                               value="{{ $content->contact_title ?? 'Ready to Schedule Your Visit?' }}">
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Phone Number</label>
                                        <input type="text" class="form-control" name="contact_phone" 
                                               value="{{ $content->contact_phone ?? $clinic->phone ?? '(555) 123-4567' }}">
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Email Address</label>
                                        <input type="email" class="form-control" name="contact_email" 
                                               value="{{ $content->contact_email ?? $clinic->email ?? 'info@dentalcareclinic.com' }}">
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Contact Subtitle</label>
                                        <textarea class="form-control" name="contact_subtitle" rows="2">{{ $content->contact_subtitle ?? 'Contact us today to book your appointment' }}</textarea>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Physical Address</label>
                                        <textarea class="form-control" name="contact_address" rows="2">{{ $content->contact_address ?? $clinic->address ?? '123 Main Street, Anytown, USA 12345' }}</textarea>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Business Hours</label>
                                        <textarea class="form-control" name="business_hours" rows="3" 
                                                  placeholder="Mon-Fri: 9AM-6PM&#10;Sat: 9AM-4PM&#10;Sun: Closed">{{ $content->business_hours ?? "Mon-Fri: 9AM-6PM\nSat: 9AM-4PM\nSun: Closed" }}</textarea>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Google Maps Embed URL</label>
                                        <input type="url" class="form-control" name="google_maps_url" 
                                               value="{{ $content->google_maps_url ?? '' }}"
                                               placeholder="https://www.google.com/maps/embed?pb=...">
                                        <div class="help-text">Get embed URL from Google Maps</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Theme Customization -->
                            <div class="section-card">
                                <div class="section-header theme">
                                    <h5 class="mb-0">
                                        <i class="fas fa-palette section-icon"></i>Theme & Colors
                                    </h5>
                                </div>
                                <div class="form-section">
                                    <div class="control-group">
                                        <label class="control-label">Primary Color</label>
                                        <div class="color-picker-group">
                                            <div class="color-preview" style="background: {{ $content->theme_primary_color ?? '#2563eb' }}" 
                                                 onclick="document.getElementById('primary-color').click()"></div>
                                            <input type="color" id="primary-color" name="theme_primary_color" 
                                                   value="{{ $content->theme_primary_color ?? '#2563eb' }}" style="display: none;">
                                            <input type="text" class="form-control" value="{{ $content->theme_primary_color ?? '#2563eb' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Secondary Color</label>
                                        <div class="color-picker-group">
                                            <div class="color-preview" style="background: {{ $content->theme_secondary_color ?? '#1d4ed8' }}" 
                                                 onclick="document.getElementById('secondary-color').click()"></div>
                                            <input type="color" id="secondary-color" name="theme_secondary_color" 
                                                   value="{{ $content->theme_secondary_color ?? '#1d4ed8' }}" style="display: none;">
                                            <input type="text" class="form-control" value="{{ $content->theme_secondary_color ?? '#1d4ed8' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Accent Color</label>
                                        <div class="color-picker-group">
                                            <div class="color-preview" style="background: {{ $content->theme_accent_color ?? '#3b82f6' }}" 
                                                 onclick="document.getElementById('accent-color').click()"></div>
                                            <input type="color" id="accent-color" name="theme_accent_color" 
                                                   value="{{ $content->theme_accent_color ?? '#3b82f6' }}" style="display: none;">
                                            <input type="text" class="form-control" value="{{ $content->theme_accent_color ?? '#3b82f6' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Font Family</label>
                                        <select class="form-select" name="theme_font_family">
                                            <option value="Inter" {{ ($content->theme_font_family ?? 'Inter') == 'Inter' ? 'selected' : '' }}>Inter (Default)</option>
                                            <option value="Roboto" {{ ($content->theme_font_family ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                            <option value="Open Sans" {{ ($content->theme_font_family ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                            <option value="Lato" {{ ($content->theme_font_family ?? '') == 'Lato' ? 'selected' : '' }}>Lato</option>
                                            <option value="Poppins" {{ ($content->theme_font_family ?? '') == 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Settings -->
                            <div class="section-card">
                                <div class="section-header seo">
                                    <h5 class="mb-0">
                                        <i class="fas fa-search section-icon"></i>SEO & Meta
                                    </h5>
                                </div>
                                <div class="form-section">
                                    <div class="control-group">
                                        <label class="control-label">Meta Title</label>
                                        <input type="text" class="form-control" name="meta_title" 
                                               value="{{ $content->meta_title ?? $clinic->name . ' - Professional Dental Care' }}"
                                               maxlength="60">
                                        <div class="help-text">50-60 characters recommended</div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Meta Description</label>
                                        <textarea class="form-control" name="meta_description" rows="3" 
                                                  maxlength="160">{{ $content->meta_description ?? 'Professional dental services at ' . $clinic->name }}</textarea>
                                        <div class="help-text">150-160 characters recommended</div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Keywords</label>
                                        <input type="text" class="form-control" name="meta_keywords" 
                                               value="{{ $content->meta_keywords ?? 'dental care, dentist, oral health, ' . $clinic->name }}"
                                               placeholder="Separate with commas">
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Google Analytics ID</label>
                                        <input type="text" class="form-control" name="google_analytics_id" 
                                               value="{{ $content->google_analytics_id ?? '' }}"
                                               placeholder="G-XXXXXXXXXX">
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Facebook Pixel ID</label>
                                        <input type="text" class="form-control" name="facebook_pixel_id" 
                                               value="{{ $content->facebook_pixel_id ?? '' }}"
                                               placeholder="123456789012345">
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Settings -->
                            <div class="section-card">
                                <div class="section-header footer">
                                    <h5 class="mb-0">
                                        <i class="fas fa-copyright section-icon"></i>Footer Settings
                                    </h5>
                                </div>
                                <div class="form-section">
                                    <div class="control-group">
                                        <label class="control-label">Footer Description</label>
                                        <textarea class="form-control" name="footer_description" rows="3">{{ $content->footer_description ?? 'Your trusted dental care provider committed to excellence.' }}</textarea>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Copyright Text</label>
                                        <input type="text" class="form-control" name="footer_copyright" 
                                               value="{{ $content->footer_copyright ?? '© ' . date('Y') . ' ' . $clinic->name . '. All rights reserved.' }}">
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

    // Toggle section collapse function
    function toggleSectionCollapse(header) {
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

    // Collapse toggle functionality
    document.querySelectorAll('.collapse-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSectionCollapse(this.closest('.section-header'));
        });
    });

    // Dynamic list management
    function addService() {
        const container = document.getElementById('services-list');
        const count = container.children.length;
        const html = `
            <div class="list-item">
                <button type="button" class="remove-btn" onclick="removeItem(this)">×</button>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" class="form-control" name="services[${count}][title]" placeholder="Service Name">
                    </div>
                    <div class="col-md-6 mb-2">
                        <input type="text" class="form-control" name="services[${count}][description]" placeholder="Service Description">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" class="form-control" name="services[${count}][icon]" placeholder="Icon Class" value="fas fa-tooth">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }



    function addFAQ() {
        const container = document.getElementById('faq-list');
        const count = container.children.length;
        const html = `
            <div class="list-item">
                <button type="button" class="remove-btn" onclick="removeItem(this)">×</button>
                <div class="row">
                    <div class="col-12 mb-2">
                        <input type="text" class="form-control" name="faq[${count}][question]" placeholder="Question">
                    </div>
                    <div class="col-12 mb-2">
                        <textarea class="form-control" name="faq[${count}][answer]" rows="2" placeholder="Answer"></textarea>
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
        window.open('/clinic/{{ $clinic->slug }}', '_blank');
    }

    function saveWebsite() {
        const form = document.getElementById('website-form');
        const saveBtn = document.querySelector('.floating-btn.save');
        
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        form.submit();
    }

    function publishWebsite() {
        if (confirm('Are you sure you want to publish these changes to your live website?')) {
            saveWebsite();
        }
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