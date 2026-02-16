@extends('layouts.app')

@section('title', 'Change Password - ' . config('app.name'))

@section('page-title', 'Security Settings')

@section('content')
<div class="max-w-2xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header Summary -->
    <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-4xl p-8 text-white shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-10">
            <i class="fas fa-shield-alt text-8xl"></i>
        </div>
        <div class="relative z-10 space-y-2">
            <h1 class="text-2xl font-black uppercase tracking-tight">Security & Privacy</h1>
            <p class="text-slate-400 text-sm font-medium">Manage your account security and authentication methods.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="animate-in slide-in-from-top-4 duration-500">
            <div class="bg-emerald-50 border border-emerald-100 rounded-3xl p-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-check"></i>
                </div>
                <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Password Update Form -->
    <div class="bg-white rounded-4xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600">
                <i class="fas fa-key"></i>
            </div>
            <div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Update Password</h2>
                <p class="text-xs text-slate-400 font-medium">Ensure your account uses a long, random password to stay secure.</p>
            </div>
        </div>
        <form action="{{ route('password.update') }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Current Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-lock text-xs"></i>
                        </div>
                        <input type="password" name="current_password" class="form-input pl-10" placeholder="••••••••" required>
                    </div>
                    @error('current_password') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2 pt-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">New Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-shield-alt text-xs"></i>
                        </div>
                        <input type="password" name="password" class="form-input pl-10" placeholder="••••••••" required>
                    </div>
                    @error('password') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2 pt-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm New Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-check-circle text-xs"></i>
                        </div>
                        <input type="password" name="password_confirmation" class="form-input pl-10" placeholder="••••••••" required>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex flex-col sm:flex-row gap-4">
                <button type="submit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-[11px] hover:bg-slate-800 hover:scale-[1.02] active:scale-95 transition-all duration-200 shadow-xl shadow-slate-200">
                    Save New Password
                </button>
                <a href="{{ route('profile.edit') }}" class="py-4 px-8 bg-slate-100 text-slate-600 rounded-2xl font-black uppercase tracking-widest text-[11px] hover:bg-slate-200 transition-all duration-200 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Security Tips -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-12">
        <div class="p-6 bg-blue-50/50 rounded-3xl border border-blue-100 flex gap-4">
            <div class="w-10 h-10 rounded-2xl bg-white flex-shrink-0 flex items-center justify-center text-blue-600 shadow-sm">
                <i class="fas fa-lightbulb"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-xs font-black text-blue-900 uppercase">Strong Combinations</h4>
                <p class="text-[10px] text-blue-700 leading-relaxed">Use a mix of uppercase, numbers and symbols for maximum security.</p>
            </div>
        </div>
        <div class="p-6 bg-purple-50/50 rounded-3xl border border-purple-100 flex gap-4">
            <div class="w-10 h-10 rounded-2xl bg-white flex-shrink-0 flex items-center justify-center text-purple-600 shadow-sm">
                <i class="fas fa-history"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-xs font-black text-purple-900 uppercase">Regular Updates</h4>
                <p class="text-[10px] text-purple-700 leading-relaxed">Consider changing your password every 90 days to maintain safety.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-4xl { border-radius: 2.5rem; }
    .form-input {
        @apply w-full rounded-2xl border-2 border-slate-50 bg-slate-50 px-5 py-4 text-sm text-slate-700 placeholder-slate-400 focus:border-blue-600 focus:bg-white focus:outline-none transition-all duration-200;
    }
</style>
@endsection
