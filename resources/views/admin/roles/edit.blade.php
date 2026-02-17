@extends('layouts.app')

@section('page-title', 'Edit Role')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Page Header -->
    <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">
                Edit Role: {{ $role->display_name ?? ucfirst($role->name) }}
            </h1>
            <p class="mt-2 text-gray-500">
                Update access levels and permissions.
            </p>
        </div>

        <a href="{{ route('admin.roles.index') }}"
           class="inline-flex items-center justify-center gap-2 w-40 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            <svg class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                      d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z"
                      clip-rule="evenodd" />
            </svg>
            Back to Roles
        </a>
    </div>

    <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Role Details -->
        <div class="rounded-xl border border-gray-200 bg-white p-8">

            <h3 class="text-lg font-semibold text-gray-800 mb-6">
                Role Information
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <!-- Identifier -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Role Identifier
                        <span class="text-gray-400">(System Locked)</span>
                    </label>

                    <input type="text"
                           value="{{ $role->name }}"
                           readonly
                           class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-gray-500 cursor-not-allowed text-sm">
                </div>

                <!-- Display Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Display Name <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           name="display_name"
                           value="{{ $role->display_name }}"
                           required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                                  focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>

                    <textarea name="description" rows="3"
                              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                                     focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">{{ $role->description }}</textarea>
                </div>

            </div>
        </div>

        <!-- Permissions -->
        <div class="rounded-xl border border-gray-200 bg-white">

            <div class="px-8 py-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    Access Control
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    Changes apply immediately to all users.
                </p>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($permissions as $category => $categoryPermissions)
                <div class="px-8 py-8">

                    <div class="flex items-center mb-6">
                        <h4 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">
                            {{ str_replace('_', ' ', $category) }} Module
                        </h4>

                        <span class="ml-auto text-xs text-gray-500">
                            {{ count($categoryPermissions) }} permissions
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($categoryPermissions as $permission)
                        <label class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-50 cursor-pointer transition">

                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $permission->id }}"
                                   class="h-4 w-4 rounded border-gray-300 text-amber-600 focus:ring-amber-600"
                                   {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>

                            <span class="text-sm text-gray-700">
                                {{ str_replace('_', ' ', ucfirst($permission->name)) }}
                            </span>
                        </label>
                        @endforeach
                    </div>

                </div>
                @endforeach
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">

            <a href="{{ route('admin.roles.index') }}"
               class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>

            <button type="submit"
                    class="rounded-lg bg-amber-600 px-8 py-2.5 text-sm font-medium text-black hover:bg-amber-700 transition">
                Update Role
            </button>

        </div>

    </form>
</div>
@endsection
