@extends('layouts.app')

@section('page-title', 'Staff Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Staff Management</h1>
        <a href="{{ route('staff.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Add Staff Member
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($staff as $member)
                <li class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-white font-medium">{{ substr($member->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                <div class="text-sm text-gray-500">{{ $member->email }}</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $member->roles->first()?->name === 'clinic_admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $member->roles->first()?->name === 'dentist' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $member->roles->first()?->name === 'receptionist' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $member->roles->first()?->name === 'accountant' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $member->roles->first()?->name ?? 'No Role')) }}
                            </span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $member->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $member->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <div class="flex space-x-2">
                                <a href="{{ route('staff.edit', $member) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                <form action="{{ route('staff.destroy', $member) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-6 py-4 text-center text-gray-500">
                    No staff members found. <a href="{{ route('staff.create') }}" class="text-blue-600">Add the first one</a>
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection