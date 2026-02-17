<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $content->meta_title ?? $clinic->name . ' - Professional Dental Care' }}</title>
    <meta name="description" content="{{ $content->meta_description ?? 'Professional dental services at ' . $clinic->name }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: {{ $content->theme_primary_color ?? '#2563eb' }};
            --secondary: {{ $content->theme_secondary_color ?? '#1d4ed8' }};
            --accent: {{ $content->theme_accent_color ?? '#3b82f6' }};
        }
        .clinic-gradient { background: linear-gradient(135deg, var(--primary), var(--secondary)); }
        .clinic-text-gradient { background: linear-gradient(135deg, var(--primary), var(--secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        /* Navbar Scroll Animation */
        nav {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1) !important;
            width: 100vw !important;
            max-width: 100vw !important;
            padding: 1rem 2rem !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 50 !important;
        }
        
        nav.scrolled {
            top: 1.5rem !important;
            width: 70vw !important;
            max-width: 70vw !important;
            left: 50% !important;
            right: auto !important;
            transform: translateX(-50%) !important;
            padding: 0.75rem 2rem !important;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.72)) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15), inset 0 1px 0 0 rgba(255, 255, 255, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            border-radius: 12px !important;
        }
        
        @media (max-width: 1024px) {
            nav.scrolled {
                width: 72vw !important;
                max-width: 72vw !important;
            }
        }

        /* On smaller screens keep the scrolled navbar full-width (with small side inset)
           to avoid cramped centered navbar and overlapping content. */
        @media (max-width: 768px) {
            nav.scrolled {
                width: calc(100% - 2rem) !important;
                max-width: calc(100% - 2rem) !important;
                left: 1rem !important;
                right: 1rem !important;
                transform: none !important;
                padding: 0.6rem 1rem !important;
                border-radius: 8px !important;
            }
        }

        @media (max-width: 640px) {
            nav {
                padding: 1rem 1rem !important;
            }

            nav.scrolled {
                width: 100% !important;
                max-width: 100% !important;
                left: 0 !important;
                right: 0 !important;
                transform: none !important;
                top: 0.5rem !important;
                padding: 0.5rem 0.75rem !important;
                border-radius: 6px !important;
            }
        }
        
        /* Ensure buttons are visible on desktop */
        @media (min-width: 768px) {
            a.hidden {
                display: inline-block !important;
            }
            a.md\\:block {
                display: inline-block !important;
            }
        }
    </style>
