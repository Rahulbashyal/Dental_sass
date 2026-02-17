@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Team Members</h1>
        <a href="{{ route('admin.cms.team.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Add Team Member
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($teamMembers as $member)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-start justify-between mb-4">
                    @if($member->photo)
                        <img src="{{ Storage::url($member->photo) }}" alt="{{ $member->name }}" class="h-20 w-20 rounded-full object-cover">
                    @else
                        <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-2xl text-gray-500">{{ substr($member->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        @if($member->is_featured)
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">Featured</span>
                        @endif
                        <form action="{{ route('admin.cms.team.toggle', $member) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-2 py-1 text-xs rounded ml-1 {{ $member->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $member->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-gray-900">{{ $member->name }}</h3>
                <p class="text-sm text-gray-600">{{ $member->title }}</p>
                @if($member->specialization)
                    <p class="text-sm text-blue-600 mt-1">{{ $member->specialization }}</p>
                @endif

                @if($member->experience_years)
                    <p class="text-xs text-gray-500 mt-2">{{ $member->experience_years }} years experience</p>
                @endif

                @if($member->bio)
                    <p class="text-sm text-gray-600 mt-3 line-clamp-2">{{ $member->bio }}</p>
                @endif

                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between">
                    <span class="text-xs text-gray-500">Order: {{ $member->display_order }}</span>
                    <div class="space-x-2">
                        <a href="{{ route('admin.cms.team.edit', $member) }}" class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>
                        <form action="{{ route('admin.cms.team.destroy', $member) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500">No team members found.</p>
                <a href="{{ route('admin.cms.team.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">Add your first team member</a>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $teamMembers->links() }}
    </div>
</div>
@endsection
