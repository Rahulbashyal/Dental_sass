<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $content->meta_title ?? $clinic->name . ' | Premium Dental Mastery' }}</title>
    <meta name="description" content="{{ $content->meta_description ?? 'Elite dental services at ' . $clinic->name }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Three.js & Vanta.js for living background -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
    <!-- GSAP for advanced sequencing -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        :root {
            --primary: #0ea5e9;
            --secondary: #2563eb;
            --bg-dark: #070b19;
            --card-glass: rgba(15, 23, 42, 0.4);
            --border-glass: rgba(255, 255, 255, 0.08);
        }
        
        body {
            background-color: var(--bg-dark);
            color: #f8fafc;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
            cursor: none; /* Hide default cursor to use custom one */
        }

        /* Custom Animated Cursor */
        .cursor-dot,
        .cursor-outline {
            position: fixed;
            top: 0; left: 0;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            z-index: 9999;
            pointer-events: none;
        }
        .cursor-dot {
            width: 8px; height: 8px;
            background-color: var(--primary);
        }
        .cursor-outline {
            width: 40px; height: 40px;
            border: 1px solid rgba(14, 165, 233, 0.5);
            transition: width 0.2s, height 0.2s, border-color 0.2s;
        }
        
        /* Interactive Hover State for Cursor */
        a:hover ~ .cursor-outline,
        button:hover ~ .cursor-outline,
        .cursor-pointer:hover ~ .cursor-outline {
            width: 60px; height: 60px;
            border-color: rgba(14, 165, 233, 1);
            background-color: rgba(14, 165, 233, 0.1);
        }

        .glass {
            background: var(--card-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-glass);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .text-glow {
            text-shadow: 0 0 25px rgba(14, 165, 233, 0.5);
        }

        /* Ambient Background Orbs */
        .ambient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 0;
            pointer-events: none;
        }
        .orb-1 {
            width: 600px; height: 600px;
            background: rgba(14, 165, 233, 0.15);
            top: -100px; right: -100px;
            animation: float 10s ease-in-out infinite alternate;
        }
        .orb-2 {
            width: 500px; height: 500px;
            background: rgba(37, 99, 235, 0.1);
            bottom: -100px; left: -100px;
            animation: float 12s ease-in-out infinite alternate-reverse;
        }

        @keyframes float {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(50px) scale(1.1); }
        }

        /* Subtle Glow Animation */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 25px rgba(14, 165, 233, 0.5); }
            50% { box-shadow: 0 0 45px rgba(14, 165, 233, 0.8), 0 0 20px rgba(14, 165, 233, 0.4) inset; }
        }
        .animate-pulse-glow {
            animation: pulse-glow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Glass Shine Hover Effect */
        .glass-shine {
            position: relative;
            overflow: hidden;
        }
        .glass-shine::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 50%; height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.2), transparent);
            transform: skewX(-20deg);
            transition: 0.7s;
            z-index: 10;
        }
        .glass-shine:hover::before {
            left: 200%;
        }

        /* Parallax Container */
        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        /* Image comparison slider */
        .comparison-slider {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 2rem;
            aspect-ratio: 16/9;
        }
        .comparison-slider img {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .comparison-overlay {
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            width: 50%; /* Default 50% */
            overflow: hidden;
            border-right: 2px solid white;
            box-shadow: 2px 0 15px rgba(0,0,0,0.5);
            transition: width 0.1s;
            z-index: 10;
        }
        .comparison-overlay img {
            width: 200%; /* Important constraint */
            max-width: unset;
        }
        .slider-handle {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px; height: 40px;
            background: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            z-index: 20;
            pointer-events: none;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: rgba(14, 165, 233, 0.3); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(14, 165, 233, 0.5); }
    </style>
</head>
<body class="antialiased selection:bg-blue-500/30">

    <!-- Ambient Orbs -->
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2 fixed"></div>

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 px-6 py-4" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-8 py-4 transition-all duration-300 rounded-2xl"
             :class="scrolled ? 'glass border border-white/10 shadow-lg' : 'bg-transparent'">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-blue-500 to-cyan-400 p-0.5 shadow-lg shadow-blue-500/20">
                    <img src="{{ $clinic->logo ? Storage::url($clinic->logo) : 'https://images.pexels.com/photos/305568/pexels-photo-305568.jpeg?auto=compress&cs=tinysrgb&w=100&h=100&fit=crop' }}" class="w-full h-full rounded-[10px] object-cover bg-slate-900">
                </div>
                <span class="text-xl font-black tracking-tighter text-white uppercase">{{ $clinic->name }}</span>
            </div>
            
            <div class="hidden lg:flex items-center gap-6 text-[10px] font-bold uppercase tracking-widest text-slate-300">
                <a href="#hero" class="hover:text-cyan-400 transition-colors">Home</a>
                <a href="#services" class="hover:text-cyan-400 transition-colors">Services</a>
                <a href="#before-after" class="hover:text-cyan-400 transition-colors">Results</a>
                <a href="#team" class="hover:text-cyan-400 transition-colors">Experts</a>
                <a href="#testimonials" class="hover:text-cyan-400 transition-colors">Reviews</a>
                <a href="#faq" class="hover:text-cyan-400 transition-colors">FAQ</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('clinic.book', $clinic->slug) }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:scale-105 hover:shadow-lg hover:shadow-cyan-500/50 transition-all duration-300 animate-pulse-glow glass-shine">
                    Book Consult
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Vanta.js Living Background -->
    <section id="hero" class="min-h-screen pt-32 pb-20 px-6 flex items-center relative z-10 overflow-hidden">
        <!-- Vanta Canvas Target -->
        <div id="vanta-bg" class="absolute inset-0 z-0"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-slate-950/50 to-slate-950 z-0 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto w-full grid lg:grid-cols-2 gap-16 items-center relative z-10">
            <div class="hero-content">
                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full glass mb-8 border border-blue-500/30 hero-stagger">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-cyan-500"></span>
                    </span>
                    <span class="text-blue-300 text-[10px] font-black uppercase tracking-widest">Advanced Dental Care</span>
                </div>
                <h1 class="text-6xl md:text-8xl font-black leading-[0.9] tracking-tighter text-white mb-6 text-glow hero-stagger">
                    {{ $content->hero_title ?? 'DESIGNING YOUR BEST SMILE' }}
                </h1>
                <p class="text-slate-300 text-lg md:text-xl font-light leading-relaxed max-w-xl mb-10 border-l-4 border-cyan-500 pl-6 hero-stagger">
                    {{ $content->hero_subtitle ?? 'Experience state-of-the-art dentistry in a relaxing, premium environment.' }}
                </p>
                <div class="flex flex-wrap gap-4 hero-stagger mt-8">
                    <a href="{{ route('clinic.book', $clinic->slug) }}" class="px-8 py-4 bg-white text-slate-900 text-sm font-black uppercase tracking-widest rounded-xl shadow-[0_0_30px_rgba(255,255,255,0.2)] hover:bg-slate-100 transition-all duration-300 flex items-center gap-3 transform hover:-translate-y-1 glass-shine hover:shadow-[0_0_40px_rgba(255,255,255,0.4)]">
                        <i class="fas fa-calendar-check text-blue-600 border-r border-slate-300 pr-3"></i> Schedule Visit
                    </a>
                    <a href="#services" class="px-8 py-4 glass text-white text-sm font-black uppercase tracking-widest rounded-xl hover:bg-white/10 transition-colors glass-shine">
                        View Services
                    </a>
                </div>
            </div>

            <div class="relative hidden lg:block" data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200">
                <!-- Floating Glass Card 1 -->
                <div class="absolute -top-10 -left-10 glass p-6 rounded-3xl z-20 animate-[float_8s_ease-in-out_infinite]">
                    <div class="flex gap-4 items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center text-cyan-400 text-xl"><i class="fas fa-microscope"></i></div>
                        <div>
                            <p class="text-white font-bold text-sm">3D Imaging</p>
                            <p class="text-slate-400 text-xs">Precise Diagnostics</p>
                        </div>
                    </div>
                </div>
                <!-- Main Image -->
                <img src="https://images.pexels.com/photos/3845653/pexels-photo-3845653.jpeg?auto=compress&cs=tinysrgb&w=800" class="rounded-[2.5rem] border border-white/10 shadow-2xl relative z-10 w-full h-[600px] object-cover">
                <!-- Floating Glass Card 2 -->
                <div class="absolute -bottom-10 -right-10 glass p-6 rounded-3xl z-20 animate-[float_10s_ease-in-out_infinite_reverse]">
                    <div class="flex gap-4 items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center text-cyan-400 text-xl"><i class="fas fa-tooth"></i></div>
                        <div>
                            <p class="text-white font-bold text-sm">Painless Surgery</p>
                            <p class="text-slate-400 text-xs">Advanced Comfort</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-32 px-6 relative z-10">
        <div class="max-w-7xl mx-auto">
            <div class="text-center max-w-3xl mx-auto mb-20" data-aos="fade-up">
                <h2 class="text-sm font-black text-cyan-400 uppercase tracking-widest mb-4">Mastery in Dentistry</h2>
                <h3 class="text-4xl md:text-5xl font-black text-white tracking-tighter">PREMIUM DENTAL SERVICES</h3>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-cyan-400 mx-auto mt-6 rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $demoServices = [
                        ['n' => 'Cosmetic Veneers', 'i' => 'fa-gem', 'd' => 'Ultra-thin porcelain veneers to correct chips, gaps, and stains for a perfect Hollywood smile.'],
                        ['n' => 'Dental Implants', 'i' => 'fa-screwdriver', 'd' => 'Permanent titanium teeth replacements that look, feel, and function just like your natural teeth.'],
                        ['n' => 'Invisalign Orthodontics', 'i' => 'fa-teeth-open', 'd' => 'Clear, removable aligners to straighten your teeth discreetly and comfortably without metal brackets.'],
                        ['n' => 'Laser Whitening', 'i' => 'fa-magic', 'd' => 'Advanced laser technology to instantly brighten your smile several shades in just one visit.'],
                        ['n' => 'Root Canal Therapy', 'i' => 'fa-tooth', 'd' => 'Painless endodontic treatment utilizing 3D microscopic precision to save infected teeth.'],
                        ['n' => 'Pediatric Care', 'i' => 'fa-child', 'd' => 'Gentle, anxiety-free dental experiences specifically tailored for children.'],
                    ];
                    $actualServices = !empty($services) ? $services : $demoServices;
                @endphp

                @foreach($actualServices as $idx => $s)
                    <div class="glass p-8 rounded-3xl hover:-translate-y-3 hover:shadow-[0_10px_40px_rgba(14,165,233,0.15)] hover:border-cyan-500/30 transition-all duration-500 group cursor-pointer glass-shine" data-aos="fade-up" data-aos-delay="{{ $idx * 100 }}">
                        <div class="w-16 h-16 rounded-2xl bg-slate-800 border border-slate-700 flex items-center justify-center text-2xl text-cyan-400 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="fas {{ $s['icon'] ?? $s['i'] ?? 'fa-stethoscope' }}"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3">{{ $s['name'] ?? $s['n'] ?? $s['title'] ?? 'Service' }}</h4>
                        <p class="text-slate-400 text-sm leading-relaxed mb-6">{{ $s['description'] ?? $s['d'] ?? 'Premium dental procedure utilizing advanced technology.' }}</p>
                        <a href="javascript:void(0)" class="text-cyan-400 text-xs font-bold uppercase tracking-widest flex items-center gap-2 group-hover:gap-4 transition-all">
                            Learn More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Before & After Comparison -->
    <section id="before-after" class="py-32 px-6 bg-slate-900/50 relative z-10 border-y border-white/5">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <h2 class="text-sm font-black text-cyan-400 uppercase tracking-widest mb-4">Transformations</h2>
                <h3 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-6">SEE THE DIFFERENCE</h3>
                <p class="text-slate-400 text-lg leading-relaxed mb-10">
                    Our elite cosmetic procedures produce life-changing results. Drag the slider to witness real patient transformations achieved through veneers and whitening.
                </p>
                <div class="flex gap-4">
                    <div class="glass px-6 py-4 rounded-xl text-center border-l-2 border-l-slate-500">
                        <span class="block text-2xl font-black text-white">Before</span>
                        <span class="text-slate-500 text-xs uppercase tracking-widest">Stained / Gaps</span>
                    </div>
                    <div class="glass px-6 py-4 rounded-xl text-center border-l-2 border-l-cyan-400">
                        <span class="block text-2xl font-black text-white">After</span>
                        <span class="text-cyan-500 text-xs uppercase tracking-widest">Veneers / Whitening</span>
                    </div>
                </div>
            </div>

            <div class="relative rounded-3xl overflow-hidden glass p-2 border border-white/10" data-aos="zoom-in">
                <div class="comparison-slider" id="comp-slider" x-data x-on:mousemove="
                    let rect = $el.getBoundingClientRect();
                    let x = $event.clientX - rect.left;
                    let num = Math.min(Math.max(x / rect.width * 100, 0), 100);
                    $el.style.setProperty('--pos', num + '%');
                    $el.querySelector('.comparison-overlay').style.width = num + '%';
                    $el.querySelector('.slider-handle').style.left = num + '%';
                " x-on:touchmove="
                    let rect = $el.getBoundingClientRect();
                    let x = $event.touches[0].clientX - rect.left;
                    let num = Math.min(Math.max(x / rect.width * 100, 0), 100);
                    $el.querySelector('.comparison-overlay').style.width = num + '%';
                    $el.querySelector('.slider-handle').style.left = num + '%';
                ">
                    <!-- Base Image (After) -->
                    <img class="pointer-events-none" src="https://images.pexels.com/photos/6502025/pexels-photo-6502025.jpeg?auto=compress&cs=tinysrgb&w=1200" alt="After treatment">
                    
                    <!-- Overlay Image (Before) -->
                    <div class="comparison-overlay" style="width: 50%;">
                        <img class="pointer-events-none" src="https://images.pexels.com/photos/6502758/pexels-photo-6502758.jpeg?auto=compress&cs=tinysrgb&w=1200" alt="Before treatment" style="max-width: initial;">
                    </div>
                    
                    <!-- Handle -->
                    <div class="slider-handle">
                        <i class="fas fa-arrows-alt-h"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Clinic Gallery Parallax -->
    <section id="gallery" class="py-40 relative flex items-center justify-center parallax-bg z-10" style="background-image: linear-gradient(rgba(7, 11, 25, 0.9), rgba(7, 11, 25, 0.9)), url('https://images.pexels.com/photos/3845680/pexels-photo-3845680.jpeg?auto=compress&cs=tinysrgb&w=1920');">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-16" data-aos="fade-up">STATE-OF-THE-ART FACILITY</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach([
                    'https://images.pexels.com/photos/3845736/pexels-photo-3845736.jpeg?auto=compress&cs=tinysrgb&w=600',
                    'https://images.pexels.com/photos/3845722/pexels-photo-3845722.jpeg?auto=compress&cs=tinysrgb&w=600',
                    'https://images.pexels.com/photos/3845739/pexels-photo-3845739.jpeg?auto=compress&cs=tinysrgb&w=600',
                    'https://images.pexels.com/photos/6502029/pexels-photo-6502029.jpeg?auto=compress&cs=tinysrgb&w=600'
                ] as $img)
                    <div class="overflow-hidden rounded-2xl aspect-square" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 150 }}">
                        <img src="{{ $img }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-700 cursor-pointer border border-white/10">
                    </div>
                @endforeach
            </div>
            
            <div class="mt-16 bg-slate-900/40 backdrop-blur-md border border-white/10 rounded-3xl p-8 inline-block" data-aos="fade-up">
                <p class="text-white font-medium italic mb-2">"A dental clinic designed like a luxury lounge."</p>
                <div class="flex items-center justify-center gap-1 text-cyan-400 text-sm">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Elite Specialists -->
    <section id="team" class="py-32 px-6 relative z-10 bg-slate-950 border-t border-white/5">
        <div class="max-w-7xl mx-auto">
            <div class="text-center max-w-3xl mx-auto mb-20" data-aos="fade-up">
                <h2 class="text-sm font-black text-cyan-400 uppercase tracking-widest mb-4">{{ $content->custom_sections['team_section_title'] ?? 'Meet the Experts' }}</h2>
                <h3 class="text-4xl md:text-5xl font-black text-white tracking-tighter">ELITE SPECIALIST BOARD</h3>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-cyan-400 mx-auto mt-6 rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach([
                    ['name' => 'Dr. Alexander Webb', 'role' => 'Lead Prosthodontist', 'img' => 'https://images.pexels.com/photos/4173251/pexels-photo-4173251.jpeg?auto=compress&cs=tinysrgb&w=600'],
                    ['name' => 'Dr. Sophia Carter', 'role' => 'Cosmetic Architect', 'img' => 'https://images.pexels.com/photos/5452293/pexels-photo-5452293.jpeg?auto=compress&cs=tinysrgb&w=600'],
                    ['name' => 'Dr. Julian Pierce', 'role' => 'Master Endodontist', 'img' => 'https://images.pexels.com/photos/5215024/pexels-photo-5215024.jpeg?auto=compress&cs=tinysrgb&w=600']
                ] as $doc)
                <div class="glass rounded-3xl overflow-hidden group hover:shadow-[0_10px_40px_rgba(14,165,233,0.15)] hover:border-cyan-500/30 transition-all duration-500 border border-white/5 glass-shine" data-aos="fade-up" data-aos-delay="{{ $loop->index * 150 }}">
                    <div class="h-80 overflow-hidden relative">
                        <img src="{{ $doc['img'] }}" class="w-full h-full object-cover object-top grayscale group-hover:grayscale-0 scale-100 group-hover:scale-110 transition-all duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                    </div>
                    <div class="p-8 relative -mt-16 z-10">
                        <h4 class="text-2xl font-black text-white tracking-tight mb-1">{{ $doc['name'] }}</h4>
                        <p class="text-cyan-400 text-sm font-bold uppercase tracking-widest">{{ $doc['role'] }}</p>
                        <div class="mt-6 flex gap-3">
                            <a href="#" class="w-10 h-10 rounded-xl bg-slate-800 flex flex-col items-center justify-center text-slate-400 hover:bg-cyan-500 hover:text-white transition-all"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white text-xs font-bold uppercase border border-white/5 flex items-center gap-2 transition-colors">View Profile <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials / Client Experience -->
    <section id="testimonials" class="py-32 px-6 relative z-10 bg-[url('https://images.pexels.com/photos/3779693/pexels-photo-3779693.jpeg?auto=compress&cs=tinysrgb&w=1920')] bg-fixed bg-cover bg-center">
        <div class="absolute inset-0 bg-slate-950/90 backdrop-blur-sm"></div>
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-20" data-aos="fade-up">
                <i class="fas fa-quote-left text-4xl text-cyan-500/30 mb-6"></i>
                <h2 class="text-sm font-black text-cyan-400 uppercase tracking-widest mb-4">Patient Experiences</h2>
                <h3 class="text-4xl md:text-5xl font-black text-white tracking-tighter">SUCCESS STORIES</h3>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $demoReviews = [
                        ['n' => 'Sarah Jenkins', 'r' => '5', 't' => 'Absolutely life-changing. I had severe dental anxiety, but their futuristic clinic and painless laser tech made me feel like I was at a spa.'],
                        ['n' => 'Michael Chen', 'r' => '5', 't' => 'Got porcelain veneers installed here. The 3D modeling showed me exactly what my smile would look like before we started.'],
                        ['n' => 'Emily Thorne', 'r' => '5', 't' => 'Elite service from start to finish. If you want the absolute best dental care with zero compromises, this is the place.'],
                    ];
                    $actualReviews = !empty($testimonials) ? $testimonials : $demoReviews;
                @endphp

                @foreach(array_slice($actualReviews, 0, 3) as $idx => $review)
                <div class="glass p-10 rounded-3xl border border-blue-500/20 relative group hover:border-cyan-500/50 hover:-translate-y-2 hover:shadow-[0_10px_40px_rgba(14,165,233,0.15)] transition-all duration-500 glass-shine" data-aos="fade-up" data-aos-delay="{{ $idx * 150 }}">
                    <div class="flex gap-1 text-cyan-400 text-sm mb-6">
                        @for($i=0; $i < ($review['rating'] ?? $review['r'] ?? 5); $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                    </div>
                    <p class="text-slate-300 text-lg leading-relaxed mb-8 italic">"{{ $review['text'] ?? $review['t'] ?? 'Incredible service and outstanding results.' }}"</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-slate-800 text-white font-bold flex items-center justify-center border border-slate-600">
                            {{ substr(($review['name'] ?? $review['n'] ?? 'G'), 0, 1) }}
                        </div>
                        <div>
                            <p class="text-white font-bold">{{ $review['name'] ?? $review['n'] ?? 'Guest Patient' }}</p>
                            <p class="text-slate-500 text-xs uppercase tracking-widest">Verified Patient</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Accordion -->
    <section id="faq" class="py-32 px-6 relative z-10 border-t border-white/5">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-sm font-black text-cyan-400 uppercase tracking-widest mb-4">Patient Intelligence</h2>
                <h3 class="text-4xl font-black text-white tracking-tighter">FREQUENTLY ASKED</h3>
            </div>
            
            <div class="space-y-4" x-data="{ selected: null }">
                @foreach([
                    ['q' => 'Does laser root canal therapy actually hurt less?', 'a' => 'Yes. Biolase™ technology removes infection using light energy, which is practically painless compared to traditional drills and results in significantly faster healing times.'],
                    ['q' => 'How long do your high-end porcelain veneers last?', 'a' => 'With proper care, our elite ceramic veneers typically last between 15 to 20 years. We use highly durable, stain-resistant porcelain crafted in our in-house lab.'],
                    ['q' => 'Do you accept international luxury health insurance?', 'a' => 'Yes, we work with several global premium insurance providers. Our concierge team handles all billing and documentation directly with your provider.'],
                    ['q' => 'Can I see a 3D preview of my smile before surgery?', 'a' => 'Absolutely. We perform a complete 3D biometric scan on your first visit, generating a precise digital twin so you can approve your new smile before any physical work begins.']
                ] as $idx => $faq)
                <div class="glass border border-white/10 rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $idx * 100 }}">
                    <button @click="selected !== {{ $idx }} ? selected = {{ $idx }} : selected = null" class="w-full text-left px-8 py-6 flex justify-between items-center focus:outline-none">
                        <span class="text-white font-bold pr-8">{{ $faq['q'] }}</span>
                        <i class="fas transition-transform duration-300 text-cyan-400" :class="selected === {{ $idx }} ? 'fa-minus scale-110' : 'fa-plus'"></i>
                    </button>
                    <div class="overflow-hidden transition-all duration-300 max-h-0" x-ref="container{{ $idx }}" x-bind:style="selected === {{ $idx }} ? 'max-height: ' + $refs.container{{ $idx }}.scrollHeight + 'px' : ''">
                        <div class="px-8 pb-6 text-slate-400 text-sm leading-relaxed border-t border-white/5 pt-4">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Booking CTA Action -->
    <section id="book" class="py-24 px-6 relative z-10 border-t border-white/5 bg-gradient-to-br from-slate-900 via-slate-950 to-blue-950">
        <!-- Background Grid Pattern -->
        <div class="absolute inset-0 z-0 opacity-10" style="background-image: radial-gradient(#0ea5e9 1px, transparent 1px); background-size: 40px 40px;"></div>
        
        <div class="max-w-6xl mx-auto glass rounded-[3rem] p-8 md:p-16 border-cyan-500/30 relative z-10 overflow-hidden shadow-2xl shadow-blue-900/40" data-aos="zoom-in">
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-cyan-500/20 blur-3xl rounded-full"></div>
            
            <div class="grid md:grid-cols-2 gap-12 items-center relative z-10">
                <div>
                    <h2 class="text-sm font-black text-cyan-400 uppercase tracking-widest mb-4">Take Action Today</h2>
                    <h3 class="text-5xl md:text-6xl font-black tracking-tighter text-white mb-6 leading-none">
                        RESERVE YOUR <br>CONSULTATION
                    </h3>
                    <p class="text-slate-400 mb-10 text-lg leading-relaxed max-w-md border-l-2 border-cyan-500 pl-4">
                        Stop waiting. Step into the future of dentistry. We process bookings rapidly to get you into the chair within 48 hours.
                    </p>
                    
                    <div class="flex flex-col gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-500/20 text-cyan-400 flex items-center justify-center text-xl"><i class="fas fa-gem"></i></div>
                            <div>
                                <h4 class="text-white font-bold">Premium Environment</h4>
                                <p class="text-slate-500 text-sm">Luxury waiting lounges</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-500/20 text-cyan-400 flex items-center justify-center text-xl"><i class="fas fa-shield-alt"></i></div>
                            <div>
                                <h4 class="text-white font-bold">Guaranteed Results</h4>
                                <p class="text-slate-500 text-sm">Long-term warranty on implants</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-xl border border-white/10 rounded-3xl p-8">
                    <form action="#" method="POST" class="space-y-5">
                        <!-- Normally this form would POST to a lead/booking endpoint -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Full Name</label>
                            <input type="text" class="w-full bg-slate-950 border border-slate-800 text-white px-5 py-4 rounded-xl focus:outline-none focus:border-cyan-500 transition-colors" placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Phone Number</label>
                            <input type="tel" class="w-full bg-slate-950 border border-slate-800 text-white px-5 py-4 rounded-xl focus:outline-none focus:border-cyan-500 transition-colors" placeholder="+1 (555) 000-0000">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Treatment Interest</label>
                            <select class="w-full bg-slate-950 border border-slate-800 text-white px-5 py-4 rounded-xl focus:outline-none focus:border-cyan-500 transition-colors appearance-none">
                                <option>General Consultation</option>
                                <option>Cosmetic Veneers</option>
                                <option>Dental Implants</option>
                                <option>Invisalign</option>
                            </select>
                        </div>
                        <button type="button" onclick="window.location.href='{{ route('clinic.book', $clinic->slug) }}'" class="w-full mt-4 bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-black uppercase tracking-widest py-5 rounded-xl hover:shadow-lg hover:shadow-cyan-500/50 transition-all transform hover:-translate-y-1 animate-pulse-glow glass-shine duration-300">
                            Process Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Footer -->
    <footer class="bg-black pt-20 pb-10 px-6 relative z-10">
        <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-12 mb-16">
            <div class="md:col-span-1">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-500 to-cyan-400 p-0.5">
                        <img src="{{ $clinic->logo ? Storage::url($clinic->logo) : 'https://images.pexels.com/photos/305568/pexels-photo-305568.jpeg?auto=compress&cs=tinysrgb&w=100&h=100&fit=crop' }}" class="w-full h-full rounded-[10px] object-cover bg-slate-900">
                    </div>
                    <span class="text-xl font-black tracking-tighter text-white uppercase">{{ $clinic->name }}</span>
                </div>
                <p class="text-slate-500 text-sm mb-6">{{ $content->about_description ?? 'Elite dental care tailored to perfection.' }}</p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full glass flex items-center justify-center text-slate-400 hover:text-cyan-400 hover:bg-slate-800 transition-colors"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full glass flex items-center justify-center text-slate-400 hover:text-cyan-400 hover:bg-slate-800 transition-colors"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div>
                <h4 class="text-white font-bold mb-6">Quick Links</h4>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li><a href="#hero" class="hover:text-cyan-400 transition-colors">Home</a></li>
                    <li><a href="#services" class="hover:text-cyan-400 transition-colors">Treatments</a></li>
                    <li><a href="#before-after" class="hover:text-cyan-400 transition-colors">Results</a></li>
                    <li><a href="#gallery" class="hover:text-cyan-400 transition-colors">Our Facility</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-white font-bold mb-6">Contact</h4>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li class="flex items-start gap-3"><i class="fas fa-map-marker-alt mt-1 text-cyan-400"></i> {{ $content->contact_address ?? $clinic->address ?? '123 Health Ave, Medical City' }}</li>
                    <li class="flex items-center gap-3"><i class="fas fa-phone text-cyan-400"></i> {{ $content->contact_phone ?? $clinic->phone ?? '+1 (555) 123-4567' }}</li>
                    <li class="flex items-center gap-3"><i class="fas fa-envelope text-cyan-400"></i> {{ $content->contact_email ?? $clinic->email ?? 'contact@clinic.local' }}</li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6">Opening Hours</h4>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li class="flex justify-between border-b border-white/5 pb-2"><span>Mon - Fri</span> <span class="text-white">8:00 AM - 8:00 PM</span></li>
                    <li class="flex justify-between border-b border-white/5 pb-2"><span>Saturday</span> <span class="text-white">9:00 AM - 5:00 PM</span></li>
                    <li class="flex justify-between"><span>Sunday</span> <span class="text-cyan-400">Emergency Only</span></li>
                </ul>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto pt-8 border-t border-white/10 text-center text-xs text-slate-600 font-medium uppercase tracking-widest">
            &copy; {{ date('Y') }} {{ $clinic->name }}. Powered by Modular SaaS.
        </div>
    </footer>

    <!-- Custom Cursor DOM Elements -->
    <div class="cursor-dot" data-cursor-dot></div>
    <div class="cursor-outline" data-cursor-outline></div>

    <!-- Init Libraries -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Custom Cursor Logic
        const cursorDot = document.querySelector("[data-cursor-dot]");
        const cursorOutline = document.querySelector("[data-cursor-outline]");

        window.addEventListener("mousemove", function(e) {
            const posX = e.clientX;
            const posY = e.clientY;
            
            cursorDot.style.left = `${posX}px`;
            cursorDot.style.top = `${posY}px`;
            
            // Smoother trailing outline animation using CSS transitions and JS positioning
            cursorOutline.animate({
                left: `${posX}px`,
                top: `${posY}px`
            }, { duration: 500, fill: "forwards" });
        });

        // Initialize AOS
        AOS.init({
            once: true,
            offset: 50,
        });

        // Initialize Vanta.js Living Background (The "Net" Effect)
        @php
            $vantaColor = $content->custom_sections['vanta_bg_color'] ?? '#0ea5e9';
            // Vanta requires color in 0x format
            $vantaHex = str_replace('#', '0x', $vantaColor);
            
            // Check if 3D background is enabled (default to true)
            $enable3dBg = isset($content->custom_sections['enable_3d_bg']) ? (bool) $content->custom_sections['enable_3d_bg'] : true;
        @endphp

        @if($enable3dBg)
        if(document.getElementById('vanta-bg')) {
            VANTA.NET({
                el: "#vanta-bg",
                mouseControls: true,
                touchControls: true,
                gyroControls: false,
                minHeight: 200.00,
                minWidth: 200.00,
                scale: 1.00,
                scaleMobile: 1.00,
                color: {{ $vantaHex }}, // Dynamic from CMS
                backgroundColor: 0x070b19, // --bg-dark
                points: 15.00,
                maxDistance: 22.00,
                spacing: 16.00,
                showDots: true
            });
        }
        @endif

        // GSAP Hero Text Reveal Sequence
        gsap.from(".hero-stagger", {
            y: 50,
            opacity: 0,
            duration: 1.2,
            stagger: 0.2, // Delays each element slightly
            ease: "power4.out",
            delay: 0.5
        });
    </script>
</body>
</html>