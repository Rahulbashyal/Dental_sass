<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DentalCare Pro - Advanced Dental Practice Management | ABS Soft</title>
    <meta name="description" content="Professional dental clinic management solution by ABS Soft. Manage patients, appointments, billing in NPR, and reports all in one platform designed for Nepal.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        // Ensure $content is an object in case the landing page table is not migrated yet
        if (! isset($content) || is_null($content)) {
            $content = new class {
                public function __get($key) { return null; }
                public function getImageUrl($key, $default = null) { return $default ?? asset('logo.png'); }
            };
        }
    @endphp
    <style>
        :root {
            --primary-color: {{ $content->theme_primary_color ?? '#3b82f6' }};
            --secondary-color: {{ $content->theme_secondary_color ?? '#06b6d4' }};
            --accent-color: {{ $content->theme_accent_color ?? '#8b5cf6' }};
        }
        .abs-gradient { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%); }
        .abs-text-gradient { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .floating { animation: float 6s ease-in-out infinite; }
        .pulse-slow { animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        .fade-in-up { animation: fadeInUp 1s ease-out; }
        .fade-in-left { animation: fadeInLeft 1s ease-out 0.2s both; }
        .fade-in-right { animation: fadeInRight 1s ease-out 0.4s both; }
        .scale-hover { transition: transform 0.3s ease; }
        .scale-hover:hover { transform: scale(1.05); }
        .glow { box-shadow: 0 0 30px rgba(59, 130, 246, 0.4); }
        .glass { backdrop-filter: blur(20px); background: rgba(255, 255, 255, 0.1); }
        .morphism { backdrop-filter: blur(16px); background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(255, 255, 255, 0.2); }
        .card-3d { transform-style: preserve-3d; transition: transform 0.6s; }
        .card-3d:hover { transform: rotateY(5deg) rotateX(5deg); }
        
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes fadeInRight { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-100%); } }

        /* Marquee / Trusted partners */
        .marquee-container { position: relative; overflow: hidden; }
        .marquee-content { display: flex; gap: 2.5rem; align-items: center; animation: marquee 30s linear infinite; }
        .partner-item { flex: 0 0 auto; margin-right: 0; display: flex; flex-direction: column; align-items: center; text-align: center; }
        .marquee-content:hover,
        .marquee-container:focus-within .marquee-content { animation-play-state: paused; }

        /* Respect users who prefer reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .marquee-content { animation: none !important; }
        }

        /* Smaller gaps on narrow screens */
        @media (max-width: 640px) {
            .marquee-content { gap: 1.25rem; }
            .partner-item img { height: 48px; }
        }
        
        .shimmer { background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent); background-size: 200% 100%; animation: shimmer 2s infinite; }
        .hero-bg { background: radial-gradient(ellipse at top, rgba(59, 130, 246, 0.1) 0%, transparent 50%), linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); }
        .section-divider { clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%); }
        
        /* Navbar Scroll Animation */
        nav {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1) !important;
            width: 100vw !important;
            height: 10vh !important;
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
        
        nav .nav-wrapper {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100% !important;
            margin: 0 auto !important;
            padding: 0 1rem !important;
            
        }
        
        nav a.nav-link-item {
            transition: all 0.3s ease;
            position: relative;
            
            font-size: 0.95rem;
        }
        
        nav a.nav-link-item:hover {
            color: var(--primary-color);
        }
        
        nav a.nav-link-item span {
            transition: width 0.3s ease;
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
<body class="antialiased bg-gray-50 overflow-x-hidden">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-100 fixed z-50 w-full">
        <div class="nav-wrapper flex justify-between items-center py-4 px-4 sm:px-6 lg:px-8 gap-8 h-14">
            <div class="flex items-center space-x-3 fade-in-left flex-shrink-0 min-w-fit h-full">
                <img src="{{ $content->getImageUrl('company_logo') ?? asset('logo.png') }}" alt="{{ $content->company_name ?? 'ABS Soft' }} Logo" class="h-10 w-auto">
                <div class="flex flex-col justify-center h-full">
                    <h1 class="text-xl font-bold abs-text-gradient leading-tight">{{ isset($clinic) ? $clinic->name : 'DentalCare Pro' }}</h1>
                    <span class="text-xs text-gray-500 leading-tight">{{ isset($clinic) ? ($content->company_tagline ?? 'Professional Dental Care') : 'by ABS Soft' }}</span>
                </div>
            </div>
            
            <div class="hidden md:flex items-center space-x-8 justify-center flex-1 px-4 h-full">
                <a href="#features" class="nav-link-item text-gray-600 hover:text-blue-600 transition-all duration-300 font-medium relative group flex items-center h-full">
                    Features
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#pricing" class="nav-link-item text-gray-600 hover:text-blue-600 transition-all duration-300 font-medium relative group flex items-center h-full">
                    Pricing
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#about" class="nav-link-item text-gray-600 hover:text-blue-600 transition-all duration-300 font-medium relative group flex items-center h-full">
                    About
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#testimonials" class="nav-link-item text-gray-600 hover:text-blue-600 transition-all duration-300 font-medium relative group flex items-center h-full">
                    Reviews
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="https://abssoft.com.np" target="_blank" class="nav-link-item text-gray-600 hover:text-blue-600 transition-all duration-300 font-medium relative group flex items-center h-full">
                    ABS Soft
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </div>
            
            <div class="flex items-center space-x-4 fade-in-right flex-shrink-0 h-full">
                <a href="{{ route('login') }}" class="hidden md:flex text-gray-600 hover:text-gray-900 transition-colors font-medium text-sm whitespace-nowrap items-center">Login</a>
                <a href="{{ route('register') }}" class="hidden md:flex abs-gradient text-white px-4 py-2 rounded-lg hover:opacity-90 transition-all shadow-lg font-medium text-sm whitespace-nowrap items-center">
                    Start Free Trial
                </a>
                <button class="md:hidden text-gray-600 hover:text-blue-600" id="mobile-menu-btn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="md:hidden hidden bg-white border-t border-gray-100" id="mobile-menu">
            <div class="px-4 py-3 space-y-2">
                <a href="#features" class="block px-3 py-2 text-gray-600 hover:text-blue-600 font-medium rounded-lg hover:bg-gray-50">Features</a>
                <a href="#pricing" class="block px-3 py-2 text-gray-600 hover:text-blue-600 font-medium rounded-lg hover:bg-gray-50">Pricing</a>
                <a href="#about" class="block px-3 py-2 text-gray-600 hover:text-blue-600 font-medium rounded-lg hover:bg-gray-50">About</a>
                <a href="#testimonials" class="block px-3 py-2 text-gray-600 hover:text-blue-600 font-medium rounded-lg hover:bg-gray-50">Reviews</a>
                <a href="https://abssoft.com.np" target="_blank" class="block px-3 py-2 text-gray-600 hover:text-blue-600 font-medium rounded-lg hover:bg-gray-50">ABS Soft</a>
                <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-600 hover:text-gray-900 font-medium rounded-lg hover:bg-gray-50">Login</a>
                <a href="{{ route('register') }}" class="block mx-3 my-2 abs-gradient text-white px-6 py-2 rounded-lg text-center font-medium hover:opacity-90 transition-all">
                    Start Free Trial
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 hero-bg relative overflow-hidden min-h-screen flex items-center section-divider">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 left-10 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 floating"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-cyan-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 floating" style="animation-delay: 3s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 floating" style="animation-delay: 1.5s;"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-8 fade-in-up">
                    <div class="inline-flex items-center px-6 py-3 morphism rounded-full shadow-lg border border-gray-200 scale-hover">
                        <img src="{{ asset('logo.png') }}" alt="ABS Soft" class="h-6 w-auto mr-3">
                        <span class="text-sm font-medium text-gray-700">Powered by ABS Soft</span>
                        <span class="ml-3 text-xs text-blue-600 font-semibold bg-blue-100 px-2 py-1 rounded-full">🇳🇵 Made in Nepal</span>
                    </div>
                    
                    <div class="space-y-8">
                        <h1 class="hero-title text-5xl lg:text-7xl font-bold text-gray-900 leading-[1.1]">
                            {{ $content->hero_title ?? "Nepal's Most Advanced Dental Platform" }}
                        </h1>
                        
                        <p class="hero-subtitle text-xl lg:text-2xl text-gray-600 leading-[1.6] max-w-2xl">
                            {!! $content->hero_subtitle ?? 'Transform your dental clinic with our comprehensive management solution. Built by <strong class="text-blue-600">ABS Soft</strong> specifically for Nepal\'s healthcare industry.' !!}
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-6">
                        <a href="{{ route('register') }}" class="abs-gradient text-white px-10 py-5 rounded-2xl text-xl font-semibold hover:opacity-90 transition-all shadow-2xl hover:shadow-3xl text-center glow scale-hover">
                            🚀 {{ $content->hero_cta_primary ?? 'Start 14-Day Free Trial' }}
                        </a>
                        <a href="#demo" class="border-2 border-gray-300 text-gray-700 px-10 py-5 rounded-2xl text-xl font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all text-center scale-hover">
                            📺 {{ $content->hero_cta_secondary ?? 'Watch Demo' }}
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center sm:text-left">
                        <div class="flex items-center justify-center sm:justify-start space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="font-medium text-gray-700">No Setup Fee</span>
                        </div>
                        <div class="flex items-center justify-center sm:justify-start space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="font-medium text-gray-700">24/7 Support</span>
                        </div>
                        <div class="flex items-center justify-center sm:justify-start space-x-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="font-medium text-gray-700">Cancel Anytime</span>
                        </div>
                    </div>
                </div>
                
                <!-- Enhanced Circular Visualization with Floating Stats -->
                <div class="relative fade-in-right">
                    <div class="relative w-full max-w-lg mx-auto">
                        <div class="w-96 h-96 mx-auto relative">
                            <!-- Animated Outer Rings -->
                            <div class="absolute inset-0 rounded-full border-4 border-blue-200 opacity-40 floating"></div>
                            <div class="absolute inset-3 rounded-full border-3 border-indigo-300 opacity-50 floating" style="animation-delay: 1s;"></div>
                            <div class="absolute inset-6 rounded-full border-2 border-cyan-300 opacity-60 floating" style="animation-delay: 2s;"></div>
                            <div class="absolute inset-9 rounded-full border border-purple-300 opacity-70 floating" style="animation-delay: 3s;"></div>
                            
                            <!-- Enhanced Center Core -->
                            <div class="absolute inset-16 rounded-full bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-700 flex items-center justify-center shadow-2xl glow floating" style="animation-delay: 0.5s;">
                                <div class="text-center text-white relative">
                                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center mx-auto mb-4 border border-white/30">
                                        <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-auto">
                                    </div>
                                    <div class="text-4xl font-bold mb-2 shimmer">500+</div>
                                    <div class="text-sm opacity-90 font-medium">Trusted Clinics</div>
                                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-green-400 rounded-full flex items-center justify-center animate-pulse">
                                        <div class="w-3 h-3 bg-white rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Enhanced Floating Stats with More Details -->
                            <div class="absolute -top-6 left-4 morphism rounded-3xl shadow-2xl p-5 text-center min-w-32 floating card-3d border border-white/20">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-3 glow">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <div class="text-3xl font-bold text-blue-600 mb-1">50K+</div>
                                <div class="text-xs text-gray-600 mb-2">Active Patients</div>
                                <div class="flex items-center justify-center space-x-1 text-xs text-green-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                    <span>+12% growth</span>
                                </div>
                            </div>
                            
                            <div class="absolute top-12 -right-10 morphism rounded-3xl shadow-2xl p-5 text-center min-w-32 floating card-3d border border-white/20" style="animation-delay: 1s;">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-3 glow">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="text-3xl font-bold text-green-600 mb-1">2M+</div>
                                <div class="text-xs text-gray-600 mb-2">Appointments</div>
                                <div class="flex items-center justify-center space-x-1 text-xs text-green-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                    <span>+8% monthly</span>
                                </div>
                            </div>
                            
                            <div class="absolute bottom-12 -left-10 morphism rounded-3xl shadow-2xl p-5 text-center min-w-36 floating card-3d border border-white/20" style="animation-delay: 2s;">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-3 glow">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                                </div>
                                <div class="text-2xl font-bold text-purple-600 mb-1">NPR 50Cr+</div>
                                <div class="text-xs text-gray-600 mb-2">Revenue Processed</div>
                                <div class="flex items-center justify-center space-x-1 text-xs text-green-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                    <span>+25% YoY</span>
                                </div>
                            </div>
                            
                            <div class="absolute -bottom-6 right-4 morphism rounded-3xl shadow-2xl p-5 text-center min-w-28 floating card-3d border border-white/20" style="animation-delay: 3s;">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-3 glow">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="text-3xl font-bold text-yellow-600 mb-1">99.9%</div>
                                <div class="text-xs text-gray-600 mb-2">Uptime</div>
                                <div class="flex items-center justify-center space-x-1 text-xs text-green-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Reliable</span>
                                </div>
                            </div>
                            
                            <!-- Enhanced Connecting Lines with Gradients -->
                            <div class="absolute top-1/2 left-1/2 w-40 h-0.5 bg-gradient-to-r from-blue-400 via-purple-400 to-transparent transform -translate-y-1/2 -translate-x-1/2 rotate-45 opacity-40 shimmer"></div>
                            <div class="absolute top-1/2 left-1/2 w-40 h-0.5 bg-gradient-to-r from-green-400 via-cyan-400 to-transparent transform -translate-y-1/2 -translate-x-1/2 -rotate-45 opacity-40 shimmer" style="animation-delay: 1s;"></div>
                            <div class="absolute top-1/2 left-1/2 w-40 h-0.5 bg-gradient-to-r from-purple-400 via-pink-400 to-transparent transform -translate-y-1/2 -translate-x-1/2 rotate-12 opacity-40 shimmer" style="animation-delay: 2s;"></div>
                            <div class="absolute top-1/2 left-1/2 w-40 h-0.5 bg-gradient-to-r from-yellow-400 via-orange-400 to-transparent transform -translate-y-1/2 -translate-x-1/2 -rotate-12 opacity-40 shimmer" style="animation-delay: 3s;"></div>
                        </div>
                        
                        <!-- Enhanced Floating Notification -->
                        <div class="absolute -top-8 -right-8 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-2xl shadow-2xl text-sm font-bold animate-bounce border border-green-400">
                            🎉 +50 New Clinics This Month!
                        </div>
                        
                        <!-- Beautiful Ambient Elements -->
                        <div class="absolute top-8 right-8 w-6 h-6 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full opacity-60 pulse-slow glow"></div>
                        <div class="absolute bottom-16 left-8 w-8 h-8 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-full opacity-50 pulse-slow glow" style="animation-delay: 1s;"></div>
                        <div class="absolute top-32 left-2 w-4 h-4 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full opacity-70 pulse-slow glow" style="animation-delay: 2s;"></div>
                        <div class="absolute bottom-8 right-12 w-5 h-5 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full opacity-55 pulse-slow glow" style="animation-delay: 3s;"></div>
                        <div class="absolute top-1/2 right-2 w-3 h-3 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full opacity-65 pulse-slow glow" style="animation-delay: 4s;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trusted Partners  -->
    <section class="pt-16 pb-36  bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Trusted by Leading Dental Clinics</h3>
                <p class="text-gray-600">Join hundreds of satisfied dental professionals across Nepal</p>
            </div>
             <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="fade-in-up">
                    <div class="text-4xl font-bold abs-text-gradient mb-2">500+</div>
                    <div class="text-gray-600 font-medium">Trusted Clinics</div>
                </div>
                <div class="fade-in-up" style="animation-delay: 0.1s;">
                    <div class="text-4xl font-bold abs-text-gradient mb-2">50K+</div>
                    <div class="text-gray-600 font-medium">Patients Managed</div>
                </div>
                <div class="fade-in-up" style="animation-delay: 0.2s;">
                    <div class="text-4xl font-bold abs-text-gradient mb-2">2M+</div>
                    <div class="text-gray-600 font-medium">Appointments</div>
                </div>
                <div class="fade-in-up" style="animation-delay: 0.3s;">
                    <div class="text-4xl font-bold abs-text-gradient mb-2">99.9%</div>
                    <div class="text-gray-600 font-medium">Uptime</div>
                </div>
            </div>
            <div class="relative overflow-hidden mt-6 md:mt-12 lg:mt-20">
                <div class="marquee-container" tabindex="0" aria-label="Trusted clinics carousel">
                    <div class="marquee-content" role="list">
                        @if($content->trusted_partners && count($content->trusted_partners) > 0)
                            @foreach($content->trusted_partners as $partner)
                                <div class="partner-item" role="listitem">
                                    <img src="{{ $content->getPartnerImageUrl($partner['logo']) }}" alt="{{ $partner['name'] }}" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('logo.png') }}'" class="h-16 w-auto object-contain grayscale hover:grayscale-0 transition-all duration-300">
                                    <span class="text-sm font-medium text-gray-700 mt-2">{{ $partner['name'] }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="partner-item" role="listitem">
                                <div class="h-16 w-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-500 font-bold">Clinic 1</span>
                                </div>
                                <span class="text-sm font-medium text-gray-700 mt-2">Dental Care Plus</span>
                            </div>
                            <div class="partner-item" role="listitem">
                                <div class="h-16 w-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-500 font-bold">Clinic 2</span>
                                </div>
                                <span class="text-sm font-medium text-gray-700 mt-2">Smile Dental</span>
                            </div>
                            <div class="partner-item" role="listitem">
                                <div class="h-16 w-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-500 font-bold">Clinic 3</span>
                                </div>
                                <span class="text-sm font-medium text-gray-700 mt-2">Perfect Teeth</span>
                            </div>
                            <div class="partner-item" role="listitem">
                                <div class="h-16 w-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-500 font-bold">Clinic 4</span>
                                </div>
                                <span class="text-sm font-medium text-gray-700 mt-2">Bright Smile</span>
                            </div>
                            <div class="partner-item" role="listitem">
                                <div class="h-16 w-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-500 font-bold">Clinic 5</span>
                                </div>
                                <span class="text-sm font-medium text-gray-700 mt-2">Dental Excellence</span>
                            </div>
                            <div class="partner-item" role="listitem">
                                <div class="h-16 w-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-500 font-bold">Clinic 6</span>
                                </div>
                                <span class="text-sm font-medium text-gray-700 mt-2">Modern Dentistry</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <br>
            
        </div>
    </section>

        <!-- About Section -->
    <section id="about" class="py-16 bg-gradient-to-br from-blue-50 via-white to-cyan-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Section Header -->
            <div class="text-center mb-12 fade-in-up">
                <div class="inline-flex items-center px-4 py-2 morphism rounded-full shadow-lg mb-4">
                    <span class="mr-2 text-lg">🇳🇵</span>
                    <span class="font-semibold text-gray-800">Built for Nepal</span>
                    <div class="ml-3 w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                    <span class="abs-text-gradient shimmer">{{ $content->about_title ?? "Designed for Nepal's Healthcare Excellence" }}</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    {{ $content->about_description ?? "Understanding Nepal's unique healthcare challenges, we've built a comprehensive platform with local language support, NPR billing, and full regulatory compliance." }}
                </p>
            </div>
            
            <!-- Enhanced Content Grid -->
            <div class="grid lg:grid-cols-3 gap-8 mb-12">
                <!-- Nepal-Specific Features -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="group bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-100 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                                </div>
                                <h4 class="font-bold text-gray-900">Nepali Language</h4>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">Complete interface with Bikram Sambat calendar integration</p>
                            <div class="flex items-center text-xs text-green-600">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Full Nepali support</span>
                            </div>
                        </div>
                        
                        <div class="group bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                                </div>
                                <h4 class="font-bold text-gray-900">NPR Currency</h4>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">Local banking integration & tax compliance</p>
                            <div class="flex items-center text-xs text-blue-600">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>All major banks supported</span>
                            </div>
                        </div>
                        
                        <div class="group bg-gradient-to-br from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-100 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <h4 class="font-bold text-gray-900">SMS Integration</h4>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">Nepali SMS services for notifications</p>
                            <div class="flex items-center text-xs text-purple-600">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Local telecom partners</span>
                            </div>
                        </div>
                        
                        <div class="group bg-gradient-to-br from-yellow-50 to-orange-50 p-6 rounded-2xl border border-yellow-100 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <h4 class="font-bold text-gray-900">Compliance</h4>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">Full regulatory compliance for Nepal</p>
                            <div class="flex items-center text-xs text-yellow-600">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Government approved</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Success Metrics -->
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-gray-100">
                    <div class="text-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Trusted Nationwide</h3>
                        <p class="text-gray-600 text-sm">500+ clinics across Nepal</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <div class="text-2xl font-bold text-blue-600 mb-1">50K+</div>
                            <div class="text-xs text-gray-600 mb-2">Active Patients</div>
                            <div class="w-full bg-blue-200 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                        
                        <div class="text-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-100">
                            <div class="text-2xl font-bold text-green-600 mb-1">2M+</div>
                            <div class="text-xs text-gray-600 mb-2">Appointments</div>
                            <div class="w-full bg-green-200 rounded-full h-1.5">
                                <div class="bg-green-500 h-1.5 rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                        
                        <div class="text-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <div class="text-lg font-bold text-purple-600 mb-1">NPR 50Cr+</div>
                            <div class="text-xs text-gray-600 mb-2">Revenue Processed</div>
                            <div class="w-full bg-purple-200 rounded-full h-1.5">
                                <div class="bg-purple-500 h-1.5 rounded-full" style="width: 78%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-6 p-3 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl">
                        <div class="flex items-center justify-center space-x-2 text-xs text-gray-600">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            <span>Growing by 50+ clinics monthly</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    @if($content->gallery_images && count($content->gallery_images) > 0)
    <section class="py-16 bg-gradient-to-br from-gray-50 to-blue-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 fade-in-up">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Our Gallery</h2>
                <p class="text-lg text-gray-600">Take a look at our facilities and work</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($content->gallery_images as $index => $image)
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 fade-in-up" style="animation-delay: {{ $index * 0.1 }}s;">
                        <img src="{{ $content->getGalleryImageUrl($image) }}" alt="Gallery Image {{ $index + 1 }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    
    <!-- Features Section -->
    <section id="features" class="py-16 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-12 fade-in-up">
                <div class="inline-flex items-center px-4 py-2 morphism rounded-full shadow-lg mb-4">
                    <span class="mr-2 text-lg">⚡</span>
                    <span class="font-semibold text-gray-800">Advanced Technology</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                    <span class="block">Everything Your</span>
                    <span class="abs-text-gradient shimmer">Dental Practice Needs</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Comprehensive tools designed by ABS Soft specifically for Nepal's dental industry
                </p>
            </div>
            
            <!-- Enhanced Feature Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                <div class="group bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Smart Appointments</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Online booking with Nepali calendar integration and automated SMS reminders</p>
                    <div class="space-y-2">
                        <div class="flex items-center text-xs text-blue-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Bikram Sambat calendar</span>
                        </div>
                        <div class="flex items-center text-xs text-blue-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>SMS notifications</span>
                        </div>
                        <div class="flex items-center text-xs text-blue-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Online patient booking</span>
                        </div>
                    </div>
                </div>
                
                <div class="group bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-600 transition-colors">Patient Records</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Digital patient files with complete treatment history and medical records</p>
                    <div class="space-y-2">
                        <div class="flex items-center text-xs text-green-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Digital patient files</span>
                        </div>
                        <div class="flex items-center text-xs text-green-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Treatment history tracking</span>
                        </div>
                        <div class="flex items-center text-xs text-green-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Medical record storage</span>
                        </div>
                    </div>
                </div>
                
                <div class="group bg-gradient-to-br from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors">NPR Billing</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Professional invoicing and payment tracking in Nepali Rupees</p>
                    <div class="space-y-2">
                        <div class="flex items-center text-xs text-purple-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>NPR currency support</span>
                        </div>
                        <div class="flex items-center text-xs text-purple-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Tax compliance</span>
                        </div>
                        <div class="flex items-center text-xs text-purple-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Payment tracking</span>
                        </div>
                    </div>
                </div>
                
                <div class="group bg-gradient-to-br from-yellow-50 to-orange-50 p-6 rounded-2xl border border-yellow-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-yellow-600 transition-colors">Staff Management</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Role-based access control for dentists, receptionists, and staff</p>
                    <div class="space-y-2">
                        <div class="flex items-center text-xs text-yellow-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Multi-role system</span>
                        </div>
                        <div class="flex items-center text-xs text-yellow-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Permission control</span>
                        </div>
                        <div class="flex items-center text-xs text-yellow-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Activity tracking</span>
                        </div>
                    </div>
                </div>
                
                <div class="group bg-gradient-to-br from-indigo-50 to-blue-50 p-6 rounded-2xl border border-indigo-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">Analytics & Reports</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Detailed reports on revenue, performance, and clinic analytics</p>
                    <div class="space-y-2">
                        <div class="flex items-center text-xs text-indigo-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Revenue analytics</span>
                        </div>
                        <div class="flex items-center text-xs text-indigo-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Performance metrics</span>
                        </div>
                        <div class="flex items-center text-xs text-indigo-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Custom reports</span>
                        </div>
                    </div>
                </div>
                
                <div class="group bg-gradient-to-br from-red-50 to-rose-50 p-6 rounded-2xl border border-red-100 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-red-600 transition-colors">Security & Compliance</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Bank-level security with data backup and privacy compliance</p>
                    <div class="space-y-2">
                        <div class="flex items-center text-xs text-red-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>SSL encryption</span>
                        </div>
                        <div class="flex items-center text-xs text-red-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Daily backups</span>
                        </div>
                        <div class="flex items-center text-xs text-red-600">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>GDPR compliant</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Features Banner -->
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl shadow-lg p-8 border border-gray-200">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Plus Many More Features</h3>
                    <p class="text-gray-600">Everything you need to run a successful dental practice in Nepal</p>
                </div>
                
                <div class="grid md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm mb-1">Treatment Plans</h4>
                        <p class="text-gray-600 text-xs">Detailed treatment planning</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm mb-1">Inventory</h4>
                        <p class="text-gray-600 text-xs">Stock management</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 19.718l8.485-8.485a2 2 0 012.828 0l1.414 1.414a2 2 0 010 2.828l-8.485 8.485A2 2 0 017.696 24H4a1 1 0 01-1-1v-3.696a2 2 0 01.586-1.414z"></path></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm mb-1">Notifications</h4>
                        <p class="text-gray-600 text-xs">Smart reminders</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm mb-1">Settings</h4>
                        <p class="text-gray-600 text-xs">Customizable options</p>
                    </div>
                </div>
            </div>
        </div>
    </section>    <!-- Testimonials Section -->
    <section id="testimonials" class="py-24 bg-gradient-to-br from-gray-50 to-blue-50 relative overflow-hidden section-divider">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-20 fade-in-up">
                <div class="inline-flex items-center px-6 py-3 morphism rounded-full shadow-lg mb-8">
                    ⭐ Customer Success Stories
                </div>
                <h2 class="text-5xl font-bold text-gray-900 mb-6">What Our Clients Say</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Hear from dental professionals across Nepal</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="morphism p-8 rounded-3xl shadow-xl card-3d fade-in-up">
                    <div class="flex items-center mb-6">
                        <div class="flex text-yellow-400 mr-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <span class="text-sm text-gray-600 font-medium">5.0 Rating</span>
                    </div>
                    <blockquote class="text-gray-600 mb-8 text-lg leading-relaxed italic">
                        "This platform has completely transformed how we manage our dental clinic. The Nepali calendar integration and SMS notifications have made patient management so much easier."
                    </blockquote>
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center mr-4 glow">
                            <span class="text-white font-bold text-lg">DR</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">Dr. Rajesh Shrestha</h4>
                            <p class="text-gray-600">Smile Dental Clinic, Kathmandu</p>
                        </div>
                    </div>
                </div>
                
                <div class="morphism p-8 rounded-3xl shadow-xl card-3d fade-in-up" style="animation-delay: 0.1s;">
                    <div class="flex items-center mb-6">
                        <div class="flex text-yellow-400 mr-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <span class="text-sm text-gray-600 font-medium">5.0 Rating</span>
                    </div>
                    <blockquote class="text-gray-600 mb-8 text-lg leading-relaxed italic">
                        "The NPR billing system and automated invoicing has saved us hours every week. Our revenue tracking is now crystal clear and professional."
                    </blockquote>
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mr-4 glow">
                            <span class="text-white font-bold text-lg">SP</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">Sita Poudel</h4>
                            <p class="text-gray-600">Clinic Manager, Pokhara Dental Care</p>
                        </div>
                    </div>
                </div>
                
                <div class="morphism p-8 rounded-3xl shadow-xl card-3d fade-in-up" style="animation-delay: 0.2s;">
                    <div class="flex items-center mb-6">
                        <div class="flex text-yellow-400 mr-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <span class="text-sm text-gray-600 font-medium">5.0 Rating</span>
                    </div>
                    <blockquote class="text-gray-600 mb-8 text-lg leading-relaxed italic">
                        "Multi-location support has been a game-changer for our dental chain. We can now manage all our clinics from one central dashboard."
                    </blockquote>
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center mr-4 glow">
                            <span class="text-white font-bold text-lg">AK</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">Dr. Anil KC</h4>
                            <p class="text-gray-600">Director, Nepal Dental Chain</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>    <!-- Pricing Section -->
    <section id="pricing" class="py-16 bg-gradient-to-br from-gray-50 via-white to-blue-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Compact Header -->
            <div class="text-center mb-12 fade-in-up">
                <div class="inline-flex items-center px-4 py-2 morphism rounded-full shadow-lg mb-4">
                    <span class="mr-2 text-lg">💎</span>
                    <span class="font-semibold text-gray-800">Nepal's Best Value</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3 leading-tight">
                    <span class="block">Simple, Transparent</span>
                    <span class="abs-text-gradient shimmer">Pricing</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">All plans include 14-day free trial • No setup fees • Cancel anytime</p>
            </div>
            
            <!-- Enhanced Pricing Cards -->
            <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto mb-12">
                <!-- Basic Plan -->
                <div class="group morphism rounded-2xl shadow-lg hover:shadow-2xl p-6 card-3d fade-in-up transition-all duration-500">
                    <div class="text-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Basic</h3>
                        <p class="text-sm text-gray-600 mb-4">Perfect for small clinics</p>
                        <div class="mb-4">
                            <span class="text-3xl font-bold text-gray-900">NPR 3,500</span>
                            <span class="text-gray-600">/month</span>
                            <div class="text-xs text-green-600 mt-1">Save NPR 8,400 annually</div>
                        </div>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-green-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span><strong>3 staff</strong> members</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-green-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span><strong>500 patients</strong> records</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-green-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Basic scheduling & NPR billing</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-green-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Nepali calendar integration</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-green-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Email support</span>
                        </div>
                    </div>
                    
                    <button class="w-full bg-gray-900 text-white py-3 px-4 rounded-xl font-semibold hover:bg-gray-800 transition-all shadow-lg scale-hover text-sm">
                        🚀 Start Free Trial
                    </button>
                </div>

                <!-- Professional Plan -->
                <div class="group morphism rounded-2xl shadow-2xl p-6 border-2 border-blue-300 relative transform scale-105 card-3d fade-in-up transition-all duration-500" style="animation-delay: 0.1s;">
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                        <span class="abs-gradient text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-lg glow">
                            🏆 MOST POPULAR
                        </span>
                    </div>
                    
                    <div class="text-center mb-6 mt-2">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Professional</h3>
                        <p class="text-sm text-gray-600 mb-4">For growing practices</p>
                        <div class="mb-4">
                            <span class="text-3xl font-bold abs-text-gradient">NPR 7,000</span>
                            <span class="text-gray-600">/month</span>
                            <div class="text-xs text-blue-600 mt-1">Save NPR 16,800 annually</div>
                        </div>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-blue-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span><strong>10 staff</strong> members</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-blue-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span><strong>2,000 patients</strong> records</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-blue-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Advanced billing & SMS alerts</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-blue-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Treatment plans & inventory</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-blue-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Priority phone support</span>
                        </div>
                    </div>
                    
                    <button class="w-full abs-gradient text-white py-3 px-4 rounded-xl font-semibold hover:opacity-90 transition-all shadow-lg glow scale-hover text-sm">
                        🚀 Start Free Trial
                    </button>
                </div>

                <!-- Enterprise Plan -->
                <div class="group morphism rounded-2xl shadow-lg hover:shadow-2xl p-6 card-3d fade-in-up transition-all duration-500" style="animation-delay: 0.2s;">
                    <div class="text-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Enterprise</h3>
                        <p class="text-sm text-gray-600 mb-4">For large clinics & chains</p>
                        <div class="mb-4">
                            <span class="text-3xl font-bold text-gray-900">NPR 12,000</span>
                            <span class="text-gray-600">/month</span>
                            <div class="text-xs text-purple-600 mt-1">Custom pricing available</div>
                        </div>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-purple-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span><strong>Unlimited</strong> staff & patients</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-purple-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Multi-location support</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-purple-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Custom branding & white-label</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-purple-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>API access & integrations</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-4 h-4 bg-purple-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Dedicated account manager</span>
                        </div>
                    </div>
                    
                    <button class="w-full bg-gray-900 text-white py-3 px-4 rounded-xl font-semibold hover:bg-gray-800 transition-all shadow-lg scale-hover text-sm">
                        📞 Contact Sales
                    </button>
                </div>
            </div>
            
            <!-- Value Proposition Banner -->
            <div class="morphism rounded-2xl shadow-xl p-6 border border-gray-200 fade-in-up">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Why Choose DentalCare Pro?</h3>
                    <p class="text-gray-600">Built specifically for Nepal's dental industry with local expertise</p>
                </div>
                
                <div class="grid md:grid-cols-4 gap-6">
                    <div class="text-center group">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h4 class="font-bold text-gray-900 text-sm mb-1">14-Day Free Trial</h4>
                        <p class="text-gray-600 text-xs">No credit card required</p>
                    </div>
                    
                    <div class="text-center group">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="font-bold text-gray-900 text-sm mb-1">24/7 Support</h4>
                        <p class="text-gray-600 text-xs">In Nepali language</p>
                    </div>
                    
                    <div class="text-center group">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h4 class="font-bold text-gray-900 text-sm mb-1">Bank-Level Security</h4>
                        <p class="text-gray-600 text-xs">SSL encrypted & compliant</p>
                    </div>
                    
                    <div class="text-center group">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <span class="text-white font-bold text-lg">🇳🇵</span>
                        </div>
                        <h4 class="font-bold text-gray-900 text-sm mb-1">Made for Nepal</h4>
                        <p class="text-gray-600 text-xs">NPR billing & local features</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-24 abs-gradient relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-20 fade-in-up">
                <h2 class="text-5xl md:text-6xl font-bold text-white mb-6">
                    {{ $content->contact_title ?? 'Ready to Transform Your Practice?' }}
                </h2>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">{{ $content->contact_subtitle ?? 'Join hundreds of dental clinics across Nepal' }}</p>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="text-white fade-in-left">
                    <h3 class="text-3xl font-bold mb-8">Get Started Today</h3>
                    <div class="space-y-6 mb-10">
                        <div class="flex items-center space-x-4 p-4 glass rounded-2xl">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-lg">14-day free trial - no credit card required</span>
                        </div>
                        <div class="flex items-center space-x-4 p-4 glass rounded-2xl">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Free setup and data migration</span>
                        </div>
                        <div class="flex items-center space-x-4 p-4 glass rounded-2xl">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-lg">24/7 customer support in Nepali</span>
                        </div>
                    </div>
                </div>
                
                <div class="fade-in-right">
                    <div class="morphism p-10 rounded-3xl shadow-2xl">
                        <h3 class="text-3xl font-bold text-gray-900 mb-8 text-center">Start Your Free Trial</h3>
                        <form class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Clinic Name</label>
                                    <input type="text" class="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Enter your clinic name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Your Name</label>
                                    <input type="text" class="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Enter your full name">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Email Address</label>
                                <input type="email" class="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Enter your email">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Phone Number</label>
                                <input type="tel" class="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Enter your phone number">
                            </div>
                            <button type="submit" class="w-full abs-gradient text-white py-4 px-6 rounded-xl font-semibold hover:opacity-90 transition-all shadow-lg glow scale-hover text-lg">
                                🚀 Start Free Trial Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-20 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid md:grid-cols-5 gap-12">
                <div class="md:col-span-2 fade-in-up">
                    <div class="flex items-center space-x-4 mb-8">
                        <img src="{{ asset('logo.png') }}" alt="ABS Soft Logo" class="h-12 w-auto">
                        <div>
                            <h3 class="text-2xl font-bold">ABS Soft</h3>
                            <p class="text-gray-400">Software Solutions</p>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-8 leading-relaxed text-lg">
                        {{ $content->footer_description ?? 'DentalCare Pro is proudly developed by ABS Soft, Nepal\'s leading software development company.' }}
                    </p>
                </div>
                
                <div class="fade-in-up" style="animation-delay: 0.1s;">
                    <h4 class="text-xl font-semibold mb-6 text-white">Product</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#pricing" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Demo</a></li>
                    </ul>
                </div>
                
                <div class="fade-in-up" style="animation-delay: 0.2s;">
                    <h4 class="text-xl font-semibold mb-6 text-white">Support</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#contact" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div class="fade-in-up" style="animation-delay: 0.3s;">
                    <h4 class="text-xl font-semibold mb-6 text-white">Company</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li><a href="https://abssoft.com.np" target="_blank" class="hover:text-white transition-colors">About ABS Soft</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-16 pt-10">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-lg mb-4 md:mb-0">
                        {{ $content->footer_copyright ?? '© 2024 ABS Soft. All rights reserved. DentalCare Pro ' }}
                    </p>
                    <div class="flex items-center space-x-6">
                        <span class="text-gray-400">Powered by</span>
                        <div class="flex items-center space-x-3 bg-gray-800 px-4 py-2 rounded-xl">
                            <img src="{{ asset('logo.png') }}" alt="ABS Soft" class="h-6 w-auto">
                            <span class="text-white font-semibold">ABS Soft</span>
                        </div>
                    </div>
                </div>
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

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Close mobile menu when clicking on a link
        if (mobileMenu) {
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                });
            });
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
