@extends('layouts.app')

@section('page-title', 'Medication Database')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Medication Database</h1>
            <p class="text-gray-600">Manage dental medications for prescriptions</p>
        </div>
        <button onclick="openAddMedicationModal()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            + Add Medication
        </button>
    </div>

    <!-- Medications Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if($medications->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medication Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Generic Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Common Dosages</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($medications as $medication)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-medium text-gray-900">{{ $medication->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $medication->generic_name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                    {{ $medication->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($medication->common_dosages && is_array($medication->common_dosages))
                                    {{ implode(', ', $medication->common_dosages) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($medication->is_active)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick="viewMedication({{ $medication->id }})" class="text-blue-600 hover:text-blue-800 mr-3">View</button>
                                <form action="{{ route('medications.toggle', $medication) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-gray-600 hover:text-gray-800">
                                        {{ $medication->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="px-6 py-4 bg-gray-50">
                {{ $medications->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-gray-500">No medications found.</p>
            </div>
        @endif
    </div>
</div>

<!-- Add Medication Modal -->
<div id="addMedicationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Add New Medication</h3>
            <button onclick="closeAddMedicationModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('medications.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Medication Name *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Generic Name</label>
                    <input type="text" name="generic_name" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Select Category</option>
                        <option value="Antibiotic">Antibiotic</option>
                        <option value="Painkiller (NSAID)">Painkiller (NSAID)</option>
                        <option value="Painkiller (Analgesic)">Painkiller (Analgesic)</option>
                        <option value="Painkiller (Opioid)">Painkiller (Opioid)</option>
                        <option value="Anti-inflammatory (Enzyme)">Anti-inflammatory (Enzyme)</option>
                        <option value="Antiseptic Mouthwash">Antiseptic Mouthwash</option>
                        <option value="Antiseptic Gargle">Antiseptic Gargle</option>
                        <option value="Analgesic Mouthwash">Analgesic Mouthwash</option>
                        <option value="Antifungal">Antifungal</option>
                        <option value="Antifungal (Topical)">Antifungal (Topical)</option>
                        <option value="Antihistamine">Antihistamine</option>
                        <option value="Vitamin">Vitamin</option>
                        <option value="Supplement">Supplement</option>
                        <option value="Corticosteroid">Corticosteroid</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Manufacturer</label>
                    <input type="text" name="manufacturer" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Common Dosages (comma-separated)</label>
                    <input type="text" name="common_dosages_input" placeholder="e.g., 250mg, 500mg, 1g" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <p class="text-xs text-gray-500 mt-1">Separate multiple dosages with commas</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Side Effects</label>
                    <textarea name="side_effects" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contraindications</label>
                    <textarea name="contraindications" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeAddMedicationModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Add Medication
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddMedicationModal() {
    document.getElementById('addMedicationModal').classList.remove('hidden');
}

function closeAddMedicationModal() {
    document.getElementById('addMedicationModal').classList.add('hidden');
}

function viewMedication(id) {
    // TODO: Implement view medication details modal
    alert('Medication details view - ID: ' + id);
}
</script>
@endsection
