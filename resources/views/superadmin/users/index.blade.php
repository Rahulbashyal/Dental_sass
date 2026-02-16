@extends('layouts.app')

@section('page-title', 'User Management')

@section('content')
<div class="space-y-8 page-fade-in">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                User Directory
                <span class="text-xs font-bold px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md border border-blue-100">
                    {{ number_format(App\Models\User::count()) }} Total
                </span>
            </h1>
            <p class="text-slate-500 mt-1">Manage global access, clinic staff, and system administrators</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('superadmin.dashboard') }}" class="btn-secondary flex items-center gap-2">
                <i class="fas fa-arrow-left text-slate-400"></i>
                Dashboard
            </a>
            <a href="{{ route('superadmin.users.create') }}" class="btn-primary flex items-center gap-2">
                <i class="fas fa-user-plus"></i>
                Add Global User
            </a>
        </div>
    </div>

    <!-- Analytics Dashboard for Users -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 stagger-in">
        <div class="stat-card group">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-shield-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Core Admins</p>
                    <p class="text-2xl font-black text-slate-900">{{ App\Models\User::whereNull('clinic_id')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-hospital-user text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Clinic Staff</p>
                    <p class="text-2xl font-black text-slate-900">{{ App\Models\User::whereNotNull('clinic_id')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-user-injured text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Patients</p>
                    <p class="text-2xl font-black text-slate-900">{{ App\Models\Patient::count() }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card group">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-clinic-medical text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Units</p>
                    <p class="text-2xl font-black text-slate-900">{{ $clinics->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Core System Users -->
    @php
        $coreUsers = App\Models\User::whereNull('clinic_id')->with('roles')->get();
    @endphp
    @if($coreUsers->count() > 0)
    <div class="space-y-4">
        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
            <i class="fas fa-microchip text-rose-500"></i>
            System Infrastructure Users
        </h2>
        <div class="card p-0 overflow-hidden border-rose-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-rose-50/30">
                            <th class="px-6 py-4 text-left text-[10px] font-black text-rose-900 uppercase tracking-widest">Identity</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-rose-900 uppercase tracking-widest">Contact Details</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-rose-900 uppercase tracking-widest">Password</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-rose-900 uppercase tracking-widest">Authorities</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-rose-900 uppercase tracking-widest">System Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($coreUsers as $user)
                            <tr class="hover:bg-rose-50/20 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 font-black border-2 border-white shadow-sm">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-extrabold text-slate-900">{{ $user->name }}</div>
                                            <div class="text-[10px] font-bold text-slate-400">UID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-600 font-medium">{{ $user->email }}</div>
                                    <div class="text-xs text-slate-400 italic">{{ $user->phone ?? 'Unmapped Device' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-black text-slate-700 flex items-center gap-2">
                                        <span class="text-lg">••••••••</span>
                                        <button type="button" 
                                            onclick="navigator.clipboard.writeText('password').then(() => { alert('Password copied!'); })"
                                            class="ml-2 px-2 py-1 bg-slate-100 hover:bg-emerald-100 text-slate-400 hover:text-emerald-600 rounded text-xs font-bold transition-colors"
                                            title="Copy password">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        @foreach($user->roles as $role)
                                            <span class="px-2 py-0.5 rounded-md bg-rose-100 text-rose-800 text-[9px] font-black uppercase tracking-tighter border border-rose-200 shadow-sm">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border shadow-sm
                                        {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100' }}">
                                        {{ $user->is_active ? 'Online' : 'Restricted' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 flex items-center gap-2">
                    <i class="fas fa-info-circle text-rose-400"></i>
                    These users bypass per-tenant multi-tenancy constraints and maintain global authority across the platform.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Clinic Node Directory -->
    <div class="space-y-4 pt-4">
        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
            <i class="fas fa-network-wired text-blue-500"></i>
            Operational Clinics staff
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 stagger-in">
            @foreach($clinics as $clinic)
                <div class="card group p-0 overflow-hidden border-slate-200 hover:border-blue-400 transition-all duration-300">
                    <div class="p-5 border-b border-slate-50 bg-slate-50/50">
                        <div class="flex justify-between items-start">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-black text-slate-900 group-hover:text-blue-600 transition-colors truncate">{{ $clinic->name }}</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ $clinic->city ?? 'Central Node' }}
                                </p>
                            </div>
                            <span class="w-2.5 h-2.5 rounded-full shadow-sm {{ $clinic->is_active ? 'bg-emerald-500 shadow-emerald-200' : 'bg-rose-500 shadow-rose-200' }}"></span>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <div class="flex items-end justify-between">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Active Personnel</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $clinic->users_count }}</span>
                                    <span class="text-xs font-bold text-slate-400">members</span>
                                </div>
                            </div>
                            <div class="flex -space-x-2">
                                @for($i = 0; $i < min($clinic->users_count, 3); $i++)
                                    <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-200 flex items-center justify-center text-[10px] font-black text-slate-500 shadow-sm">
                                        {{ chr(65 + $i) }}
                                    </div>
                                @endfor
                                @if($clinic->users_count > 3)
                                    <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-600 flex items-center justify-center text-[8px] font-black text-white shadow-sm italic">
                                        +{{ $clinic->users_count - 3 }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 flex gap-2">
                            <a href="{{ route('superadmin.users.clinic', ['clinic' => $clinic->id]) }}" 
                               class="flex-1 h-10 bg-slate-900 text-white rounded-lg text-xs font-bold flex items-center justify-center gap-2 hover:bg-blue-600 transition-colors shadow-lg shadow-slate-900/10">
                                <i class="fas fa-users-cog"></i> View Portal
                            </a>
                            <a href="{{ route('superadmin.users.create', ['clinic_id' => $clinic->id]) }}" 
                               class="w-10 h-10 bg-white border border-slate-200 text-slate-400 rounded-lg flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition-all shadow-sm"
                               title="Quick Enroll Member">
                                <i class="fas fa-plus text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
