<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SaaS Onboarding - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-gradient {
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.1), transparent),
                        radial-gradient(circle at bottom left, rgba(6, 182, 212, 0.05), transparent);
        }
    </style>
</head>
<body class="bg-slate-50 auth-gradient antialiased page-fade-in">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left Side: Visual/Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-indigo-900 items-center justify-center p-12 relative overflow-hidden">
            <div class="relative z-10 max-w-md">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-xl rounded-2xl flex items-center justify-center mb-8 border border-white/20">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-8 w-auto">
                </div>
                <h2 class="text-4xl font-black text-white mb-6 leading-tight">Digitalize Your Dental Practice in Minutes.</h2>
                <p class="text-indigo-200 text-lg mb-12">Join 500+ clinics across Nepal who have upgraded to our high-performance clinical engine.</p>
                
                <div class="space-y-6">
                    <div class="flex items-center space-x-4 bg-white/5 border border-white/10 p-4 rounded-2xl backdrop-blur-sm">
                        <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center text-white font-bold">1</div>
                        <div>
                            <p class="text-white font-bold text-sm">Create Account</p>
                            <p class="text-indigo-300 text-xs text-opacity-80">Simple signup to get started</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 p-4 rounded-2xl opacity-60">
                        <div class="w-10 h-10 bg-indigo-800 rounded-xl flex items-center justify-center text-indigo-400 font-bold">2</div>
                        <div>
                            <p class="text-indigo-200 font-bold text-sm">Configure Clinic</p>
                            <p class="text-indigo-400 text-xs">Set branches and staff roles</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Decorative elements -->
            <svg class="absolute top-0 right-0 w-96 h-96 text-indigo-800 opacity-20 -mt-24 -mr-24" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
            </svg>
        </div>

        <!-- Right Side: Form -->
        <div class="flex-1 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-white/40 backdrop-blur-md">
            <div class="max-w-md w-full">
                <div class="mb-10">
                    <h1 class="text-3xl font-black text-slate-900 mb-2">Get Started</h1>
                    <p class="text-slate-500 text-sm">No credit card required for 14-day trial.</p>
                </div>

                <form class="space-y-6" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="space-y-5">
                        <div class="group">
                            <label for="name" class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block group-focus-within:text-indigo-600 transition-colors">Full Name</label>
                            <div class="relative">
                                <input id="name" name="name" type="text" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="e.g. Dr. Rajesh Shrestha" value="{{ old('name') }}">
                            </div>
                            @error('name')
                                <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="group">
                            <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block group-focus-within:text-indigo-600 transition-colors">Business Email</label>
                            <div class="relative">
                                <input id="email" name="email" type="email" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="clinic@example.com" value="{{ old('email') }}">
                            </div>
                            @error('email')
                                <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="group">
                            <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block group-focus-within:text-indigo-600 transition-colors">Secure Password</label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="••••••••">
                            </div>
                            @error('password')
                                <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="group">
                            <label for="password_confirmation" class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block group-focus-within:text-indigo-600 transition-colors">Confirm Password</label>
                            <div class="relative">
                                <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black rounded-2xl shadow-xl shadow-indigo-100 transition-all flex items-center justify-center group overflow-hidden relative">
                            <span class="relative z-10">Create SaaS Instance</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-cyan-400 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                            <svg class="w-5 h-5 ml-2 relative z-10 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>

                    <p class="text-center text-slate-500 text-sm pt-6">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">Sign in &rarr;</a>
                    </p>
                </form>

                <div class="mt-12 flex items-center justify-center space-x-6 grayscale opacity-40">
                    <img src="{{ asset('logo.png') }}" alt="Trust 1" class="h-8 w-auto">
                    <div class="w-px h-6 bg-slate-200"></div>
                     <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight text-center">Cloud Hosting<br>Secure in Nepal</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>