@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('content')
<div class="space-y-8 page-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
                <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-user-edit text-amber-500"></i>
                </div>
                Synchronize Identity
            </h1>
            <p class="text-slate-500 font-medium mt-2">Modify credentials, roles, or clinical node assignments for <span class="text-slate-900 font-black">{{ $user->name }}</span>.</p>
        </div>
        <div>
            <a href="{{ route('superadmin.users') }}" class="btn-premium-outline !py-3 !px-6 !rounded-2xl !text-xs !bg-white group">
                <i class="fas fa-arrow-left mr-2 text-slate-400 group-hover:text-slate-900 transition-colors"></i>
                Identity Directory
            </a>
        </div>
    </div>

    <div class="relative bg-white rounded-[3rem] border border-slate-100 p-12 shadow-sm overflow-hidden">
        <!-- Decorative Accents -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-amber-50/30 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-slate-50/50 rounded-full blur-[100px] pointer-events-none"></div>

        <form action="{{ route('superadmin.users.update', $user->
    @if(request()->has('iframe'))
        <input type="hidden" name="iframe" value="1">
    @endif
id) }}" method="POST" class="relative z-10">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Identity Section -->
                <div class="col-span-1 md:col-span-2 flex items-center gap-3 mb-2">
                    <div class="w-1.5 h-6 bg-blue-500 rounded-full"></div>
                    <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Core Protocol</h2>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Full Legal Name *</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-user text-slate-300 group-focus-within:text-blue-500 transition-colors text-sm"></i>
                        </div>
                        <input type="text" name="name" required 
                               class="w-full pl-11 pr-4 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 focus:bg-white transition-all text-sm font-bold placeholder-slate-400" 
                               value="{{ old('name', $user->name) }}">
                    </div>
                    @error('name')
                        <p class="text-[10px] font-bold text-rose-500 mt-1.5 ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Network Email Address *</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-300 group-focus-within:text-blue-500 transition-colors text-sm"></i>
                        </div>
                        <input type="email" name="email" required 
                               class="w-full pl-11 pr-4 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 focus:bg-white transition-all text-sm font-bold placeholder-slate-400" 
                               value="{{ old('email', $user->email) }}">
                    </div>
                    @error('email')
                        <p class="text-[10px] font-bold text-rose-500 mt-1.5 ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">New Access Passkey <span class="text-[8px] opacity-70">(Leave blank to keep active)</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-key text-slate-300 group-focus-within:text-blue-500 transition-colors text-sm"></i>
                        </div>
                        <input type="password" name="password" 
                               class="w-full pl-11 pr-4 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 focus:bg-white transition-all text-sm font-bold placeholder-slate-400"
                               placeholder="Modify secure key...">
                    </div>
                    @error('password')
                        <p class="text-[10px] font-bold text-rose-500 mt-1.5 ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Verification Passkey</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-check-double text-slate-300 group-focus-within:text-blue-500 transition-colors text-sm"></i>
                        </div>
                        <input type="password" name="password_confirmation" 
                               class="w-full pl-11 pr-4 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 focus:bg-white transition-all text-sm font-bold placeholder-slate-400"
                               placeholder="Retype new key...">
                    </div>
                </div>

                <!-- Governance Section -->
                <div class="col-span-1 md:col-span-2 mt-4 flex items-center gap-3 mb-2">
                    <div class="w-1.5 h-6 bg-blue-500 rounded-full"></div>
                    <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Authority & Assignment</h2>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Authority Role *</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-user-shield text-slate-300 group-focus-within:text-blue-500 transition-colors text-sm"></i>
                        </div>
                        <select name="role" required 
                                class="w-full pl-11 pr-10 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 focus:bg-white transition-all text-sm font-bold appearance-none cursor-pointer">
                            <option value="">Select identity role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                        </div>
                    </div>
                    @error('role')
                        <p class="text-[10px] font-bold text-rose-500 mt-1.5 ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Assigned Clinic Node</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-hospital text-slate-300 group-focus-within:text-blue-500 transition-colors text-sm"></i>
                        </div>
                        <select name="clinic_id" 
                                class="w-full pl-11 pr-10 py-4 bg-slate-50/50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/30 focus:bg-white transition-all text-sm font-bold appearance-none cursor-pointer text-slate-900">
                            <option value="">No Clinic (Core System Administrator)</option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}" {{ $user->clinic_id == $clinic->id ? 'selected' : '' }}>
                                    {{ $clinic->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                        </div>
                    </div>
                    @error('clinic_id')
                        <p class="text-[10px] font-bold text-rose-500 mt-1.5 ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-12 flex items-center justify-end gap-6 pt-10 border-t border-slate-50">
                <a href="{{ route('superadmin.users') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-900 transition-colors">
                    Discard Changes
                </a>
                <button type="submit" 
                        class="h-14 px-8 bg-slate-900 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 hover:bg-blue-600 transition-all shadow-xl shadow-slate-900/10 hover:shadow-blue-500/30 group">
                    <i class="fas fa-sync text-blue-400 group-hover:rotate-180 transition-transform duration-700"></i>
                    Update Global Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


{{-- Auto-close modal script on success --}}
@if(session('success') && request()->has('iframe'))
    <script>
        setTimeout(() => {
            window.parent.location.reload();
        }, 1500);
    </script>
@endif
