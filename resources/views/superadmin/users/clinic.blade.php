@extends('layouts.app')

@section('page-title', $clinic->name . ' - User Directory')

@section('content')
<div class="space-y-8 page-fade-in">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('superadmin.users') }}" class="w-10 h-10 bg-slate-100 text-slate-500 rounded-xl flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all duration-300 shadow-sm group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                    {{ $clinic->name }}
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm
                        {{ $clinic->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100' }}">
                        {{ $clinic->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </h1>
                <p class="text-slate-500 mt-1 font-medium">{{ $clinic->address ?? 'Primary Clinical Node' }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('superadmin.users.create', ['clinic_id' => $clinic->id]) }}" class="btn-primary flex items-center gap-2">
                <i class="fas fa-user-plus"></i>
                Add User
            </a>
        </div>
    </div>

    <!-- Clinic Node Metrics -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 stagger-in">
        <div class="stat-card">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Personnel</p>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-black text-slate-900">{{ $users->total() }}</span>
                <i class="fas fa-users text-slate-100 text-4xl -mb-2"></i>
            </div>
        </div>
        <div class="stat-card">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Online Status</p>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-black text-emerald-600">{{ $clinic->users()->where('is_active', true)->count() }}</span>
                <i class="fas fa-signal text-emerald-100 text-4xl -mb-2"></i>
            </div>
        </div>
        <div class="stat-card">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Dental Experts</p>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-black text-blue-600">{{ $clinic->users()->role('dentist')->count() }}</span>
                <i class="fas fa-user-md text-blue-100 text-4xl -mb-2"></i>
            </div>
        </div>
        <div class="stat-card">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Administrative</p>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-black text-purple-600">{{ $clinic->users()->role('clinic_admin')->count() }}</span>
                <i class="fas fa-user-shield text-purple-100 text-4xl -mb-2"></i>
            </div>
        </div>
    </div>

    <!-- Users Directory Table -->
    <div class="card p-0 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <i class="fas fa-list text-slate-400"></i>
                Member Directory
            </h3>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest bg-white px-3 py-1 rounded-lg border border-slate-100 shadow-sm">
                Real-time Node Status
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Index</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Full Identity</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest hidden md:table-cell">Global Credentials</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Applied Roles</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Authority Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($users as $index => $user)
                        <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                            <td class="px-6 py-5 whitespace-nowrap text-xs font-black text-slate-300">
                                {{ sprintf('%02d', $users->firstItem() + $index) }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black border border-blue-100 shadow-inner group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-extrabold text-slate-900">{{ $user->name }}</div>
                                        <div class="text-[10px] font-bold text-slate-400 mt-0.5">MEMBER SINCE {{ $user->created_at->format('Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap hidden md:table-cell">
                                <div class="text-sm text-slate-600 font-bold tracking-tight">{{ $user->email }}</div>
                                <div class="text-xs text-slate-400 font-medium">{{ $user->phone ?? 'NO MOBILE REGISTERED' }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-tighter border shadow-sm
                                            @if($role->name == 'superadmin') bg-rose-50 text-rose-700 border-rose-100
                                            @elseif($role->name == 'clinic_admin') bg-purple-50 text-purple-700 border-purple-100
                                            @elseif($role->name == 'dentist') bg-blue-50 text-blue-700 border-blue-100
                                            @elseif($role->name == 'accountant') bg-emerald-50 text-emerald-700 border-emerald-100
                                            @elseif($role->name == 'receptionist') bg-amber-50 text-amber-700 border-amber-100
                                            @else bg-slate-50 text-slate-700 border-slate-100
                                            @endif">
                                            {{ str_replace('_', ' ', $role->name) }}
                                        </span>
                                    @empty
                                        <span class="text-[10px] text-slate-400 font-bold italic">Unassigned Level</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border shadow-sm
                                    {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-2 {{ $user->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $user->is_active ? 'Active' : 'Restricted' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 text-slate-200 rounded-2xl flex items-center justify-center mb-4">
                                        <i class="fas fa-users-slash text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-black text-slate-900">Zero personnel mapped</h3>
                                    <p class="text-slate-500 max-w-xs mx-auto text-sm font-medium mt-1">This node currently has no active human members assigned to its operational structure.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
