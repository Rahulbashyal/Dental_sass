@extends('layouts.app')

@section('page-title', 'Create Role')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Create New Role
            </h2>
            <p class="mt-1 text-sm text-gray-500">Define a new system role and its permissions.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <a href="{{ route('admin.roles.index') }}" class="whitespace-nowrap inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" />
                </svg>
                Back to Roles
            </a>
        </div>
    </div>

    <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Role Details Card -->
        <div class="bg-white shadow-sm ring-1 ring-indigo-100 rounded-2xl md:col-span-2">
            <div class="px-4 py-5 sm:px-6 border-b border-indigo-50 bg-indigo-50/30 rounded-t-2xl">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Role Details</h3>
                <p class="mt-1 text-sm text-gray-500">Basic information about the role.</p>
            </div>
            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="name" class="block text-sm font-semibold leading-6 text-gray-900">
                            Role Name (System) <span class="text-red-600">*</span>
                        </label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" required
                                class="block w-full rounded-xl border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                placeholder="e.g. clinic_manager">
                            <p class="mt-2 text-xs text-gray-500">
                                Unique identifier. Use lowercase letters and underscores only.
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="display_name" class="block text-sm font-semibold leading-6 text-gray-900">
                            Display Name <span class="text-red-600">*</span>
                        </label>
                        <div class="mt-2">
                            <input type="text" name="display_name" id="display_name" required
                                class="block w-full rounded-xl border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                placeholder="e.g. Clinic Manager">
                            <p class="mt-2 text-xs text-gray-500">
                                Human-readable name shown in the interface.
                            </p>
                        </div>
                    </div>

                    <div class="col-span-full">
                        <label for="description" class="block text-sm font-semibold leading-6 text-gray-900">
                            Description
                        </label>
                        <div class="mt-2">
                            <textarea id="description" name="description" rows="3"
                                class="block w-full rounded-xl border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                placeholder="Briefly describe what this role is for..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Card -->
        <div class="bg-white shadow-sm ring-1 ring-indigo-100 rounded-2xl md:col-span-2">
            <div class="px-4 py-5 sm:px-6 border-b border-indigo-50 bg-indigo-50/30 rounded-t-2xl flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Permissions</h3>
                    <p class="mt-1 text-sm text-gray-500">Select the access levels for this role.</p>
                </div>
                <div class="text-sm text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full font-medium">
                    Select Carefully
                </div>
            </div>
            
            <div class="divide-y divide-gray-100">
                @foreach($permissions as $category => $categoryPermissions)
                <div class="px-4 py-6 sm:px-8 hover:bg-gray-50/50 transition-colors">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-indigo-200 bg-indigo-50 text-[0.625rem] font-medium text-indigo-700 uppercase">
                            {{ substr($category, 0, 1) }}
                        </span>
                        <h4 class="text-sm font-bold leading-6 text-gray-900 uppercase tracking-wide">{{ str_replace('_', ' ', $category) }} Module</h4>
                        <span class="ml-2 inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                            {{ count($categoryPermissions) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($categoryPermissions as $permission)
                        <div class="relative flex items-start">
                            <div class="flex h-6 items-center">
                                <input id="perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}" type="checkbox" 
                                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="perm_{{ $permission->id }}" class="font-medium text-gray-700 cursor-pointer select-none hover:text-gray-900">
                                    {{ str_replace('_', ' ', ucfirst($permission->name)) }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-4 border-t border-gray-900/10 pt-4">
            <a href="{{ route('admin.roles.index') }}" class="text-sm font-semibold leading-6 text-gray-900 px-3 py-2 rounded-md hover:bg-gray-100 transition-colors">Cancel</a>
            <button type="submit" class="rounded-md bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Create Role
            </button>
        </div>
    </form>
</div>
@endsection