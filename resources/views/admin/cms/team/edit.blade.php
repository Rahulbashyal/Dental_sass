@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('admin.cms.team.index') }}" class="text-blue-600 hover:underline">← Back to Team</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Edit Team Member</h1>
    </div>

    <form action="{{ route('admin.cms.team.update', $team) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Link to Staff User (Optional)</label>
                <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">- None -</option>
                    @foreach($staffUsers as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $team->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->getRoleNames()->first() }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('name', $team->name) }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('title', $team->title) }}">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                <input type="text" name="specialization" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('specialization', $team->specialization) }}">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                <textarea name="bio" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('bio', $team->bio) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Photo</label>
                @if($team->photo)
                    <div class="mb-2">
                        <img src="{{ Storage::url($team->photo) }}" alt="{{ $team->name }}" class="h-32 w-32 rounded-full object-cover">
                        <p class="text-sm text-gray-500 mt-1">Current photo</p>
                    </div>
                @endif
                <input type="file" name="photo" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <p class="text-xs text-gray-500 mt-1">Leave empty to keep current photo</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Education/Credentials</label>
                <textarea name="education" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('education', $team->education) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Experience (years)</label>
                <input type="number" name="experience_years" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('experience_years', $team->experience_years) }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Languages</label>
                <input type="text" name="languages" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('languages', $team->languages) }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email (Public)</label>
                <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('email', $team->email) }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone (Public)</label>
                <input type="text" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('phone', $team->phone) }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                <input type="number" name="display_order" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('display_order', $team->display_order) }}">
            </div>

            <div class="flex items-center space-x-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_featured" id="is_featured" class="h-4 w-4 text-blue-600" {{ old('is_featured', $team->is_featured) ? 'checked' : '' }}>
                    <label for="is_featured" class="ml-2 text-sm text-gray-700">Featured</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" class="h-4 w-4 text-blue-600" {{ old('is_active', $team->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.cms.team.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Team Member</button>
        </div>
    </form>
</div>
@endsection
