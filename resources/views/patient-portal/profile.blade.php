@extends('patient-portal.layout')

@section('title', 'Global Account Identity')

@section('content')
<div class="max-w-7xl mx-auto space-y-10 page-fade-in pb-20">
    @php
        $patient = Auth::guard('patient')->user();
        $clinic = $patient ? $patient->clinic : null;
        $clinicColor = $clinic && $clinic->primary_color ? $clinic->primary_color : '#0ea5e9';
    @endphp

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="space-y-1">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-4 Outfit">
                <span class="w-2 h-8 rounded-full" style="background-color: {{ $clinicColor }}"></span>
                Account Identity
            </h1>
            <p class="text-slate-500 font-medium">Manage your clinical record, credentials, and contact preferences.</p>
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100 uppercase tracking-widest text-xs font-black">
            <i class="fas fa-check-shield"></i> Validated Profile
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 stagger-in">
        <!-- Profile Sidebar Panel -->
        <div class="lg:col-span-4 space-y-10">
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden relative group transition-all duration-300 hover:shadow-xl hover:shadow-slate-200/50">
                <div class="h-32 relative" style="background-color: {{ $clinicColor }}">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="absolute -bottom-1 w-full h-8 bg-gradient-to-t from-white to-transparent"></div>
                </div>
                
                <div class="px-8 pb-8 flex flex-col items-center">
                    <div class="relative -mt-16 group/photo">
                        <div class="w-32 h-32 rounded-[2.5rem] bg-white p-1 shadow-2xl relative z-10 overflow-hidden">
                            @if($patient->photo)
                                <img src="{{ asset('storage/' . $patient->photo) }}" alt="Profile Photo" class="w-full h-full rounded-[2.2rem] object-cover">
                            @else
                                <div class="w-full h-full rounded-[2.2rem] bg-slate-50 flex items-center justify-center text-slate-400 text-4xl font-black border border-slate-100 Outfit">
                                    {{ substr($patient->first_name, 0, 1) }}
                                </div>
                            @endif
                            <!-- Photo Upload Overlay -->
                            <form action="{{ route('patient.profile.photo') }}" method="POST" enctype="multipart/form-data" class="absolute inset-0 opacity-0 group-hover/photo:opacity-100 transition-opacity duration-300">
                                @csrf
                                <label for="photo-upload" class="w-full h-full bg-slate-900/60 backdrop-blur-sm flex flex-col items-center justify-center text-white cursor-pointer px-4 text-center">
                                    <i class="fas fa-camera text-2xl mb-1"></i>
                                    <span class="text-xs font-black uppercase tracking-widest leading-tight">Sync New Portrait</span>
                                </label>
                                <input type="file" name="photo" id="photo-upload" class="hidden" onchange="this.form.submit()">
                            </form>
                        </div>
                        <div class="absolute -inset-2 bg-slate-400/20 rounded-full blur-xl group-hover/photo:bg-sky-500/20 transition-all"></div>
                    </div>

                    <div class="text-center mt-6">
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight Outfit">{{ $patient->first_name }} {{ $patient->last_name }}</h2>
                        <div class="inline-flex items-center gap-2 mt-3 px-4 py-1.5 bg-slate-50 rounded-full border border-slate-100">
                            <i class="fas fa-fingerprint text-xs text-slate-300"></i>
                            <span class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ $patient->patient_id }}</span>
                        </div>
                    </div>
                    
                    <div class="w-full mt-10 grid grid-cols-2 gap-4">
                        <div class="bg-slate-50/50 p-4 rounded-3xl border border-slate-100 text-center transition-all hover:bg-white hover:shadow-sm">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Clinic Access</p>
                            <p class="text-xs font-black text-slate-800 Outfit">Standard</p>
                        </div>
                        <div class="bg-slate-50/50 p-4 rounded-3xl border border-slate-100 text-center transition-all hover:bg-white hover:shadow-sm">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Records</p>
                            <p class="text-xs font-black text-slate-800 Outfit">Synchronized</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Badge -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden group shadow-xl shadow-slate-900/40">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-2xl group-hover:scale-125 transition-all duration-700"></div>
                <div class="relative z-10 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-white/40 uppercase tracking-widest">Security Protocol</span>
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-lg shadow-emerald-500/50"></div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-white/5 pb-4">
                            <span class="text-xs font-bold text-white/60">Encryption Node</span>
                            <span class="text-[10px] font-black uppercase text-white Outfit">AES-GCM-256</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-white/5 pb-4">
                            <span class="text-xs font-bold text-white/60">Session State</span>
                            <span class="text-xs font-black uppercase text-white Outfit">Verified</span>
                        </div>
                    </div>
                    <p class="text-[9px] font-bold text-white/40 text-center">Account data resides in an encrypted clinical environment.</p>
                </div>
            </div>
        </div>

        <!-- Master Information Panel -->
        <div class="lg:col-span-8 space-y-10">
            <!-- Registry Data -->
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden backdrop-blur-xl">
                <div class="px-10 py-6 border-b border-slate-50 bg-slate-50/30 flex justify-between items-center">
                    <h3 class="text-lg font-black text-slate-900 flex items-center gap-3 Outfit">
                        <i class="fas fa-database text-slate-400 text-sm"></i>
                        Clinical Registry
                    </h3>
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Immutable Fields</span>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-2">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Identity Email</p>
                        <p class="text-base font-black text-slate-800 Outfit">{{ $patient->email }}</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Date of Birth</p>
                        <p class="text-base font-black text-slate-800 Outfit">{{ $patient->date_of_birth ?? 'Registry Empty' }}</p>
                    </div>
                    <div class="md:col-span-2 space-y-3">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Medical Context & Alerts</p>
                        <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 flex gap-4">
                            <div class="w-10 h-10 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center text-rose-500">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-slate-600 font-bold leading-relaxed">
                                    {{ $patient->medical_history ?? 'No systemic alerts established in clinical records.' }}
                                </p>
                                <p class="text-[11px] text-rose-500 font-black uppercase mt-2 tracking-tighter">
                                    Allergies: {{ $patient->allergies ?? 'Nil Recorded' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Vectors -->
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:border-slate-300">
                <div class="px-10 py-6 border-b border-slate-50 flex justify-between items-center">
                    <h3 class="text-lg font-black text-slate-900 flex items-center gap-3 Outfit">
                        <i class="fas fa-edit text-slate-400 text-sm"></i>
                        Synchronization Settings
                    </h3>
                </div>
                <form action="{{ route('patient.profile.update') }}" method="POST" class="p-10 space-y-10">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                        <div class="space-y-3">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Primary Mobile Liaison</label>
                            <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}" 
                                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-[1.5rem] focus:outline-none focus:ring-4 focus:ring-slate-100 focus:bg-white transition-all font-black text-slate-800 Outfit"
                                placeholder="+977 98XXXXXXXX">
                        </div>
                        <div class="space-y-3">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Physical Residence Node</label>
                            <input type="text" name="address" value="{{ old('address', $patient->address) }}" 
                                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-[1.5rem] focus:outline-none focus:ring-4 focus:ring-slate-100 focus:bg-white transition-all font-black text-slate-800 Outfit"
                                placeholder="Street, City, Nepal">
                        </div>
                        <div class="space-y-3">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Emergency Liaison Name</label>
                            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" 
                                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-[1.5rem] focus:outline-none focus:ring-4 focus:ring-slate-100 focus:bg-white transition-all font-black text-slate-800 Outfit"
                                placeholder="Full Name">
                        </div>
                        <div class="space-y-3">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Emergency Liaison Vector</label>
                            <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" 
                                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-[1.5rem] focus:outline-none focus:ring-4 focus:ring-slate-100 focus:bg-white transition-all font-black text-slate-800 Outfit"
                                placeholder="Phone Number">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="w-full md:w-auto px-10 py-5 rounded-[1.5rem] text-xs font-black text-white uppercase tracking-widest flex items-center justify-center gap-3 shadow-lg shadow-slate-100 transform active:scale-95 transition-all"
                                style="background-color: {{ $clinicColor }}">
                            Sync Account Profile
                            <i class="fas fa-sync-alt text-sm"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
