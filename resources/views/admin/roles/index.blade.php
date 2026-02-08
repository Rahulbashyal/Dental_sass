@extends('layouts.app')

@section('page-title', 'Role Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Top Configuration Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-indigo-600 tracking-tight">
                Role Management
            </h2>
            <p class="mt-2 text-base text-gray-600">
                Manage user access levels, configure permissions, and assign roles.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.roles.create') }}" class="group relative whitespace-nowrap inline-flex items-center justify-center gap-2 overflow-hidden rounded-xl bg-indigo-600 px-6 py-3.5 text-sm font-bold text-white shadow-xl shadow-indigo-200 transition-all duration-300 hover:bg-indigo-700 hover:shadow-2xl hover:shadow-indigo-300 hover:-translate-y-1">
                <button 
    class="group inline-flex items-center gap-3 px-6 py-3 
           bg-emerald-600 hover:bg-emerald-700 
           text-white font-semibold rounded-xl 
           shadow-md hover:shadow-lg 
           transition-all duration-300 ease-in-out">
    
    <svg class="h-5 w-5 transition-transform duration-300 group-hover:rotate-90"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor"
         stroke-width="2">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 4v16m8-8H4" />
    </svg>

    <span>Create New Role</span>
</button>

            </a>
        </div>
    </div>

    <!-- Active Roles Grid -->
    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 mb-16">
        @foreach($roles as $role)
        <div class="group relative flex flex-col justify-between overflow-hidden rounded-2xl bg-white border border-gray-100 shadow-md transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-100 hover:-translate-y-1">
            <!-- Decorative Gradient Bar -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 opacity-75 group-hover:opacity-100 transition-opacity"></div>
            
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 text-indigo-600 shadow-inner group-hover:scale-110 transition-transform duration-300">
                            <!-- Icon based on role name for variety -->
                            @if($role->name === 'superadmin')
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            @elseif($role->name === 'receptionist')
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            @else
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 tracking-tight">{{ $role->display_name ?? ucwords(str_replace('_', ' ', $role->name)) }}</h3>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mt-0.5">{{ str_replace('_', ' ', $role->name) }}</p>
                        </div>
                    </div>

                    <!-- Action Menu -->
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="p-2 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all" title="Edit Role">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </a>
                        @if(!in_array($role->name, ['superadmin', 'clinic_admin']))
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all" onclick="return confirm('Are you sure you want to delete this role? This will remove permissions for all assigned users.')" title="Delete Role">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <p class="mt-4 text-sm text-gray-600 leading-relaxed line-clamp-2 h-10">
                    {{ $role->description ?? 'No description provided.' }}
                </p>

                <!-- Permissions Pills (Restored) -->
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Permissions</span>
                        <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-bold text-indigo-700">
                            {{ $role->permissions->count() }}
                        </span>
                    </div>
                    
                    <div class="flex flex-wrap gap-2 h-16 overflow-hidden content-start">
                        @forelse($role->permissions->take(4) as $permission)
                        <span class="inline-flex items-center rounded-lg bg-gray-50 px-2.5 py-1.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-200">
                            {{ str_replace('_', ' ', $permission->name) }}
                        </span>
                        @empty
                        <span class="text-sm text-gray-400 italic">No permissions assigned</span>
                        @endforelse
                        
                        @if($role->permissions->count() > 4)
                        <span class="inline-flex items-center rounded-lg bg-gray-50 px-2.5 py-1.5 text-xs font-medium text-gray-500 ring-1 ring-inset ring-gray-200">
                            +{{ $role->permissions->count() - 4 }} more
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Quick Assignment Section (Restored Gradient) -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-900 via-blue-900 to-indigo-900 shadow-2xl">
        <!-- Abstract Background Shapes -->
        <div class="pointer-events-none absolute top-0 right-0 -mr-20 -mt-20 h-96 w-96 rounded-full bg-gradient-to-br from-white to-transparent opacity-20 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-0 left-0 -ml-20 -mb-20 h-80 w-80 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 opacity-30 blur-3xl"></div>
        
        <div class="relative z-10 px-8 py-12 sm:px-12 md:flex md:items-start md:justify-between md:gap-8">
            <div class="md:w-1/4">
                <div class="inline-flex items-center gap-2 rounded-full bg-indigo-800/50 px-4 py-1.5 text-sm font-medium text-indigo-200 ring-1 ring-inset ring-indigo-400/30 mb-6">
                    <svg class="h-4 w-4 animate-pulse" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                    Quick Action
                </div>
                <h2 class="text-3xl font-bold tracking-tight text-black mb-4">
                    Assign Role
                </h2>
                <p class="text-lg text-indigo-200 leading-relaxed mb-8 md:mb-0">
                                        Select a user and assign them a new role instantly. <br> This action updates their permissions immediately.
                </p>
            </div>

            <div class="md:w-3/4 bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/10 shadow-inner">
                <form action="{{ route('admin.roles.assign') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-indigo-100 mb-2">Select User</label>
                            <div class="relative">
                                <select id="user_id" name="user_id" class="block w-full rounded-xl border-0 bg-white/5 py-4 pl-4 pr-10 text-black shadow-sm ring-1 ring-inset ring-white/20 focus:ring-2 focus:ring-inset focus:ring-white sm:text-sm sm:leading-6 [&>option]:text-gray-900" required>
                                    <option value="" class="text-gray-400">Choose a user...</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="role_id" class="block text-sm font-medium text-indigo-100 mb-2">Assign Role</label>
                            <div class="relative">
                                <select id="role_id" name="role_id" class="block w-full rounded-xl border-0 bg-white/5 py-4 pl-4 pr-10 text-black shadow-sm ring-1 ring-inset ring-white/20 focus:ring-2 focus:ring-inset focus:ring-white sm:text-sm sm:leading-6 [&>option]:text-gray-900" required>
                                    <option value="" class="text-gray-400">Choose a role...</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->display_name ?? ucwords(str_replace('_', ' ', $role->name)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-emerald-600 px-6 py-4 text-sm font-bold text-indigo-900 shadow-xl transition-transform hover:-translate-y-0.5 hover:shadow-2xl focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                        Assign Access Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection