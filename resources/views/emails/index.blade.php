@extends('layouts.app')

@section('page-title', 'Email Communications')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Email Communications</h1>
        <div class="space-x-2">
            <a href="{{ route('emails.compose') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Compose Email
            </a>
            <button onclick="openBulkEmailModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Bulk Email
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipients</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sender</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($emails as $email)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $email->subject }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ count($email->recipients) }} recipient(s)</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $email->sender->name ?? 'System' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $email->status === 'sent' ? 'bg-green-100 text-green-800' : 
                               ($email->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($email->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $email->sent_at ? $email->sent_at->format('M d, Y H:i') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('emails.show', $email) }}" class="text-blue-600 hover:text-blue-900">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No emails found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $emails->links() }}
    </div>
</div>

<!-- Bulk Email Modal -->
<div id="bulkEmailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
            <h3 class="text-lg font-medium mb-4">Send Bulk Email</h3>
            <form action="{{ route('emails.bulk') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recipients</label>
                    <select name="recipient_type" class="w-full border border-gray-300 rounded-md px-3 py-2" onchange="togglePatientSelection(this.value)">
                        <option value="all_patients">All Patients</option>
                        <option value="selected_patients">Selected Patients</option>
                    </select>
                </div>
                
                <div id="patientSelection" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Patients</label>
                    <div class="max-h-40 overflow-y-auto border border-gray-300 rounded-md p-2">
                        @foreach(\App\Models\Patient::where('clinic_id', Auth::user()->clinic_id)->get() as $patient)
                        <label class="flex items-center mb-2">
                            <input type="checkbox" name="selected_patients[]" value="{{ $patient->id }}" class="mr-2">
                            {{ $patient->name }} ({{ $patient->email }})
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" name="subject" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="body" rows="6" class="w-full border border-gray-300 rounded-md px-3 py-2" required></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeBulkEmailModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Send Bulk Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openBulkEmailModal() {
    document.getElementById('bulkEmailModal').classList.remove('hidden');
}

function closeBulkEmailModal() {
    document.getElementById('bulkEmailModal').classList.add('hidden');
}

function togglePatientSelection(type) {
    const selection = document.getElementById('patientSelection');
    if (type === 'selected_patients') {
        selection.classList.remove('hidden');
    } else {
        selection.classList.add('hidden');
    }
}
</script>
@endsection