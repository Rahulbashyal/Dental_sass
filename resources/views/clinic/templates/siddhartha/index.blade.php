<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $clinic->name }} | Siddhartha Storytelling</title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Tailwind (using Vite as per project standards) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #1d4ed8;
            --bg-deep: #030712;
            --glass: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            background-color: var(--bg-deep);
            color: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        h1, h2, h3, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        .canvas-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: 1;
            pointer-events: none;
        }

        .narrative-container {
            position: relative;
            z-index: 10;
        }

        .glass-card {
            background: var(--glass);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 2rem;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px);
        }

        .specialist-photo {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
        }

        .specialist-photo img {
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover .specialist-photo img {
            transform: scale(1.1);
        }

        /* Video Scrubber Tweaks */
        .video-scrub-container {
            position: sticky;
            top: 0;
            height: 100vh;
            width: 100%;
            overflow: hidden;
        }

        .reveal-item { opacity: 0; transform: translateY(40px); }
        
        .glow-text {
            text-shadow: 0 0 30px rgba(59, 130, 246, 0.4);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-deep); }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }
    </style>
</head>
@php
    $specialists = $content->custom_sections['specialists'] ?? [
        ['name' => 'Dr. Santosh Kandel', 'title' => 'Oral & Maxillofacial Surgeon', 'focus' => 'Implants'],
        ['name' => 'Dr. Gyanendra Jha', 'title' => 'Endodontist', 'focus' => 'Root Canal'],
        ['name' => 'Dr. Raju Shrestha', 'title' => 'Orthodontist', 'focus' => 'Braces'],
        ['name' => 'Dr. Reecha Bhadel', 'title' => 'Prosthodontist', 'focus' => 'Aesthetics'],
        ['name' => 'Dr. Suman Chhetri', 'title' => 'Senior Surgeon', 'focus' => 'Clinical'],
    ];
@endphp

