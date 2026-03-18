@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12 max-w-4xl mx-auto">
    <div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fas fa-rocket text-blue-500"></i>
            </div>
            New Campaign
        </h1>
        <p class="text-slate-500 font-medium mt-2 flex items-center gap-2 italic">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            Create a new marketing campaign to reach your customers.
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <!-- Form Container -->
    <div class="relative premium-card p-12 overflow-hidden group">
        <!-- Background Decorative Blobs -->
        <div class="absolute -top-32 -right-32 w-80 h-80 bg-blue-50/30 rounded-full blur-[120px] pointer-events-none group-hover:scale-110 transition-transform duration-1000"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-slate-50/50 rounded-full blur-[120px] pointer-events-none group-hover:scale-110 transition-transform duration-1000"></div>

        <div class="relative z-10 flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-inner">
                <i class="fas fa-satellite-dish text-xs"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Campaign Settings</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">Set up your campaign details and budget.</p>
            </div>
        </div>

        <form action="{{ route('superadmin.crm.store-campaign') }}" method="POST" class="relative z-10 space-y-10 focus-stagger">
            @csrf
            
            <div class="space-y-3">
                <label for="name" class="premium-label">Campaign Name *</label>
                <div class="relative group/input">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                        <i class="fas fa-signature text-slate-300 group-focus-within/input:text-blue-500 transition-colors"></i>
                    </div>
                    <input type="text" name="name" id="name" required placeholder="Summer Expansion 2024"
                           class="premium-input pl-14" value="{{ old('name') }}">
                </div>
                @error('name')
                    <p class="mt-1 text-[10px] font-bold text-blue-500 uppercase tracking-widest ml-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-3">
                <label for="description" class="premium-label">Description</label>
                <div class="relative group/input">
                    <div class="absolute top-4 left-6 pointer-events-none">
                        <i class="fas fa-align-left text-slate-300 group-focus-within/input:text-blue-500 transition-colors"></i>
                    </div>
                    <textarea name="description" id="description" rows="3" 
                              class="premium-input pl-14 py-5 resize-none" 
                              placeholder="Summarize your campaign goals...">{{ old('description') }}</textarea>
                </div>
                @error('description')
                    <p class="mt-1 text-[10px] font-bold text-blue-500 uppercase tracking-widest ml-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label for="type" class="premium-label">Campaign Type *</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none z-10">
                            <i class="fas fa-broadcast-tower text-slate-300 group-focus-within/input:text-blue-500 transition-colors"></i>
                        </div>
                        <select name="type" id="type" required 
                                class="premium-input pl-14 appearance-none relative z-0">
                            <option value="">Select Type</option>
                            <option value="email" {{ old('type') === 'email' ? 'selected' : '' }}>Email</option>
                            <option value="sms" {{ old('type') === 'sms' ? 'selected' : '' }}>SMS</option>
                            <option value="social_media" {{ old('type') === 'social_media' ? 'selected' : '' }}>Social Media</option>
                            <option value="advertisement" {{ old('type') === 'advertisement' ? 'selected' : '' }}>Advertisement</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                        </div>
                    </div>
                    @error('type')
                        <p class="mt-1 text-[10px] font-bold text-blue-500 uppercase tracking-widest ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <label for="budget" class="premium-label">Campaign Budget</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <i class="fas fa-credit-card text-slate-300 group-focus-within/input:text-blue-500 transition-colors"></i>
                        </div>
                        <input type="number" name="budget" id="budget" min="0" step="0.01" placeholder="0.00"
                               class="premium-input pl-14" value="{{ old('budget') }}">
                    </div>
                    @error('budget')
                        <p class="mt-1 text-[10px] font-bold text-blue-500 uppercase tracking-widest ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="relative group/date">
                    <x-nepali-date-input 
                        name="start_date"
                        label="Start Date (सुरु मिति)"
                        :value="old('start_date')"
                        :minDate="date('Y-m-d')"
                        help="Choose when to start this campaign."
                    />
                </div>

                <div class="relative group/date">
                    <x-nepali-date-input 
                        name="end_date"
                        label="End Date (अन्त्य मिति)"
                        :value="old('end_date')"
                        :minDate="date('Y-m-d')"
                        help="Choose when to end this campaign (optional)."
                    />
                </div>
            </div>

            <div class="flex items-center justify-between pt-10 border-t border-slate-50">
                <a href="{{ route('superadmin.crm.campaigns') }}" class="btn-premium-outline !rounded-2xl !py-4 !px-10 group">
                    <i class="fas fa-times mr-2 group-hover:rotate-90 transition-transform text-[10px]"></i>
                    Cancel
                </a>
                <button type="submit" class="btn-premium-primary !rounded-2xl !py-4 !px-12 !bg-slate-900 shadow-2xl shadow-slate-900/10 hover:!bg-blue-600 transition-all flex items-center gap-3 group">
                    <i class="fas fa-paper-plane text-blue-400 group-hover:text-white group-hover:translate-x-1 group-hover:-translate-y-1 transition-all text-xs"></i>
                    Start Campaign
                </button>
            </div>
        </form>
    </div>
</div>
@endsection