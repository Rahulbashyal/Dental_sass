@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manage Services</h1>
        <a href="{{ route('admin.cms.services.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Add New Service
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($services as $service)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($service->featured_image)
                                    <img src="{{ Storage::url($service->featured_image) }}" alt="{{ $service->name }}" class="h-10 w-10 rounded object-cover mr-3">
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($service->short_description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $service->category ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $service->duration_minutes ? $service->duration_minutes . ' min' : '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($service->show_pricing && $service->price)
                                रू {{ number_format($service->price, 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.cms.services.toggle', $service) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-2 py-1 text-xs rounded {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $service->display_order }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('admin.cms.services.edit', $service) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form action="{{ route('admin.cms.services.destroy', $service) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No services found. <a href="{{ route('admin.cms.services.create') }}" class="text-blue-600 hover:underline">Add your first service</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $services->links() }}
    </div>
</div>
@endsection
