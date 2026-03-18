@extends('layouts.app')

@section('page-title', $clinic->name . ' - User Directory')

@section('content')
<div class="space-y-8 page-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
        <div class="flex items-center gap-6">
            <a href="{{ route('superadmin.users') }}" class="w-14 h-14 bg-white border border-slate-100 text-slate-400 rounded-2xl flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all duration-500 shadow-sm group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
                    {{ $clinic->name }}
                    <span class="inline-flex items-center px-4 py-2 rounded-2xl text-[9px] font-black uppercase tracking-[0.15em] border shadow-xs
                        {{ $clinic->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                        <span class="w-2 h-2 rounded-full mr-2.5 {{ $clinic->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }}"></span>
                        {{ $clinic->is_active ? 'Online Node' : 'Inactive' }}
                    </span>
                </h1>
                <p class="text-slate-500 mt-2 font-medium flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-blue-500/50"></i>
                    {{ $clinic->address ?? 'Primary Clinical Node' }}
                </p>
            </div>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('superadmin.users.create', ['clinic_id' => $clinic->id], 'iframe' => 1) }}" data-modal-url="{{ route('superadmin.users') }}" class="w-14 h-14 bg-white border border-slate-100 text-slate-400 rounded-2xl flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all duration-500 shadow-sm group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
                    {{ $clinic->name }}
                    <span class="inline-flex items-center px-4 py-2 rounded-2xl text-[9px] font-black uppercase tracking-[0.15em] border shadow-xs
                        {{ $clinic->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                        <span class="w-2 h-2 rounded-full mr-2.5 {{ $clinic->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }}"></span>
                        {{ $clinic->is_active ? 'Online Node' : 'Inactive' }}
                    </span>
                </h1>
                <p class="text-slate-500 mt-2 font-medium flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-blue-500/50"></i>
                    {{ $clinic->address ?? 'Primary Clinical Node' }}
                </p>
            </div>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('superadmin.users.create', ['clinic_id' => $clinic->id], 'iframe' => 1) }}" data-modal-title="Provision Member
            
        
    

    
    
        
        
            
            
                
                    Personnel
                    {{ $users->total() }}
                
                
                    
                
            
        

        
        
            
            
                
                    Live Nodes
                    {{ $clinic->users()->where('is_active', true)->count() }}
                
                
                    
                
            
        

        
        
            
            
                
                    Experts
                    {{ $clinic->users()->role('dentist')->count() }}
                
                
                    
                
            
        

        
        
            
            
                
                    Kernels
                    {{ $clinic->users()->role('clinic_admin')->count() }}
                
                
                    
                
            
        
    

    
    
        
            
                
            
            Node Member Directory
        
        
            
            
            
            
                
                    
                        
                            ID
                            Identity Profile
                            Credentials
                            Authorities
                            Live Status
                        
                    
                    
                        @forelse($users as $index => $user)
                            
                                
                                    {{ sprintf('%02d', $users->firstItem() + $index) }}
                                
                                
                                    
                                        
                                            {{ substr($user->name, 0, 1) }}
                                        
                                        
                                            {{ $user->name }}
                                            Since {{ $user->created_at->format('M Y') }}
                                        
                                    
                                
                                
                                    {{ $user->email }}
                                    {{ $user->phone ?? 'NO MOBILE REGISTERED' }}
                                
                                
                                    
                                        @forelse($user->roles as $role)
                                            name == 'superadmin') bg-indigo-50 text-indigo-700 border-indigo-100
                                                @elseif($role->name == 'clinic_admin') bg-purple-50 text-purple-700 border-purple-100
                                                @elseif($role->name == 'dentist') bg-blue-50 text-blue-700 border-blue-100
                                                @elseif($role->name == 'accountant') bg-emerald-50 text-emerald-700 border-emerald-100
                                                @elseif($role->name == 'receptionist') bg-amber-50 text-amber-700 border-amber-100
                                                @else bg-slate-50 text-slate-700 border-slate-100
                                                @endif">
                                                {{ str_replace('_', ' ', $role->name) }}
                                            
                                        @empty
                                            Unassigned Level
                                        @endforelse
                                    
                                
                                
                                    is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                        is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }}">
                                        {{ $user->is_active ? 'Active Node' : 'Restricted' }}
                                    
                                
                            
                        @empty
                            
                                
                                    
                                        
                                            
                                        
                                        Zero Personnel Mapped
                                        This node currently has no active human members assigned to its operational structure.
                                         $clinic->id]) }}" class="mt-8 btn-premium-primary !py-4 !px-8 !rounded-2xl !text-[10px] !bg-slate-900 !text-white flex items-center gap-3 hover:!bg-blue-600 transition-all shadow-xl shadow-slate-900/10">
                                            
                                            Add First Member" class="btn-premium-primary !py-3 !px-6 !rounded-2xl !text-xs !bg-slate-900 !text-white hover:!bg-blue-600 shadow-xl shadow-slate-900/10">
                <i class="fas fa-user-plus mr-2 text-blue-400"></i>
                Provision Member
            </a>
        </div>
    </div>

    <!-- Clinic Node Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 stagger-in mb-12">
        <!-- Total Personnel -->
        <div class="group relative bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden">
            <div class="absolute -top-6 -right-6 w-32 h-32 bg-blue-50/50 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Personnel</p>
                    <p class="text-4xl font-black text-slate-900 tracking-tight">{{ $users->total() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-500/30 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Online Status -->
        <div class="group relative bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden">
            <div class="absolute -top-6 -right-6 w-32 h-32 bg-emerald-50/50 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Live Nodes</p>
                    <p class="text-4xl font-black text-emerald-600 tracking-tight">{{ $clinic->users()->where('is_active', true)->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-signal text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Dental Experts -->
        <div class="group relative bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden">
            <div class="absolute -top-6 -right-6 w-32 h-32 bg-indigo-50/50 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Experts</p>
                    <p class="text-4xl font-black text-blue-600 tracking-tight">{{ $clinic->users()->role('dentist')->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-500/30 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-user-md text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Administrative -->
        <div class="group relative bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden">
            <div class="absolute -top-6 -right-6 w-32 h-32 bg-purple-50/50 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Kernels</p>
                    <p class="text-4xl font-black text-purple-600 tracking-tight">{{ $clinic->users()->role('clinic_admin')->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-plum-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-purple-500/30 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Directory Table -->
    <div class="space-y-6">
        <h2 class="text-xl font-black text-slate-900 flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fas fa-list text-blue-500"></i>
            </div>
            Node Member Directory
        </h2>
        <div class="relative bg-white rounded-[3rem] border border-slate-100 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-700">
            <!-- Decorative Accents -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-50/30 rounded-full blur-[100px] pointer-events-none"></div>
            
            <div class="overflow-x-auto relative z-10">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead>
                        <tr class="bg-slate-50/40">
                            <th class="px-10 py-6 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">ID</th>
                            <th class="px-10 py-6 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Identity Profile</th>
                            <th class="px-10 py-6 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] hidden md:table-cell">Credentials</th>
                            <th class="px-10 py-6 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Authorities</th>
                            <th class="px-10 py-6 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Live Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-blue-50/[0.1] transition-all duration-300 group">
                                <td class="px-10 py-8 whitespace-nowrap text-[10px] font-black text-slate-200 group-hover:text-blue-500 transition-colors">
                                    {{ sprintf('%02d', $users->firstItem() + $index) }}
                                </td>
                                <td class="px-10 py-8 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-black shadow-lg shadow-blue-500/20 transition-transform group-hover:scale-110 duration-500 text-lg">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-5">
                                            <div class="text-base font-black text-slate-900">{{ $user->name }}</div>
                                            <div class="text-[10px] font-bold text-slate-400 mt-0.5 tracking-widest uppercase italic">Since {{ $user->created_at->format('M Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 whitespace-nowrap hidden md:table-cell">
                                    <div class="text-sm text-slate-600 font-bold group-hover:text-blue-600 transition-colors tracking-tight">{{ $user->email }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold tracking-widest mt-1 uppercase">{{ $user->phone ?? 'NO MOBILE REGISTERED' }}</div>
                                </td>
                                <td class="px-10 py-8 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($user->roles as $role)
                                            <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border shadow-sm
                                                @if($role->name == 'superadmin') bg-indigo-50 text-indigo-700 border-indigo-100
                                                @elseif($role->name == 'clinic_admin') bg-purple-50 text-purple-700 border-purple-100
                                                @elseif($role->name == 'dentist') bg-blue-50 text-blue-700 border-blue-100
                                                @elseif($role->name == 'accountant') bg-emerald-50 text-emerald-700 border-emerald-100
                                                @elseif($role->name == 'receptionist') bg-amber-50 text-amber-700 border-amber-100
                                                @else bg-slate-50 text-slate-700 border-slate-100
                                                @endif">
                                                {{ str_replace('_', ' ', $role->name) }}
                                            </span>
                                        @empty
                                            <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest bg-slate-50 px-3 py-1 rounded-lg border border-slate-100 shadow-xs italic">Unassigned Level</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-10 py-8 whitespace-nowrap">
                                    <span class="inline-flex items-center px-4 py-2 rounded-2xl text-[9px] font-black uppercase tracking-[0.15em] border shadow-xs
                                        {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                        <span class="w-2 h-2 rounded-full mr-2.5 {{ $user->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }}"></span>
                                        {{ $user->is_active ? 'Active Node' : 'Restricted' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-32 text-center">
                                    <div class="flex flex-col items-center justify-center stagger-in">
                                        <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center mb-6 shadow-inner border border-slate-100 group">
                                            <i class="fas fa-users-slash text-4xl text-slate-200 group-hover:scale-110 group-hover:text-blue-500/20 transition-all duration-700"></i>
                                        </div>
                                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Zero Personnel Mapped</h3>
                                        <p class="text-slate-500 max-w-sm mx-auto text-sm font-medium mt-2">This node currently has no active human members assigned to its operational structure.</p>
                                        <a href="{{ route('superadmin.users.create', ['clinic_id' => $clinic->id]) }}" class="mt-8 btn-premium-primary !py-4 !px-8 !rounded-2xl !text-[10px] !bg-slate-900 !text-white flex items-center gap-3 hover:!bg-blue-600 transition-all shadow-xl shadow-slate-900/10">
                                            <i class="fas fa-user-plus text-blue-400"></i>
                                            Add First Member
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
                <div class="px-10 py-6 bg-slate-50/50 border-t border-slate-50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