</head>
<body class="antialiased">
    <!-- Navbar -->
    <nav class="bg-white/95 backdrop-blur-md shadow-lg fixed w-full z-50 transition-all duration-300" id="navbar">
        <div class="flex justify-between items-center py-4 transition-all duration-300 px-4 sm:px-6 lg:px-8 gap-8 h-20" id="navbar-content">
            <div class="flex items-center space-x-3 flex-shrink-0 min-w-fit h-full">
                <img src="https://images.pexels.com/photos/305568/pexels-photo-305568.jpeg?auto=compress&cs=tinysrgb&w=100&h=100&fit=crop" alt="{{ $clinic->name }}" class="h-12 w-12 rounded-full object-cover shadow-lg">
                <div class="flex flex-col justify-center h-full">
                    <h1 class="text-xl font-bold clinic-text-gradient leading-tight">{{ $clinic->name }}</h1>
                    <span class="text-xs text-gray-500 leading-tight">Professional Dental Care</span>
                </div>
            </div>
            
            <div class="hidden md:flex items-center space-x-8 justify-center flex-1 px-4 h-full">
                <a href="#hero" class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center h-full">Home</a>
                <a href="#about" class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center h-full">About</a>
                <a href="#services" class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center h-full">Services</a>
                <a href="#gallery" class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center h-full">Gallery</a>
                <a href="#testimonials" class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center h-full">Reviews</a>
                <a href="#faq" class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center h-full">FAQ</a>
                <a href="#contact" class="text-gray-600 hover:text-blue-600 transition-colors font-medium flex items-center h-full">Contact</a>
            </div>
            
            <div class="flex items-center space-x-4 flex-shrink-0 h-full">
                <a href="/login" class="hidden md:flex text-gray-600 hover:text-blue-600 font-medium transition-colors text-sm whitespace-nowrap items-center ">Login</a>
                <a href="{{ route('clinic.book', $clinic->slug) }}" class="hidden md:flex clinic-gradient text-white px-4 py-2 rounded-lg hover:opacity-90 transition-all shadow-lg font-semibold text-sm whitespace-nowrap items-center h-full">
                    Book Appointment
                </a>
                <button class="md:hidden text-gray-600 hover:text-blue-600" id="mobile-menu-btn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
            
        <!-- Mobile Menu -->
        <div class="md:hidden hidden border-t border-gray-200 bg-white" id="mobile-menu">
            <div class="px-4 py-3 space-y-2">
                <a href="#hero" class="block px-3 py-2 text-gray-600 hover:text-blue-600 font-medium rounded hover:bg-gray-50">Home</a>
                <a href="#about" class="block px-3 py-2 text-gray-600 hover:text-blue-600 font-medium rounded hover:bg-gray-50">About</a>
                <a href="#services" class="block px-3 py-2 text-gray-600 hover:text-blue-600 font-medium rounded hover:bg-gray-50">Services</a>
                <a href="#gallery" class="block px-3 py-2 text-gray-600 hover:text-blue-600 font-medium rounded hover:bg-gray-50">Gallery</a>
                <a href="#testimonials" class="block px-3 py-2 text-gray-600 hover:text-blue-600 font-medium rounded hover:bg-gray-50">Reviews</a>
                <a href="#faq" class="block px-3 py-2 text-gray-600 hover:text-blue-600 font-medium rounded hover:bg-gray-50">FAQ</a>
                <a href="#contact" class="block px-3 py-2 text-gray-600 hover:text-blue-600 font-medium rounded hover:bg-gray-50">Contact</a>
                <a href="/login" class="block px-3 py-2 text-gray-600 hover:text-blue-600 font-medium rounded hover:bg-gray-50">Login</a>
                <a href="{{ route('clinic.book', $clinic->slug) }}" class="block mx-3 my-2 clinic-gradient text-white px-6 py-2 rounded-lg text-center font-semibold">
                    Book Appointment
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Carousel -->
    <section id="hero" class="pt-20 pb-16 relative overflow-hidden min-h-screen flex items-center">
        <!-- Carousel Background -->
        <div class="absolute inset-0 carousel-container">
            @php
                $heroImages = [
                    'https://images.pexels.com/photos/3845810/pexels-photo-3845810.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop',
                    'https://images.pexels.com/photos/3779709/pexels-photo-3779709.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop',
                    'https://images.pexels.com/photos/3845623/pexels-photo-3845623.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop',
                    'https://images.pexels.com/photos/3779709/pexels-photo-3779709.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop',
                    'https://images.pexels.com/photos/3845810/pexels-photo-3845810.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop',
                    'https://images.pexels.com/photos/3845623/pexels-photo-3845623.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop'
                ];
            @endphp
            @foreach($heroImages as $index => $image)
            <div class="carousel-slide {{ $index === 0 ? 'active' : '' }} absolute inset-0 bg-cover bg-center transition-all duration-1000 transform" style="background-image: linear-gradient(135deg, rgba(37, 99, 235, 0.7), rgba(29, 78, 216, 0.7)), url('{{ $image }}');"></div>
            @endforeach
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-pulse"></div>
            <div class="absolute top-40 right-20 w-16 h-16 bg-blue-300/20 rounded-full animate-bounce"></div>
            <div class="absolute bottom-40 left-20 w-12 h-12 bg-white/15 rounded-full animate-ping"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center text-white animate-fade-in">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                    {{ $content->hero_title ?? 'Welcome to ' . $clinic->name }}
                </h1>
                <p class="text-xl md:text-2xl max-w-4xl mx-auto mb-10 text-blue-100 leading-relaxed">
                    {{ $content->hero_subtitle ?? 'Experience exceptional dental care with our expert team and state-of-the-art facilities. Your smile is our priority.' }}
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="{{ route('clinic.book', $clinic->slug) }}" class="bg-white text-blue-600 px-10 py-4 rounded-2xl text-lg font-bold hover:bg-gray-100 transition-all shadow-2xl transform hover:scale-105">
                        {{ $content->hero_cta_primary ?? 'Book Appointment' }}
                    </a>
                    <a href="#services" class="border-2 border-white text-white px-10 py-4 rounded-2xl text-lg font-bold hover:bg-white/20 transition-all transform hover:scale-105">
                        {{ $content->hero_cta_secondary ?? 'Our Services' }}
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Carousel Indicators -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20">
            @foreach($heroImages as $index => $image)
            <button class="carousel-indicator w-4 h-4 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-white/50' }} transition-all hover:bg-white transform hover:scale-110" data-slide="{{ $index }}"></button>
            @endforeach
        </div>
        
        <!-- Scroll Down Arrow -->
        <div class="absolute bottom-20 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="clinic-gradient py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
                <div>
                    <div class="text-4xl font-bold mb-2">15+</div>
                    <div class="text-blue-100">Years Experience</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">2500+</div>
                    <div class="text-blue-100">Happy Patients</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">98%</div>
                    <div class="text-blue-100">Success Rate</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">24/7</div>
                    <div class="text-blue-100">Emergency Care</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">
                        {{ $content->about_title ?? 'About ' . $clinic->name }}
                    </h2>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        {{ $content->about_description ?? 'We are committed to providing exceptional dental care in a comfortable and modern environment. Our experienced team uses the latest technology to ensure the best possible outcomes for our patients.' }}
                    </p>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900">Expert Dentists</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900">Modern Equipment</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900">Comfortable Environment</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900">Affordable Prices</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="https://images.pexels.com/photos/3779709/pexels-photo-3779709.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&fit=crop" alt="About Us" class="w-full h-96 object-cover rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-600/20 to-transparent rounded-2xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    {{ $content->services_title ?? 'Our Services' }}
                </h2>
                <p class="text-xl text-gray-600">
                    {{ $content->services_description ?? 'Comprehensive dental care services for your entire family' }}
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all">
                    <div class="w-16 h-16 clinic-gradient rounded-2xl flex items-center justify-center mb-6">
                        <span class="text-white text-2xl">🦷</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">General Dentistry</h3>
                    <p class="text-gray-600">Comprehensive oral health care including cleanings, fillings, and preventive treatments</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all">
                    <div class="w-16 h-16 clinic-gradient rounded-2xl flex items-center justify-center mb-6">
                        <span class="text-white text-2xl">✨</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Cosmetic Dentistry</h3>
                    <p class="text-gray-600">Enhance your smile with teeth whitening, veneers, and smile makeovers</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all">
                    <div class="w-16 h-16 clinic-gradient rounded-2xl flex items-center justify-center mb-6">
                        <span class="text-white text-2xl">🔧</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Orthodontics</h3>
                    <p class="text-gray-600">Straighten your teeth with traditional braces or modern clear aligners</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all">
                    <div class="w-16 h-16 clinic-gradient rounded-2xl flex items-center justify-center mb-6">
                        <span class="text-white text-2xl">🏥</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Oral Surgery</h3>
                    <p class="text-gray-600">Expert surgical procedures including extractions and implant placement</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all">
                    <div class="w-16 h-16 clinic-gradient rounded-2xl flex items-center justify-center mb-6">
                        <span class="text-white text-2xl">👶</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Pediatric Dentistry</h3>
                    <p class="text-gray-600">Specialized dental care for children in a friendly environment</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all">
                    <div class="w-16 h-16 clinic-gradient rounded-2xl flex items-center justify-center mb-6">
                        <span class="text-white text-2xl">🚨</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Emergency Care</h3>
                    <p class="text-gray-600">24/7 emergency dental services for urgent dental problems</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    {{ $content->gallery_title ?? 'Our Gallery' }}
                </h2>
                <p class="text-xl text-gray-600">Take a look at our modern facilities and happy patients</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @php
                    $defaultGallery = [
                        'https://images.pexels.com/photos/3845810/pexels-photo-3845810.jpeg?auto=compress&cs=tinysrgb&w=400&h=300&fit=crop',
                        'https://images.pexels.com/photos/3779709/pexels-photo-3779709.jpeg?auto=compress&cs=tinysrgb&w=400&h=300&fit=crop',
                        'https://images.pexels.com/photos/3845623/pexels-photo-3845623.jpeg?auto=compress&cs=tinysrgb&w=400&h=300&fit=crop',
                        'https://images.pexels.com/photos/305568/pexels-photo-305568.jpeg?auto=compress&cs=tinysrgb&w=400&h=300&fit=crop',
                        'https://images.pexels.com/photos/3779709/pexels-photo-3779709.jpeg?auto=compress&cs=tinysrgb&w=400&h=300&fit=crop',
                        'https://images.pexels.com/photos/3845810/pexels-photo-3845810.jpeg?auto=compress&cs=tinysrgb&w=400&h=300&fit=crop',
                        'https://images.pexels.com/photos/3845623/pexels-photo-3845623.jpeg?auto=compress&cs=tinysrgb&w=400&h=300&fit=crop',
                        'https://images.pexels.com/photos/305568/pexels-photo-305568.jpeg?auto=compress&cs=tinysrgb&w=400&h=300&fit=crop'
                    ];
                    $galleryImages = $content && $content->gallery_images ? json_decode($content->gallery_images, true) : $defaultGallery;
                @endphp
                @foreach($galleryImages as $index => $image)
                <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                    <img src="{{ is_string($image) ? $image : $content->getGalleryImageUrl($image) }}" alt="Gallery Image {{ $index + 1 }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-600/70 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                        <div class="text-white text-center transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <p class="text-sm font-semibold">View Image</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    {{ $content->testimonials_title ?? 'What Our Patients Say' }}
                </h2>
                <p class="text-xl text-gray-600">Real experiences from our valued patients</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $defaultTestimonials = [
                        ['name' => 'Sarah Johnson', 'designation' => 'Patient', 'review' => 'Excellent service and very professional staff. The treatment was painless and the results exceeded my expectations.'],
                        ['name' => 'Michael Chen', 'designation' => 'Patient', 'review' => 'Best dental clinic in the city! The doctors are highly skilled and the facility is top-notch. Highly recommended!'],
                        ['name' => 'Emily Davis', 'designation' => 'Patient', 'review' => 'Amazing experience from start to finish. The team made me feel comfortable and the treatment was excellent.']
                    ];
                    $testimonials = $content && $content->testimonials_data ? json_decode($content->testimonials_data, true) : $defaultTestimonials;
                @endphp
                @foreach($testimonials as $testimonial)
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="flex text-yellow-400 mb-4">
                        @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        @endfor
                    </div>
                    <blockquote class="text-gray-600 mb-6 italic">
                        "{{ $testimonial['review'] }}"
                    </blockquote>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">{{ substr($testimonial['name'], 0, 1) }}</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $testimonial['name'] }}</h4>
                            <p class="text-gray-600 text-sm">{{ $testimonial['designation'] ?? 'Patient' }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    {{ $content->faq_title ?? 'Frequently Asked Questions' }}
                </h2>
                <p class="text-xl text-gray-600">Find answers to common questions about our services</p>
            </div>
            <div class="space-y-6">
                @php
                    $defaultFAQs = [
                        ['question' => 'How often should I visit the dentist?', 'answer' => 'We recommend visiting the dentist every 6 months for regular check-ups and cleanings to maintain optimal oral health.'],
                        ['question' => 'Do you accept insurance?', 'answer' => 'Yes, we accept most major dental insurance plans. Please contact our office to verify your specific coverage.'],
                        ['question' => 'What should I do in a dental emergency?', 'answer' => 'Contact our office immediately. We provide 24/7 emergency dental care for urgent situations.'],
                        ['question' => 'Are your treatments painful?', 'answer' => 'We use modern techniques and anesthesia to ensure your comfort during all procedures. Most patients experience minimal to no discomfort.']
                    ];
                    $faqs = $content && $content->faq_data ? json_decode($content->faq_data, true) : $defaultFAQs;
                @endphp
                @foreach($faqs as $index => $faq)
                <div class="bg-gray-50 rounded-2xl p-6">
                    <button class="faq-toggle w-full text-left flex justify-between items-center" data-target="faq-{{ $index }}">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $faq['question'] }}</h3>
                        <svg class="w-6 h-6 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="faq-{{ $index }}" class="faq-content hidden mt-4">
                        <p class="text-gray-600">{{ $faq['answer'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 clinic-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">
                    {{ $content->contact_title ?? 'Ready to Schedule Your Visit?' }}
                </h2>
                <p class="text-xl text-blue-100">
                    {{ $content->contact_subtitle ?? 'Contact us today to book your appointment and start your journey to better oral health' }}
                </p>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-16">
                <div class="text-white">
                    <h3 class="text-2xl font-bold mb-8">Get in Touch</h3>
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Address</h4>
                                <p class="text-blue-100">{{ $content->contact_address ?? $clinic->address ?? 'Kathmandu, Nepal' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Phone</h4>
                                <p class="text-blue-100">{{ $content->contact_phone ?? $clinic->phone ?? '+977-1-XXXXXXX' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold">Email</h4>
                                <p class="text-blue-100">{{ $content->contact_email ?? $clinic->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8">
                    <h3 class="text-2xl font-bold text-white mb-6">Send us a Message</h3>
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <input type="text" placeholder="Your Name" class="w-full px-4 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50">
                            <input type="email" placeholder="Your Email" class="w-full px-4 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50">
                        </div>
                        <input type="text" placeholder="Subject" class="w-full px-4 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50">
                        <textarea rows="4" placeholder="Your Message" class="w-full px-4 py-3 rounded-xl bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50"></textarea>
                        <button type="submit" class="w-full bg-white text-blue-600 py-3 px-6 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <img src="https://images.pexels.com/photos/305568/pexels-photo-305568.jpeg?auto=compress&cs=tinysrgb&w=100&h=100&fit=crop" alt="{{ $clinic->name }}" class="h-12 w-12 rounded-full object-cover shadow-lg">
                        <div>
                            <h3 class="text-xl font-bold">{{ $clinic->name }}</h3>
                            <p class="text-gray-400">Professional Dental Care</p>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-6">
                        {{ $content->footer_description ?? 'Your trusted dental care provider committed to excellence in oral health and patient satisfaction.' }}
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-6">Quick Links</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="#about" class="hover:text-white transition-colors">About</a></li>
                        <li><a href="#services" class="hover:text-white transition-colors">Services</a></li>
                        <li><a href="#gallery" class="hover:text-white transition-colors">Gallery</a></li>
                        <li><a href="#contact" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-6">Contact Info</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li>{{ $content->contact_address ?? $clinic->address ?? 'Kathmandu, Nepal' }}</li>
                        <li>{{ $content->contact_phone ?? $clinic->phone ?? '+977-1-XXXXXXX' }}</li>
                        <li>{{ $content->contact_email ?? $clinic->email }}</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                <p class="text-gray-400">
                    {{ $content->footer_copyright ?? '© 2024 ' . $clinic->name . '. All rights reserved. Powered by DentalCare Pro' }}
                </p>
            </div>
        </div>
    </footer>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 1s ease-out;
        }
        .navbar-scrolled {
            padding: 0.5rem 0 !important;
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .navbar-scrolled #navbar-content {
            padding: 0.75rem 0 !important;
        }
    </style>
    
    <script>
        // Navbar scroll animation with smooth transitions
        const navbar = document.getElementById('navbar');
        const scrollThreshold = 30; // Lower threshold for faster trigger
        let ticking = false;

        function updateNavbarState() {
            const currentScrollY = window.scrollY;
            
            if (currentScrollY > scrollThreshold) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }

        // Use requestAnimationFrame for smooth 60fps animation
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    updateNavbarState();
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });

        // Initial state check
        updateNavbarState();

        // Mobile Menu Toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Hero Carousel
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const indicators = document.querySelectorAll('.carousel-indicator');
        
        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
                slide.style.opacity = i === index ? '1' : '0';
                slide.style.transform = i === index ? 'scale(1)' : 'scale(1.1)';
            });
            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('bg-white', i === index);
                indicator.classList.toggle('bg-white/50', i !== index);
            });
        }
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }
        
        // Auto-advance carousel
        setInterval(nextSlide, 4000);
        
        // Indicator clicks
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                showSlide(currentSlide);
            });
        });

        // FAQ Toggle
        document.querySelectorAll('.faq-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const target = document.getElementById(button.dataset.target);
                const icon = button.querySelector('svg');
                
                if (target.classList.contains('hidden')) {
                    target.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    target.classList.add('hidden');
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Close mobile menu if open
                    document.getElementById('mobile-menu').classList.add('hidden');
                }
            });
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all sections
        document.querySelectorAll('section').forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(30px)';
            section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(section);
        });

        // Hero section should be visible immediately
        document.getElementById('hero').style.opacity = '1';
        document.getElementById('hero').style.transform = 'translateY(0)';
    </script>
</body>
</html>