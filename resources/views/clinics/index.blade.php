@extends('layouts.app')

@section('page-title', 'Clinics')

@section('content')
<div class="space-y-8 page-fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                Clinics
                <span class="text-xs font-bold px-2 py-0.5 bg-slate-100 text-slate-500 rounded-md border border-slate-200">
                    {{ $clinics->total() }} Entities
                </span>
            </h1>
            <p class="text-slate-500 mt-1">Manage physical clinics, contact details, and operational status</p>
        </div>
        <a href="{{ route('clinics.create') }}" class="btn-primary flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Add New Clinic
        </a>
    </div>

    @if(session('new_clinic_info'))
        <div class="card bg-blue-50 border-blue-200 shadow-blue-100/50 animate-card-hover overflow-hidden relative">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fas fa-shield-alt text-6xl text-blue-900 rotate-12"></i>
            </div>
            <div class="flex gap-4 items-start relative z-10">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-blue-900">Clinic Admin Provisioned</h3>
                    <p class="text-blue-700 font-medium mt-1">Initial administrative credentials generated. Please share these securely:</p>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white/60 backdrop-blur p-3 rounded-xl border border-blue-200">
                            <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest block mb-1">Login Identity</span>
                            <span class="text-sm font-bold text-slate-900">{{ session('new_clinic_info.email') }}</span>
                        </div>
                        <div class="bg-white/60 backdrop-blur p-3 rounded-xl border border-blue-200">
                            <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest block mb-1">Access URL</span>
                            <a href="{{ session('new_clinic_info.login_url') }}" class="text-sm font-bold text-blue-600 hover:underline flex items-center">
                                Portal Access <i class="fas fa-external-link-alt ml-2 text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-in">
        @forelse($clinics as $clinic)
            <div class="card group relative p-0 overflow-hidden flex flex-col h-full bg-white border-slate-200 hover:border-blue-400">
                <div class="p-6 flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-inner group-hover:shadow-lg group-hover:shadow-blue-600/30">
                            <i class="fas fa-clinic-medical text-2xl"></i>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border
                            {{ $clinic->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100' }}">
                            {{ $clinic->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-black text-slate-900 mb-1 group-hover:text-blue-600 transition-colors">{{ $clinic->name }}</h3>
                    <div class="space-y-2 mt-4">
                        <div class="flex items-center text-sm text-slate-500 font-medium">
                            <i class="fas fa-envelope w-5 text-slate-300"></i>
                            {{ $clinic->email }}
                        </div>
                        <div class="flex items-center text-sm text-slate-500 font-medium">
                            <i class="fas fa-phone w-5 text-slate-300"></i>
                            {{ $clinic->phone }}
                        </div>
                        <div class="flex items-start text-sm text-slate-500 font-medium">
                            <i class="fas fa-map-marker-alt w-5 mt-1 text-slate-300"></i>
                            <span class="line-clamp-2">{{ $clinic->address }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-2">
                    <a href="{{ route('clinics.show', $clinic) }}" class="flex-1 btn-secondary py-2 text-xs flex items-center justify-center gap-2">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="{{ route('clinics.edit', $clinic) }}" class="flex-1 btn-secondary py-2 text-xs text-indigo-600 hover:text-indigo-700 flex items-center justify-center gap-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full card py-16 text-center">
                <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-hospital-alt text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">No clinics found</h3>
                <p class="text-slate-500 max-w-xs mx-auto mb-8">Start growing your network by adding your first dental practice location.</p>
                <a href="{{ route('clinics.create') }}" class="btn-primary">Add New Clinic</a>
            </div>
        @endforelse
    </div>

    @if($clinics->hasPages())
        <div class="mt-8 font-bold">
            {{ $clinics->links() }}
        </div>
    @endif
</div>
@endsection