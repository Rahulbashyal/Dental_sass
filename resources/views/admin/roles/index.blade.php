@extends('layouts.app')

@section('page-title', 'Role Management')

@section('content')
<div class="space-y-8 page-fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                Role Management
                <span class="text-xs font-bold px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-md border border-indigo-100">
                    {{ $roles->count() }} Definitions
                </span>
            </h1>
            <p class="text-slate-500 mt-1">Configure system access levels and granular permission mappings</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="btn-primary flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Create New Role
        </a>
    </div>

    <!-- Active Roles Grid -->
    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 stagger-in">
        @foreach($roles as $role)
            <div class="card group relative flex flex-col justify-between overflow-hidden border-slate-200 hover:border-indigo-400">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 opacity-20 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="space-y-6">
                    <!-- Role Header -->
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                @if($role->name === 'superadmin')
                                    <i class="fas fa-crown"></i>
                                @elseif($role->name === 'clinic_admin')
                                    <i class="fas fa-user-shield"></i>
                                @elseif($role->name === 'dentist')
                                    <i class="fas fa-user-md"></i>
                                @elseif($role->name === 'receptionist')
                                    <i class="fas fa-user-tag"></i>
                                @else
                                    <i class="fas fa-users-cog"></i>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 tracking-tight">{{ $role->display_name ?? ucwords(str_replace('_', ' ', $role->name)) }}</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-0.5">{{ str_replace('_', ' ', $role->name) }}</p>
                            </div>
                        </div>

                        <!-- Mini Actions -->
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.roles.edit', $role) }}" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white flex items-center justify-center transition-all duration-200" title="Edit Permissions">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            @if(!in_array($role->name, ['superadmin', 'clinic_admin']))
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-400 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-all duration-200" onclick="return confirm('Delete this role?')" title="Delete Role">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <p class="text-sm text-slate-500 font-medium leading-relaxed line-clamp-2 min-h-[2.5rem]">
                        {{ $role->description ?? 'No functional description provided for this role.' }}
                    </p>

                    <!-- Permissions Summary -->
                    <div class="pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Capabilities</span>
                            <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-tighter shadow-sm border border-indigo-100">
                                {{ $role->permissions->count() }} Total
                            </span>
                        </div>
                        
                        <div class="flex flex-wrap gap-1.5 h-16 overflow-hidden">
                            @forelse($role->permissions->take(6) as $permission)
                                <span class="inline-flex items-center px-2 py-1 rounded-md bg-slate-50 text-slate-600 text-[10px] font-bold border border-slate-100 uppercase tracking-tighter">
                                    {{ str_replace('_', ' ', $permission->name) }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-400 italic">Global unrestricted access</span>
                            @endforelse
                            
                            @if($role->permissions->count() > 6)
                                <span class="inline-flex items-center px-2 py-1 rounded-md bg-indigo-50 text-indigo-600 text-[10px] font-bold border border-indigo-100">
                                    +{{ $role->permissions->count() - 6 }} More
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Dual Role Assignment Section -->
    <div class="card bg-slate-900 border-none shadow-2xl relative overflow-hidden p-8 md:p-12">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-indigo-500/10 to-transparent pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-[10px] font-black uppercase tracking-widest border border-indigo-500/30 mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                    Instant Provisioning
                </div>
                <h2 class="text-3xl font-black text-white leading-tight">Assign Role to User</h2>
                <p class="text-indigo-200/70 mt-4 text-lg font-medium leading-relaxed">
                    Update user access levels instantly. Changes apply to the next request without requiring user re-authentication.
                </p>
            </div>

            <div class="lg:col-span-7">
                <form action="{{ route('admin.roles.assign') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="user_id" class="text-[10px] font-black text-indigo-300 uppercase tracking-widest block mb-1.5 ml-1">Target User</label>
                            <select id="user_id" name="user_id" class="w-full h-12 bg-white/5 border-white/10 rounded-xl text-white font-medium focus:ring-indigo-500 focus:border-indigo-500 px-4" required>
                                <option value="" class="text-slate-900 bg-white">Select identifiable user...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" class="text-slate-900 bg-white">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="role_id" class="text-[10px] font-black text-indigo-300 uppercase tracking-widest block mb-1.5 ml-1">New Authority</label>
                            <select id="role_id" name="role_id" class="w-full h-12 bg-white/5 border-white/10 rounded-xl text-white font-medium focus:ring-indigo-500 focus:border-indigo-500 px-4" required>
                                <option value="" class="text-slate-900 bg-white">Select role level...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" class="text-slate-900 bg-white">{{ $role->display_name ?? ucwords(str_replace('_', ' ', $role->name)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full h-14 bg-indigo-600 hover:bg-white hover:text-indigo-900 text-white font-black uppercase tracking-widest rounded-xl shadow-lg shadow-indigo-600/20 transition-all duration-300 flex items-center justify-center gap-3 mt-2">
                        Update Access Permissions
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection