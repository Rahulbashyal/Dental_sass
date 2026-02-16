<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Clinical Login - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-gradient {
            background: radial-gradient(circle at top left, rgba(99, 102, 241, 0.1), transparent),
                        radial-gradient(circle at bottom right, rgba(6, 182, 212, 0.05), transparent);
        }
    </style>
</head>
<body class="bg-slate-50 auth-gradient antialiased page-fade-in">
    <div class="min-h-screen flex flex-col lg:flex-row-reverse">
        <!-- Right Side: Visual/Branding (Reversed for Login) -->
        <div class="hidden lg:flex lg:w-1/2 bg-slate-900 items-center justify-center p-12 relative overflow-hidden">
            <div class="relative z-10 max-w-md">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-xl rounded-2xl flex items-center justify-center mb-8 border border-white/20">
                     <img src="{{ asset('logo.png') }}" alt="Logo" class="h-8 w-auto">
                </div>
                <h2 class="text-4xl font-black text-white mb-6 leading-tight">Welcome Back to Clinical Core.</h2>
                <p class="text-slate-400 text-lg mb-12">Resume your clinical operations with our ultra-fast dashboard and real-time patient tracking.</p>
                
                <div class="p-8 bg-white/5 border border-white/10 rounded-3xl backdrop-blur-sm shadow-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Platform Status</span>
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2"></span>
                            <span class="text-[10px] text-green-500 font-black uppercase">Operational</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 w-3/4"></div>
                        </div>
                        <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-cyan-500 w-1/2"></div>
                        </div>
                        <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-500 w-5/6"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Decorative elements -->
            <svg class="absolute bottom-0 left-0 w-96 h-96 text-slate-800 opacity-20 -mb-24 -ml-24 transform rotate-180" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
            </svg>
        </div>

        <!-- Left Side: Form -->
        <div class="flex-1 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-white/40 backdrop-blur-md">
            <div class="max-w-md w-full">
                <div class="mb-10 text-center lg:text-left">
                    <h1 class="text-3xl font-black text-slate-900 mb-2">Clinical Login</h1>
                    <p class="text-slate-500 text-sm">Access your clinic's specialized SaaS instance.</p>
                </div>

                <form class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="space-y-5">
                        <div class="group">
                            <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block group-focus-within:text-slate-900 transition-colors">Credential Identifier</label>
                            <div class="relative">
                                <input id="email" name="email" type="email" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-slate-100 focus:border-slate-900 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="e.g. admin@yourclinic.com" value="{{ old('email') }}">
                            </div>
                            @error('email')
                                <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="group">
                            <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block group-focus-within:text-slate-900 transition-colors">Secure Key</label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-slate-100 focus:border-slate-900 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="••••••••">
                            </div>
                            @error('password')
                                <p class="text-red-500 text-[10px] font-bold mt-1 uppercase tracking-tighter">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-slate-900 focus:ring-slate-900 border-slate-300 rounded">
                            <label for="remember" class="ml-3 block text-xs font-bold text-slate-600 uppercase tracking-wide">
                                Stay Synced
                            </label>
                        </div>
                        <a href="#" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-900 transition-colors">Lost Access?</a>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-5 bg-slate-900 hover:bg-black text-white text-sm font-black rounded-2xl shadow-xl shadow-slate-200 transition-all flex items-center justify-center group relative overflow-hidden">
                            <span class="relative z-10">Authorize Session</span>
                            <svg class="w-5 h-5 ml-2 relative z-10 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </div>

                    <p class="text-center text-slate-500 text-sm pt-6">
                        No medical instance yet? 
                        <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline">Provision Now &rarr;</a>
                    </p>
                </form>

                 <div class="mt-12 flex items-center justify-center space-x-6 grayscale opacity-40">
                    <img src="{{ asset('logo.png') }}" alt="Trust 1" class="h-8 w-auto">
                    <div class="w-px h-6 bg-slate-200"></div>
                     <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight text-center text-slate-500">Clinical Data Security<br>Verified by ABS Soft</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>