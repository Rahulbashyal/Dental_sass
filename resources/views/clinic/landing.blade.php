<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $clinic->name }} - Professional Dental Care | DentalCare Pro</title>
    <meta name="description" content="Professional dental services at {{ $clinic->name }}. Book appointments, manage your dental health with our advanced clinic management system.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --clinic-primary: {{ $clinic->theme_color ?? '#2563eb' }};
            --clinic-secondary: {{ $clinic->secondary_color ?? '#1d4ed8' }};
            --clinic-accent: {{ $clinic->accent_color ?? '#3b82f6' }};
        }
        .clinic-gradient { background: linear-gradient(135deg, var(--clinic-primary) 0%, var(--clinic-secondary) 50%, var(--clinic-accent) 100%); }
        .clinic-text-gradient { background: linear-gradient(135deg, var(--clinic-primary) 0%, var(--clinic-secondary) 50%, var(--clinic-accent) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        /* Unique Pattern Styles for Each Clinic */
        @if($clinic->id % 4 == 1)
        .clinic-pattern { background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(59, 130, 246, 0.1) 10px, rgba(59, 130, 246, 0.1) 20px); }
        .shape-1 { clip-path: polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%); }
        .shape-2 { clip-path: polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%); }
        @elseif($clinic->id % 4 == 2)
        .clinic-pattern { background-image: radial-gradient(circle at 20% 50%, rgba(16, 185, 129, 0.1) 20%, transparent 21%), radial-gradient(circle at 80% 50%, rgba(34, 197, 94, 0.1) 20%, transparent 21%); background-size: 40px 40px; }
        .shape-1 { clip-path: polygon(25% 0%, 100% 0%, 75% 100%, 0% 100%); }
        .shape-2 { clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%); }
        @elseif($clinic->id % 4 == 3)
        .clinic-pattern { background-image: conic-gradient(from 45deg, transparent 0deg, rgba(168, 85, 247, 0.1) 90deg, transparent 180deg, rgba(147, 51, 234, 0.1) 270deg, transparent 360deg); background-size: 60px 60px; }
        .shape-1 { clip-path: polygon(0% 20%, 60% 20%, 60% 0%, 100% 50%, 60% 100%, 60% 80%, 0% 80%); }
        .shape-2 { clip-path: polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%); }
        @else
        .clinic-pattern { background-image: linear-gradient(90deg, transparent 50%, rgba(245, 101, 101, 0.1) 50%), linear-gradient(rgba(239, 68, 68, 0.1) 50%, transparent 50%); background-size: 30px 30px; }
        .shape-1 { clip-path: polygon(50% 0%, 90% 20%, 100% 60%, 75% 100%, 25% 100%, 0% 60%, 10% 20%); }
        .shape-2 { clip-path: polygon(20% 0%, 0% 20%, 30% 50%, 0% 80%, 20% 100%, 80% 100%, 100% 80%, 70% 50%, 100% 20%, 80% 0%); }
        @endif
        
        .floating { animation: float 6s ease-in-out infinite; }
        .pulse-glow { animation: pulseGlow 3s ease-in-out infinite; }
        .slide-up { animation: slideUp 1s ease-out; }
        .slide-down { animation: slideDown 1s ease-out; }
        .zoom-in { animation: zoomIn 1s ease-out; }
        .rotate-slow { animation: rotateSlow 20s linear infinite; }
        
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        @keyframes pulseGlow { 0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.4); } 50% { box-shadow: 0 0 40px rgba(59, 130, 246, 0.8); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(50px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-50px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes zoomIn { from { opacity: 0; transform: scale(0.8); } to { opacity: 1; transform: scale(1); } }
        @keyframes rotateSlow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        
        .glass-card { backdrop-filter: blur(20px); background: rgba(255, 255, 255, 0.9); border: 1px solid rgba(255, 255, 255, 0.3); }
        .clinic-bg { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%); }
        .hover-lift { transition: transform 0.3s ease; }
        .hover-lift:hover { transform: translateY(-10px); }
        
        /* Navbar Scroll Animation */
        nav {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1) !important;
            width: 100vw !important;
            max-width: 100vw !important;
            padding: 1.25rem 2rem !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 50 !important;
            background: rgba(255, 255, 255, 0.95) !important;
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
        
        nav .nav-container {
            transition: all 0.3s ease;
            position: relative;
            font-size: 0.95rem;
        }
        
        .nav-link-item:hover {
            color: var(--clinic-primary);
        }
        
        .nav-link-item::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--clinic-primary);
            transition: width 0.3s ease;
        }
        
        .nav-link-item:hover::after {
            width: 100%;
        }
        
        /* Responsive adjustments */
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

            .nav-link-item {
                font-size: 0.85rem;
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
                width: 80vw !important;
                max-width: 80vw !important;
                top: 1rem !important;
                padding: 0.6rem 1rem !important;
            }
        }
    </style>
