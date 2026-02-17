@extends('layouts.app')

@section('page-title', 'Treatment Plans')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Treatment Plans</h1>
        <a href="{{ route('treatment-plans.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Create Treatment Plan
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($treatmentPlans as $plan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $plan->patient->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $plan->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">NPR {{ number_format($plan->estimated_cost) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $plan->priority === 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $plan->priority === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $plan->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $plan->priority === 'low' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ ucfirst($plan->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $plan->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $plan->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $plan->status === 'approved' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $plan->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $plan->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('treatment-plans.show', $plan) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                <a href="{{ route('treatment-plans.edit', $plan) }}" class="text-green-600 hover:text-green-900">Edit</a>
                                <form action="{{ route('treatment-plans.destroy', $plan) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No treatment plans found. <a href="{{ route('treatment-plans.create') }}" class="text-blue-600">Create the first one</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $treatmentPlans->links() }}
    </div>
</div>
@endsection