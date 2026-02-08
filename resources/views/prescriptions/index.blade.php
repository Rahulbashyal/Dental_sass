@extends('layouts.app')

@section('page-title', 'Prescriptions')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Prescriptions</h1>
            <p class="text-gray-600">Manage patient prescriptions</p>
        </div>
        <a href="{{ route('prescriptions.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            + New Prescription
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-md rounded-lg p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Prescription #</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="PRX-20251205-0001"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                <select name="patient_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">All Patients</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 mr-2">
                    Filter
                </button>
                <a href="{{ route('prescriptions.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Prescriptions Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if($prescriptions->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prescription #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dentist</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medications</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($prescriptions as $prescription)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm text-blue-600">{{ $prescription->prescription_number }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $prescription->patient->first_name }} {{ $prescription->patient->last_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                Dr. {{ $prescription->dentist->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $prescription->prescribed_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $prescription->items->count() }} item(s)
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($prescription->status === 'active')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                @elseif($prescription->status === 'completed')
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Completed</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Cancelled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex space-x-2">
                                    <a href="{{ route('prescriptions.show', $prescription) }}" 
                                       class="text-blue-600 hover:text-blue-800">View</a>
                                    <a href="{{ route('prescriptions.pdf', $prescription) }}" 
                                       class="text-green-600 hover:text-green-800">PDF</a>
                                    <a href="{{ route('prescriptions.print', $prescription) }}" 
                                       target="_blank"
                                       class="text-purple-600 hover:text-purple-800">Print</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="px-6 py-4 bg-gray-50">
                {{ $prescriptions->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-gray-500">No prescriptions found.</p>
                <a href="{{ route('prescriptions.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                    Create your first prescription
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
