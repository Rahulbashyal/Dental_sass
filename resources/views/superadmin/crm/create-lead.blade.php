@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12 max-w-4xl mx-auto">
    <div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fas fa-plus text-blue-500"></i>
            </div>
            Add New Lead
        </h1>
        <p class="text-slate-500 font-medium mt-2 flex items-center gap-2 italic">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            Enter details to add a new potential clinic or customer.
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
                <i class="fas fa-id-badge text-xs"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Lead Details</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 italic">Basic information about the lead.</p>
            </div>
        </div>

        <form action="{{ route('superadmin.crm.store-lead') }}" method="POST" class="relative z-10 space-y-10 focus-stagger">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label for="name" class="premium-label">Full Name *</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <i class="fas fa-user text-slate-300 group-focus-within/input:text-blue-500 transition-colors"></i>
                        </div>
                        <input type="text" name="name" id="name" required placeholder="Dr. John Doe"
                               class="premium-input pl-14" value="{{ old('name') }}">
                    </div>
                    @error('name')
                        <p class="mt-1 text-[10px] font-bold text-rose-500 uppercase tracking-widest ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <label for="email" class="premium-label">Email Address *</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-300 group-focus-within/input:text-blue-500 transition-colors"></i>
                        </div>
                        <input type="email" name="email" id="email" required placeholder="contact@acquisition.com"
                               class="premium-input pl-14" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-1 text-[10px] font-bold text-blue-500 uppercase tracking-widest ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label for="phone" class="premium-label">Phone Number</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-slate-300 group-focus-within/input:text-blue-500 transition-colors"></i>
                        </div>
                        <input type="text" name="phone" id="phone" placeholder="+977 12345678"
                               class="premium-input pl-14" value="{{ old('phone') }}">
                    </div>
                    @error('phone')
                        <p class="mt-1 text-[10px] font-bold text-blue-500 uppercase tracking-widest ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <label for="company" class="premium-label">Company / Clinic Name</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <i class="fas fa-building text-slate-300 group-focus-within/input:text-blue-500 transition-colors"></i>
                        </div>
                        <input type="text" name="company" id="company" placeholder="HealthCare Systems Ltd."
                               class="premium-input pl-14" value="{{ old('company') }}">
                    </div>
                    @error('company')
                        <p class="mt-1 text-[10px] font-bold text-blue-500 uppercase tracking-widest ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label for="source" class="premium-label">Lead Source *</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none z-10">
                            <i class="fas fa-search-location text-slate-300 group-focus-within/input:text-blue-500 transition-colors"></i>
                        </div>
                        <select name="source" id="source" required 
                                class="premium-input pl-14 appearance-none relative z-0">
                            <option value="">Select Source</option>
                            <option value="website" {{ old('source') === 'website' ? 'selected' : '' }}>Website</option>
                            <option value="referral" {{ old('source') === 'referral' ? 'selected' : '' }}>Referral</option>
                            <option value="social_media" {{ old('source') === 'social_media' ? 'selected' : '' }}>Social Media</option>
                            <option value="advertisement" {{ old('source') === 'advertisement' ? 'selected' : '' }}>Advertisement</option>
                            <option value="other" {{ old('source') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                        </div>
                    </div>
                    @error('source')
                        <p class="mt-1 text-[10px] font-bold text-blue-500 uppercase tracking-widest ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <label for="potential_value" class="premium-label">Lead Value (NPR)</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <i class="fas fa-coins text-slate-300 group-focus-within/input:text-blue-500 transition-colors"></i>
                        </div>
                        <input type="number" name="potential_value" id="potential_value" min="0" step="0.01" placeholder="0.00"
                               class="premium-input pl-14" value="{{ old('potential_value') }}">
                    </div>
                    @error('potential_value')
                        <p class="mt-1 text-[10px] font-bold text-blue-500 uppercase tracking-widest ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-3">
                <label for="notes" class="premium-label">Notes</label>
                <div class="relative group/input">
                    <div class="absolute top-4 left-6 pointer-events-none">
                        <i class="fas fa-brain text-slate-300 group-focus-within/input:text-blue-500 transition-colors"></i>
                    </div>
                    <textarea name="notes" id="notes" rows="4" 
                              class="premium-input pl-14 py-5 resize-none overflow-hidden" 
                              placeholder="Add any additional notes about this lead...">{{ old('notes') }}</textarea>
                </div>
                @error('notes')
                    <p class="mt-1 text-[10px] font-bold text-blue-500 uppercase tracking-widest ml-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-10 border-t border-slate-50">
                <a href="{{ route('superadmin.crm.leads') }}" class="btn-premium-outline !rounded-2xl !py-4 !px-10 group">
                    <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform text-[10px]"></i>
                    Cancel
                </a>
                <button type="submit" class="btn-premium-primary !rounded-2xl !py-4 !px-12 !bg-slate-900 shadow-2xl shadow-slate-900/10 hover:!bg-blue-600 transition-all flex items-center gap-3 group">
                    <i class="fas fa-database text-blue-400 group-hover:text-white transition-all text-xs"></i>
                    Add Lead
                </button>
            </div>
        </form>
    </div>
</div>
@endsection