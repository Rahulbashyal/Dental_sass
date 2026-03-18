@extends(request()->has('iframe') ? 'layouts.iframe' : 'layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('admin.cms.services.index') }}" class="text-blue-600 hover:underline">← Back to Services</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Edit Service</h1>
    </div>

    <form action="{{ route('admin.cms.services.update', $service) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
    @if(request()->has('iframe'))
        <input type="hidden" name="iframe" value="1">
    @endif

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Service Name *</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('name', $service->name) }}">
                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <input type="text" name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('category', $service->category) }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                <input type="number" name="duration_minutes" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('duration_minutes', $service->duration_minutes) }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Price (रू)</label>
                <input type="number" step="0.01" name="price" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('price', $service->price) }}">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="show_pricing" id="show_pricing" class="h-4 w-4 text-blue-600" {{ old('show_pricing', $service->show_pricing) ? 'checked' : '' }}>
                <label for="show_pricing" class="ml-2 text-sm text-gray-700">Show pricing on website</label>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                <input type="text" name="short_description" maxlength="500" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('short_description', $service->short_description) }}">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Description *</label>
                <textarea name="description" required rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('description', $service->description) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                @if($service->featured_image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($service->featured_image) }}" alt="{{ $service->name }}" class="h-32 w-auto rounded">
                        <p class="text-sm text-gray-500 mt-1">Current image</p>
                    </div>
                @endif
                <input type="file" name="featured_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                <input type="number" name="display_order" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('display_order', $service->display_order) }}">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" class="h-4 w-4 text-blue-600" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">SEO Title</label>
                <input type="text" name="seo_title" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="{{ old('seo_title', $service->seo_title) }}">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">SEO Description</label>
                <textarea name="seo_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('seo_description', $service->seo_description) }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.cms.services.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Service</button>
        </div>
    </form>
</div>
@endsection


{{-- Auto-close modal script on success --}}
@if(session('success') && request()->has('iframe'))
    <script>
        setTimeout(() => {
            window.parent.location.reload();
        }, 1500);
    </script>
@endif