<body class="antialiased" x-data="{ 
    specialistModal: false, 
    activeSpecialist: null,
    specialists: {{ json_encode($specialists) }},
    openSpecialist(index) {
        this.activeSpecialist = this.specialists[index];
        this.specialistModal = true;
    }
}">

    <!-- Ambient 3D Background -->
    <div class="canvas-container" id="3d-canvas-root"></div>

    <!-- Floating Navigation -->
    <nav class="fixed top-8 left-1/2 -translate-x-1/2 z-[100] w-[90%] max-w-5xl glass-card py-4 px-8 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-black text-xl">S</div>
            <span class="font-heading font-black text-xl tracking-tighter">{{ $content->navbar_title ?? 'Siddhartha' }}</span>
        </div>
        
        <div class="hidden md:flex items-center gap-8">
            <a href="#specialists" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-white transition-colors">Specialists</a>
            <a href="#implant-narrative" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-white transition-colors">Implants</a>
            <a href="#ortho-narrative" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-white transition-colors">Aesthetics</a>
            <a href="#contact" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-white transition-colors">Contact</a>
        </div>

        <a href="{{ route('clinic.book', $clinic->slug) }}" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-blue-900/20">
            Book Now
        </a>
    </nav>

    <div class="narrative-container">
        <!-- Hero Section -->
        <section class="min-h-screen flex items-center justify-center relative px-6">
            <div class="max-w-7xl mx-auto text-center">
                <div class="reveal-item mb-6">
                    <span class="inline-block px-4 py-1.5 text-[10px] font-black tracking-[0.3em] text-blue-400 uppercase glass-card bg-blue-500/5 border-blue-500/10">
                        The Future of Dental Care • 5 Specialists • 1 Home
                    </span>
                </div>
                
                <h1 class="reveal-item text-6xl md:text-9xl font-black mb-8 leading-[0.9] tracking-tighter glow-text">
                    {{ $content->vision_hook ?? 'Precision is our Practice.' }}<br/>
                    <span class="text-slate-500">Your Smile is our Story.</span>
                </h1>
                
                <p class="reveal-item text-xl text-slate-400 max-w-2xl mx-auto mb-12 font-medium leading-relaxed">
                    {{ $content->hero_subtitle ?? 'Experience the next generation of oral healthcare with Siddhartha Dental Home’s specialized ecosystem.' }}
                </p>
                
                <div class="reveal-item flex flex-wrap justify-center gap-6">
                    <button class="px-10 py-5 bg-white text-black hover:bg-slate-100 rounded-2xl font-black text-xs uppercase tracking-widest transition-all active:scale-95 shadow-2xl">
                        Consult a Specialist
                    </button>
                    <button class="px-10 py-5 glass-card font-black text-xs uppercase tracking-widest hover:bg-white/5 transition-all">
                        Watch Documentary
                    </button>
                </div>
            </div>
            
            <!-- Mouse Scroll Icon -->
            <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex flex-col items-center gap-4 opacity-30">
                <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center p-1">
                    <div class="w-1 h-2 bg-white rounded-full animate-bounce"></div>
                </div>
                <span class="text-[8px] font-black uppercase tracking-widest">Scroll to Explore</span>
            </div>
        </section>

        <!-- Specialists Section -->
        <section id="specialists" class="py-40 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="mb-24 text-center md:text-left reveal-item">
                    <h2 class="text-5xl md:text-7xl font-black mb-6 leading-tight">The Specialists.<br/><span class="text-blue-500">Clinical Perfection.</span></h2>
                    <p class="text-slate-400 font-medium max-w-xl text-lg leading-relaxed">Meet the five architectural pillars of Siddhartha Dental Home. Each a master of their discipline, committed to your lifelong health.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">


                    @foreach($specialists as $index => $doc)
                    <div class="glass-card p-8 group overflow-hidden reveal-item cursor-pointer" @click="openSpecialist({{ $index }})">
                        <div class="specialist-photo h-64 mb-8 bg-slate-900 flex items-center justify-center border border-white/5">
                            <i class="fas fa-user-md text-6xl text-slate-800 group-hover:text-blue-500 transition-colors"></i>
                        </div>
                        
                        <div class="relative z-10">
                            <span class="inline-block px-3 py-1 bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase tracking-widest mb-4 rounded-lg border border-blue-500/20">
                                {{ $doc['focus'] }}
                            </span>
                            <h3 class="text-3xl font-black mb-2">{{ $doc['name'] }}</h3>
                            <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-6 h-10">{{ $doc['title'] }}</p>
                            
                            <div class="inline-flex items-center text-[10px] font-black uppercase tracking-widest text-white hover:text-blue-400 transition-all gap-2 group/link">
                                Clinical Profile 
                                <span class="w-8 h-[1px] bg-white group-hover/link:bg-blue-400 group-hover/link:w-12 transition-all"></span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Narrative Section: The Implant Story -->
        <section id="implant-narrative" class="relative">
            <x-video-scrub 
                :videoUrl="asset('themes/siddhartha/assets/videos/implant-scrub.mp4')" 
                title="Biological Integration" 
                specialist="Dr. Santosh Kandel"
            />
        </section>

        <!-- Narrative Section: Structural Harmony -->
        <section id="ortho-narrative" class="relative">
            <x-video-scrub 
                :videoUrl="asset('themes/siddhartha/assets/videos/ortho-scrub.mp4')" 
                title="Structural Harmony" 
                specialist="Dr. Raju Shrestha"
            />
        </section>

        <!-- Final Vision Section -->
        <section class="py-60 px-6 text-center bg-gradient-to-t from-blue-900/20 to-transparent">
            <div class="max-w-4xl mx-auto">
                <h2 class="reveal-item text-4xl md:text-7xl font-black mb-12 leading-tight">Your story is waiting to be rewritten.</h2>
                <div class="reveal-item flex flex-col md:flex-row justify-center gap-8 items-center">
                    <a href="{{ route('clinic.book', $clinic->slug) }}" class="px-12 py-6 bg-white text-black rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl hover:scale-105 transition-all">
                        Schedule an Analysis
                    </a>
                    <div class="text-left max-w-xs">
                        <p class="text-slate-500 text-sm font-medium">Located in the heart of Butwal, providing specialized care for the entire Rupandehi region.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-20 px-6 border-t border-white/5 text-center">
            <div class="flex flex-col items-center gap-6">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center font-black text-2xl">S</div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">© 2024 {{ $clinic->name }} • Engineered by ABS Soft</p>
            </div>
        </footer>
    </div>

    <!-- Specialist Detail Modal (Slide-over style) -->
    <div x-show="specialistModal" 
         class="fixed inset-0 z-[110] flex items-center justify-end"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-cloak>
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" @click="specialistModal = false"></div>
        
        <div class="relative w-full max-w-2xl h-full bg-slate-950 border-l border-white/10 p-12 overflow-y-auto"
             x-transition:enter="transition transform ease-out duration-500"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0">
             
            <button @click="specialistModal = false" class="absolute top-8 right-8 text-slate-500 hover:text-white transition-colors">
                <i class="fas fa-times text-2xl"></i>
            </button>

            <template x-if="activeSpecialist">
                <div>
                    <span class="inline-block px-4 py-1.5 bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase tracking-widest mb-8 rounded-lg border border-blue-500/20" x-text="activeSpecialist.focus"></span>
                    
                    <h2 class="text-6xl font-black mb-4 leading-tight" x-text="activeSpecialist.name"></h2>
                    <p class="text-slate-400 text-xl font-bold uppercase tracking-widest mb-12" x-text="activeSpecialist.title"></p>

                    <div class="space-y-12">
                        <div class="glass-card p-8 border-l-4 border-blue-500">
                            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-slate-500 mb-4">Clinical Philosophy</h4>
                            <p class="text-slate-300 leading-relaxed italic">"My approach to dentistry is rooted in biological harmony and structural integrity. Every smile is an architectural masterpiece requiring precision engineering."</p>
                        </div>

                        <div>
                            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-slate-500 mb-6">Area of Mastery</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="glass-card p-6 bg-white/5">
                                    <h5 class="font-black text-sm mb-2 text-blue-400">Biological Precision</h5>
                                    <p class="text-xs text-slate-500 leading-normal">Utilizing the latest 3D imaging to map neural paths for zero-impact procedures.</p>
                                </div>
                                <div class="glass-card p-6 bg-white/5">
                                    <h5 class="font-black text-sm mb-2 text-blue-400">Structural Integrity</h5>
                                    <p class="text-xs text-slate-500 leading-normal">Ensuring long-term stability through advanced material science and bonding.</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-8">
                            <a href="{{ route('clinic.book', $clinic->slug) }}" class="inline-block bg-blue-600 hover:bg-blue-500 text-white px-10 py-5 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">
                                Consult with <span x-text="activeSpecialist.name.split(' ')[1]"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Intitial Reveal
        document.addEventListener('DOMContentLoaded', () => {
            gsap.to('.reveal-item', {
                opacity: 1,
                y: 0,
                duration: 1.2,
                stagger: 0.15,
                ease: "expo.out",
                scrollTrigger: {
                    trigger: '.reveal-item',
                    start: 'top 90%'
                }
            });
            
            // Animation for Navbar on scroll
            ScrollTrigger.create({
                start: 'top -80',
                onUpdate: (self) => {
                    if (self.direction === 1) { // Scrolling down
                        gsap.to('nav', { y: -150, opacity: 0, duration: 0.3 });
                    } else { // Scrolling up
                        gsap.to('nav', { y: 0, opacity: 1, duration: 0.3 });
                    }
                }
            });
        });

        // 3D Engine Setup - Atmospheric Particles
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        document.getElementById('3d-canvas-root').appendChild(renderer.domElement);

        // Particle Geometry
        const particlesCount = 2000;
        const posArray = new Float32Array(particlesCount * 3);
        for(let i = 0; i < particlesCount * 3; i++) {
            posArray[i] = (Math.random() - 0.5) * 15;
        }

        const particlesGeometry = new THREE.BufferGeometry();
        particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));

        const particlesMaterial = new THREE.PointsMaterial({
            size: 0.005,
            color: 0x3b82f6,
            transparent: true,
            opacity: 0.4,
            blending: THREE.AdditiveBlending
        });

        const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
        scene.add(particlesMesh);

        camera.position.z = 5;

        // Mouse Parallax
        let mouseX = 0;
        let mouseY = 0;
        window.addEventListener('mousemove', (e) => {
            mouseX = (e.clientX - window.innerWidth / 2) / 100;
            mouseY = (e.clientY - window.innerHeight / 2) / 100;
        });

        function animate() {
            requestAnimationFrame(animate);
            
            particlesMesh.rotation.y += 0.001;
            particlesMesh.rotation.x += 0.0005;

            // Smooth parallax movement
            particlesMesh.position.x += (mouseX - particlesMesh.position.x) * 0.05;
            particlesMesh.position.y += (-mouseY - particlesMesh.position.y) * 0.05;
            
            renderer.render(scene, camera);
        }
        animate();

        // Responsive resize
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        // GSAP Scroll Animations for 3D
        gsap.to(particlesMesh.rotation, {
            y: Math.PI * 2,
            scrollTrigger: {
                trigger: "body",
                start: "top top",
                end: "bottom bottom",
                scrub: 2
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
