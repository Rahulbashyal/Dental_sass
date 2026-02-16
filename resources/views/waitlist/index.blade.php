@extends('layouts.app')

@section('title', 'Waitlist Management')
@section('page-title', 'Waitlist Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Waitlist Management</h1>
            <p class="text-sm text-gray-500">Manage patients waiting for cancellations or preferred time slots.</p>
        </div>
        <button onclick="document.getElementById('addToWaitlistModal').classList.remove('hidden')" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Add to Waitlist
        </button>
    </div>

    <div class="card overflow-hidden border border-gray-100 shadow-sm rounded-2xl bg-white text-left">
        @if($waitlists->isEmpty())
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Waitlist is empty</h3>
                <p class="mt-2 text-gray-500 max-w-sm mx-auto">Click "Add to Waitlist" to track patients waiting for scheduled appointments.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Preferred Dentist</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Desired Date/Time</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($waitlists as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 bg-indigo-50 text-indigo-700 rounded-full flex items-center justify-center font-bold">
                                        {{ substr($item->patient->first_name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $item->patient->first_name }} {{ $item->patient->last_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->patient->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                Dr. {{ $item->dentist->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($item->preferred_date)->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $item->preferred_time ?? 'Any time' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-lg">
                                    {{ $item->appointment_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $colors = [
                                        'waiting' => 'bg-yellow-100 text-yellow-800',
                                        'contacted' => 'bg-blue-100 text-blue-800',
                                        'scheduled' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colors[$item->status] ?? 'bg-gray-100' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="relative inline-block text-left">
                                    <form action="{{ route('clinic.waitlist.update-status', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-sm border-gray-200 rounded-lg py-1 pl-2 pr-8 focus:ring-indigo-500 border-none bg-gray-50 hover:bg-gray-100 cursor-pointer transition-colors">
                                            <option value="waiting" {{ $item->status == 'waiting' ? 'selected' : '' }}>Waiting</option>
                                            <option value="contacted" {{ $item->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                            <option value="scheduled" {{ $item->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                            <option value="cancelled" {{ $item->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50/30 border-t border-gray-100">
                {{ $waitlists->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add to Waitlist Modal -->
<div id="addToWaitlistModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="document.getElementById('addToWaitlistModal').classList.add('hidden')">
            <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Add Patient to Waitlist</h3>
                
                <form action="{{ route('clinic.waitlist.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="form-label font-semibold">Select Patient</label>
                        <select name="patient_id" class="form-input rounded-xl h-12 border-gray-200" required>
                            <option value="">Choose Patient...</option>
                            @php
                                $patients = \App\Models\Patient::where('clinic_id', auth()->user()->clinic_id)->get();
                            @endphp
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="form-label font-semibold">Preferred Dentist</label>
                        <select name="dentist_id" class="form-input rounded-xl h-12 border-gray-200" required>
                            @php
                                $dentists = \App\Models\User::role('dentist')->where('clinic_id', auth()->user()->clinic_id)->get();
                            @endphp
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}">Dr. {{ $dentist->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="form-label font-semibold">Pref. Date</label>
                            <input type="date" name="preferred_date" class="form-input rounded-xl h-12 border-gray-200" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="space-y-2">
                            <label class="form-label font-semibold">Pref. Time</label>
                            <input type="time" name="preferred_time" class="form-input rounded-xl h-12 border-gray-200">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="form-label font-semibold">Appointment Type</label>
                        <input type="text" name="appointment_type" placeholder="e.g. Scaling" class="form-input rounded-xl h-12 border-gray-200" required>
                    </div>

                    <div class="pt-4 flex space-x-4">
                        <button type="button" onclick="document.getElementById('addToWaitlistModal').classList.add('hidden')" class="flex-1 px-6 py-3 rounded-xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                            Add to List
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection