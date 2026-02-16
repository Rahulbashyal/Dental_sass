<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Authorize Access | Clinical Node Identity</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/animations.css'])
    
    <!-- Premium Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&family=Outfit:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-bg: #f8fafc;
            --brand-main: #3b82f6;
            --brand-dark: #1e3a8a;
            --glass-white: rgba(255, 255, 255, 0.75);
            --glass-border: rgba(255, 255, 255, 0.8);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--primary-bg);
            overflow-x: hidden;
        }

        .outfit { font-family: 'Outfit', sans-serif; }

        /* Advanced Mesh Background */
        .mesh-container {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: #ffffff;
            overflow: hidden;
        }

        .mesh-blob {
            position: absolute;
            width: 800px;
            height: 800px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            z-index: 0;
            animation: blob-drift 30s infinite alternate cubic-bezier(0.45, 0, 0.55, 1);
        }

        .blob-1 { background: #3b82f6; top: -200px; right: -200px; animation-delay: 0s; }
        .blob-2 { background: #10b981; bottom: -300px; left: -200px; animation-duration: 35s; }
        .blob-3 { background: #8b5cf6; top: 30%; left: -100px; opacity: 0.1; }

        @keyframes blob-drift {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); }
            100% { transform: translate(100px, 50px) scale(1.1) rotate(10deg); }
        }

        /* Full Screen Split Architecture */
        .page-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 1024px) {
            .page-shell {
                flex-direction: row;
            }
        }

        /* Sidebar Side (Branding) */
        .brand-side {
            background: linear-gradient(165deg, #1e293b 0%, #0f172a 100%);
            padding: 5rem 4rem;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
            flex: 1;
        }

        .brand-side::after {
            content: "";
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.1;
            pointer-events: none;
        }

        /* Form Side */
        .form-side {
            padding: 5rem 4rem;
            background: #ffffff;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .form-container {
            width: 100%;
            max-width: 500px;
        }

        /* Input Engineering */
        .field-shell {
            position: relative;
            margin-bottom: 2rem;
        }

        .input-premium {
            width: 100%;
            background: white;
            border: 2px solid #f1f5f9;
            padding: 1.75rem 1.75rem 1.75rem 4.5rem;
            border-radius: 2rem;
            font-size: 1.15rem;
            font-weight: 500;
            color: #0f172a;
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.03),
                inset 0 2px 4px 0 rgba(0, 0, 0, 0.02);
        }

        .input-premium:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.15);
            transform: translateY(-2px);
        }

        .field-icon {
            position: absolute;
            left: 1.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.4rem;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .input-premium:focus + .field-icon {
            color: #3b82f6;
            transform: translateY(-50%) scale(1.1);
        }

        .field-label {
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            margin-bottom: 1rem;
            display: block;
            margin-left: 0.5rem;
        }

        /* Authorize Button */
        .btn-authorize {
            background: #1e293b;
            color: white;
            width: 100%;
            padding: 1.4rem;
            border-radius: 1.25rem;
            font-weight: 800;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.3);
        }

        .btn-authorize::after {
            content: "";
            position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: 0.6s;
        }

        .btn-authorize:hover {
            background: #334155;
            transform: translateY(-3px);
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.4);
        }

        .btn-authorize:hover::after {
            left: 100%;
        }

        /* Decorative Elements */
        .status-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 10px #10b981;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
            100% { opacity: 1; transform: scale(1); }
        }

        /* Mesh Background for Form Side */
        .form-mesh {
            position: absolute;
            inset: 0;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }

        /* Responsive Flow */
        @media (max-width: 1024px) {
            .brand-side {
                padding: 4rem 2rem;
                min-height: 400px;
                text-align: center;
                align-items: center;
            }
            .form-side {
                padding: 4rem 2rem;
            }
        }
    </style>
</head>
<body class="min-h-screen antialiased bg-slate-50">
    <div class="page-shell" 
         x-data="{ active: false }" 
         x-init="setTimeout(() => active = true, 50)"
         x-cloak>
        
        <!-- Left Pane: Branding & Trust -->
        <div class="brand-side lg:w-1/2 overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center">
                        <i class="fas fa-tooth text-2xl text-white"></i>
                    </div>
                    <span class="outfit font-black text-xl tracking-tighter">{{ config('app.name') }}</span>
                </div>

                <h2 class="outfit text-4xl font-black leading-tight mb-6">Your health,<br>digitally secured.</h2>
                <p class="text-slate-400 font-medium leading-relaxed">Secure access to your clinical records, interactive treatment plans, and instant financial coordination.</p>
            </div>

            <!-- Verified Badges -->
            <div class="relative z-10">
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 flex items-center gap-4">
                    <img src="{{ asset('logo.png') }}" class="h-6 opacity-80" alt="Clinic Logo">
                    <div class="w-px h-6 bg-white/10"></div>
                    <div class="text-[10px] uppercase font-black tracking-widest text-slate-400">Verified Identity Node</div>
                </div>
            </div>

            <!-- Background Decoration -->
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl"></div>
        </div>

        <!-- Right Pane: The Auth Engine -->
        <div class="form-side lg:w-1/2">
            <!-- Sophisticated Mesh Background for Form Side -->
            <div class="form-mesh">
                <div class="mesh-blob blob-1" style="opacity: 0.1; right: -10%; top: -10%;"></div>
                <div class="mesh-blob blob-2" style="opacity: 0.05; left: -10%; bottom: -10%;"></div>
            </div>

            <div class="form-container">
                <!-- Mobile Branding -->
                <div class="lg:hidden flex flex-col items-center mb-10 text-center">
                    <div class="w-16 h-16 bg-slate-900 rounded-3xl flex items-center justify-center mb-6">
                        <i class="fas fa-tooth text-2xl text-white"></i>
                    </div>
                    <h1 class="outfit text-3xl font-black text-slate-900">Patient Identity</h1>
                </div>

                <div class="mb-12 text-center lg:text-left">
                    <h1 class="outfit text-4xl font-black text-slate-900 mb-4">Authorize Access</h1>
                    <p class="text-slate-500 font-medium text-lg">Synchronizing your distributed medical records.</p>
                </div>

            <!-- Identity Form -->
            <form action="{{ route('patient.login') }}" method="POST">
                @csrf
                
                <!-- Email Field -->
                <div class="field-shell">
                    <label class="field-label">Official Register Email</label>
                    <div class="relative">
                        <input type="email" name="email" required 
                               class="input-premium" 
                               placeholder="patient@medical-node.com"
                               value="{{ old('email') }}">
                        <i class="fas fa-envelope field-icon"></i>
                    </div>
                    @error('email')
                        <p class="mt-2 text-[10px] font-black text-rose-500 uppercase tracking-widest ml-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Patient ID Field -->
                <div class="field-shell">
                    <label class="field-label">Registry Identifier (UID)</label>
                    <div class="relative">
                        <input type="text" name="patient_id" required 
                               class="input-premium" 
                               placeholder="e.g. PAT-9000-X">
                        <i class="fas fa-id-card-clip field-icon"></i>
                    </div>
                </div>

                <!-- Submit Action -->
                <div class="mt-4">
                    <button type="submit" class="btn-authorize">
                        <span>Initiate Node Link</span>
                        <i class="fas fa-fingerprint text-lg"></i>
                    </button>
                    
                    <div class="mt-10 flex items-center justify-center gap-3">
                        <div class="status-dot"></div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Encrypted AES-256 Link Active</span>
                    </div>
                </div>
            </form>

            <!-- Navigation Footer -->
            <div class="mt-16 pt-10 border-t border-slate-100 flex flex-col items-center sm:flex-row sm:justify-between gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">
                    <i class="fas fa-arrow-left text-[9px]"></i>
                    Back to Core Website
                </a>
                
                <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest text-center">
                    Managed by DCMS Infrastructure Node V3.0
                </p>
            </div>
        </div>
    </div>
    <!-- Alpine.js & Micro-Interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