</head>
<body class="antialiased bg-gray-50 overflow-x-hidden">
    <!-- Navigation with Scroll Animation -->
    <nav class="bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-100 fixed z-50 w-full">
        <div class="nav-container flex justify-between items-center py-4 px-4 sm:px-6 lg:px-8 gap-8 h-20">
            <!-- Brand -->
            <div class="nav-brand flex items-center space-x-3 slide-up flex-shrink-0 min-w-fit h-full">
                @if($clinic->logo)
                    <img src="{{ Storage::url($clinic->logo) }}" alt="{{ $clinic->name }} Logo" class="h-10 w-auto transition-all duration-300">
                @else
                    <div class="w-10 h-10 clinic-gradient rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-lg">{{ substr($clinic->name, 0, 1) }}</span>
                    </div>
                @endif
                <div class="hidden sm:flex flex-col justify-center h-full">
                    <h1 class="text-xl font-bold clinic-text-gradient truncate max-w-xs leading-tight">{{ $clinic->name }}</h1>
                    <span class="text-xs text-gray-500 hidden md:inline leading-tight">{{ $clinic->tagline ?? 'Professional Dental Care' }}</span>
                </div>
            </div>
            
            <!-- Links -->
            <div class="nav-items hidden md:flex items-center space-x-6 justify-center flex-1 px-4 h-full">
                <a href="#services" class="nav-link-item text-gray-600 hover:text-blue-600 font-medium flex items-center h-full">Services</a>
                <a href="#about" class="nav-link-item text-gray-600 hover:text-blue-600 font-medium flex items-center h-full">About</a>
                <a href="#contact" class="nav-link-item text-gray-600 hover:text-blue-600 font-medium flex items-center h-full">Contact</a>
            </div>
            
            <!-- Buttons -->
            <div class="nav-buttons flex items-center space-x-3 sm:space-x-4 slide-down flex-shrink-0 h-full">
                <a href="{{ route('login') }}" class="staff-login text-gray-600 hover:text-gray-900 transition-colors font-medium text-sm sm:text-base hidden md:flex whitespace-nowrap items-center h-full">Staff Login</a>
                <a href="#appointment" class="hidden md:flex clinic-gradient text-white px-4 py-2 rounded-lg hover:opacity-90 transition-all shadow-lg font-medium text-sm whitespace-nowrap items-center h-full">
                    Book Appointment
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 clinic-bg relative overflow-hidden min-h-screen flex items-center">
        <div class="absolute inset-0 clinic-pattern opacity-30"></div>
        
        <!-- Floating Shapes -->
        <div class="absolute top-20 left-10 w-32 h-32 bg-blue-200 shape-1 opacity-20 floating"></div>
        <div class="absolute bottom-20 right-10 w-24 h-24 bg-indigo-200 shape-2 opacity-30 floating" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 right-1/4 w-16 h-16 bg-purple-200 rounded-full opacity-25 floating" style="animation-delay: 4s;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-8 slide-up">
                    <div class="inline-flex items-center px-6 py-3 glass-card rounded-full shadow-lg">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-700">{{ $clinic->city ?? 'Nepal' }} • Professional Dental Care</span>
                    </div>
                    
                    <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                        Welcome to
                        <span class="clinic-text-gradient block">{{ $clinic->name }}</span>
                    </h1>
                    
                    <p class="text-xl text-gray-600 leading-relaxed">
                        {{ $clinic->description ?? 'Experience exceptional dental care with our state-of-the-art facility and expert team. Your smile is our priority.' }}
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-6">
                        <a href="#appointment" class="clinic-gradient text-white px-8 py-4 rounded-2xl text-lg font-semibold hover:opacity-90 transition-all shadow-xl text-center hover-lift">
                            📅 Book Appointment
                        </a>
                        <a href="#services" class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-2xl text-lg font-semibold hover:bg-gray-50 transition-all text-center hover-lift">
                            🦷 Our Services
                        </a>
                    </div>
                    
                    <!-- Clinic Stats -->
                    <div class="grid grid-cols-3 gap-6 pt-8">
                        <div class="text-center">
                            <div class="text-3xl font-bold clinic-text-gradient">500+</div>
                            <div class="text-sm text-gray-600">Happy Patients</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold clinic-text-gradient">10+</div>
                            <div class="text-sm text-gray-600">Years Experience</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold clinic-text-gradient">24/7</div>
                            <div class="text-sm text-gray-600">Emergency Care</div>
                        </div>
                    </div>
                </div>
                
                <!-- Clinic Showcase -->
                <div class="relative zoom-in">
                    <div class="relative w-full max-w-lg mx-auto">
                        <!-- Main Clinic Card -->
                        <div class="glass-card rounded-3xl shadow-2xl p-8 floating">
                            <div class="text-center mb-6">
                                @if($clinic->logo)
                                    <img src="{{ Storage::url($clinic->logo) }}" alt="{{ $clinic->name }}" class="h-16 w-auto mx-auto mb-4">
                                @else
                                    <div class="w-16 h-16 clinic-gradient rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <span class="text-white font-bold text-2xl">{{ substr($clinic->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <h3 class="text-xl font-bold text-gray-900">{{ $clinic->name }}</h3>
                                <p class="text-gray-600">{{ $clinic->address ?? 'Professional Dental Clinic' }}</p>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-900">Modern Equipment</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-900">Flexible Hours</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-xl">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-900">Expert Team</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Floating Elements -->
                        <div class="absolute -top-6 -right-6 glass-card rounded-2xl shadow-xl p-4 text-center floating" style="animation-delay: 1s;">
                            <div class="text-2xl">⭐</div>
                            <div class="text-sm font-bold text-gray-900">5.0</div>
                            <div class="text-xs text-gray-600">Rating</div>
                        </div>
                        
                        <div class="absolute -bottom-6 -left-6 glass-card rounded-2xl shadow-xl p-4 text-center floating" style="animation-delay: 3s;">
                            <div class="text-2xl">🏆</div>
                            <div class="text-sm font-bold text-gray-900">Award</div>
                            <div class="text-xs text-gray-600">Winner</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 slide-up">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Dental Services</h2>
                <p class="text-xl text-gray-600">Comprehensive dental care for your entire family</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="group glass-card p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 hover-lift slide-up">
                    <div class="w-16 h-16 clinic-gradient rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">General Dentistry</h3>
                    <p class="text-gray-600 mb-4">Comprehensive oral health care including cleanings, fillings, and preventive treatments</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Regular Checkups</li>
                        <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Teeth Cleaning</li>
                        <li class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>Cavity Fillings</li>
                    </ul>
                </div>
                
                <div class="group glass-card p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 hover-lift slide-up" style="animation-delay: 0.2s;">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Cosmetic Dentistry</h3>
                    <p class="text-gray-600 mb-4">Enhance your smile with our advanced cosmetic dental procedures</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>Teeth Whitening</li>
                        <li class="flex items-center"><span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>Veneers</li>
                        <li class="flex items-center"><span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>Smile Makeover</li>
                    </ul>
                </div>
                
                <div class="group glass-card p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 hover-lift slide-up" style="animation-delay: 0.4s;">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Specialized Care</h3>
                    <p class="text-gray-600 mb-4">Advanced treatments for complex dental conditions</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>Root Canal</li>
                        <li class="flex items-center"><span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>Dental Implants</li>
                        <li class="flex items-center"><span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>Orthodontics</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="slide-up">
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">About {{ $clinic->name }}</h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        {{ $clinic->about ?? 'We are committed to providing exceptional dental care in a comfortable and modern environment. Our experienced team uses the latest technology to ensure the best possible outcomes for our patients.' }}
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 clinic-gradient rounded-full flex items-center justify-center mr-4">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">State-of-the-art equipment</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 clinic-gradient rounded-full flex items-center justify-center mr-4">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Experienced dental professionals</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 clinic-gradient rounded-full flex items-center justify-center mr-4">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Comfortable and relaxing atmosphere</span>
                        </div>
                    </div>
                </div>
                
                <div class="relative zoom-in">
                    <div class="glass-card rounded-3xl shadow-2xl p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Why Choose Us?</h3>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold clinic-text-gradient mb-2">10+</div>
                                <div class="text-sm text-gray-600">Years Experience</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold clinic-text-gradient mb-2">500+</div>
                                <div class="text-sm text-gray-600">Happy Patients</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold clinic-text-gradient mb-2">24/7</div>
                                <div class="text-sm text-gray-600">Emergency Care</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold clinic-text-gradient mb-2">100%</div>
                                <div class="text-sm text-gray-600">Satisfaction</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact & Appointment Section -->
    <section id="contact" class="py-20 clinic-gradient">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-white mb-6">Ready to Schedule Your Visit?</h2>
            <p class="text-xl text-blue-100 mb-8">Contact us today to book your appointment</p>
            
            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <div class="glass-card rounded-2xl p-6 text-center">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Phone</h3>
                    <p class="text-blue-100">{{ $clinic->phone ?? '+977-1-XXXXXXX' }}</p>
                </div>
                
                <div class="glass-card rounded-2xl p-6 text-center">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Email</h3>
                    <p class="text-blue-100">{{ $clinic->email }}</p>
                </div>
                
                <div class="glass-card rounded-2xl p-6 text-center">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Location</h3>
                    <p class="text-blue-100">{{ $clinic->address ?? 'Kathmandu, Nepal' }}</p>
                </div>
            </div>
            
            <a href="tel:{{ $clinic->phone }}" class="bg-white text-blue-600 px-8 py-4 rounded-2xl text-lg font-semibold hover:bg-gray-50 transition-all shadow-xl hover-lift">
                📞 Call Now to Book
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        @if($clinic->logo)
                            <img src="{{ Storage::url($clinic->logo) }}" alt="{{ $clinic->name }}" class="h-8 w-auto">
                        @else
                            <div class="w-8 h-8 clinic-gradient rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold">{{ substr($clinic->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-lg font-bold">{{ $clinic->name }}</h3>
                            <p class="text-gray-400 text-sm">Professional Dental Care</p>
                        </div>
                    </div>
                    <p class="text-gray-400">{{ $clinic->description ?? 'Your trusted dental care provider' }}</p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#services" class="hover:text-white transition-colors">Services</a></li>
                        <li><a href="#about" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#contact" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Staff Portal</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact Info</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>📍 {{ $clinic->address ?? 'Kathmandu, Nepal' }}</li>
                        <li>📞 {{ $clinic->phone ?? '+977-1-XXXXXXX' }}</li>
                        <li>✉️ {{ $clinic->email }}</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">© 2024 {{ $clinic->name }}. All rights reserved. Powered by <a href="https://abssoft.com.np" class="text-blue-400 hover:text-blue-300">ABS Soft</a> 🇳🇵</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll animation with smooth transitions
        const navbar = document.querySelector('nav');
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

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && document.querySelector(href)) {
                    e.preventDefault();
                    const element = document.querySelector(href);
                    const offsetTop = element.getBoundingClientRect().top + window.scrollY - 100;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>