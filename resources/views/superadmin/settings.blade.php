@extends('layouts.app')

@section('title', 'System Settings')
@section('page-title', 'System Settings')

@section('content')
<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
    <div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fas fa-cog text-blue-500"></i>
            </div>
            System Configuration
        </h1>
        <p class="text-slate-500 font-medium mt-2 flex items-center gap-2 italic">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            Global architecture parameters and platform metadata.
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto space-y-10">
    <!-- Platform Identity -->
    <div class="relative bg-white rounded-[3rem] border border-slate-100 p-10 hover:shadow-2xl transition-all duration-700 overflow-hidden group">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-50/30 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="relative z-10 flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-id-card text-xs"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Platform Identity</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">Public Branding and Metadata</p>
            </div>
        </div>

        <form method="POST" action="#" class="relative z-10 space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Platform Name</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <i class="fas fa-globe text-slate-300 group-focus-within/input:text-blue-500 transition-colors"></i>
                        </div>
                        <input type="text" name="app_name" value="{{ config('app.name') }}"
                               class="w-full pl-12 pr-4 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Support Contact Address</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-300 group-focus-within/input:text-blue-500 transition-colors"></i>
                        </div>
                        <input type="email" name="support_email" value="{{ config('mail.from.address') }}"
                               class="w-full pl-12 pr-4 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 transition-all">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="btn-premium-primary !py-4 !px-10 !rounded-2xl !text-[10px] !bg-slate-900 !text-white flex items-center gap-3 hover:!bg-blue-600 transition-all shadow-xl shadow-slate-900/10">
                    <i class="fas fa-save text-blue-400 group-hover:text-white transition-all"></i>
                    Update Platform Specs
                </button>
            </div>
        </form>
    </div>

    <!-- Security & Access Policy -->
    <div class="relative bg-slate-50/50 rounded-[3rem] border border-slate-100 p-10 overflow-hidden group">
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 text-center md:text-left">
                <div class="w-10 h-10 bg-white border border-slate-100 text-slate-400 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-shield-alt text-xs"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Global Auth Policy</h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-0.5">Enforce MFA and IP restrictions platform-wide.</p>
                </div>
            </div>

            <a href="#" class="btn-premium-outline !py-3 !px-8 !rounded-2xl !text-[10px] !text-slate-600 !border-slate-200 hover:!bg-slate-900 hover:!text-white transition-all flex items-center gap-2 group">
                <i class="fas fa-lock group-hover:scale-110 transition-transform"></i>
                Policies Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
