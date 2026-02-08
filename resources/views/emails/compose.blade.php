@extends('layouts.app')

@section('page-title', 'Compose Email')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Compose Email</h1>
        <a href="{{ route('emails.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Emails</a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('emails.send') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Template (Optional)</label>
                <select id="templateSelect" class="w-full border border-gray-300 rounded-md px-3 py-2" onchange="loadTemplate()">
                    <option value="">Select a template...</option>
                    @foreach($templates as $key => $template)
                    <option value="{{ $key }}">{{ $template['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Recipients</label>
                <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <input type="email" name="recipients[]" class="flex-1 border border-gray-300 rounded-md px-3 py-2" placeholder="Enter email address" required>
                        <button type="button" onclick="addRecipient()" class="bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700">+</button>
                    </div>
                    <div id="additionalRecipients"></div>
                </div>
                
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Or select from patients:</label>
                    <select class="w-full border border-gray-300 rounded-md px-3 py-2" onchange="addPatientEmail(this)">
                        <option value="">Select a patient...</option>
                        @foreach($patients as $patient)
                        @if($patient->email)
                        <option value="{{ $patient->email }}">{{ $patient->name }} ({{ $patient->email }})</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                <input type="text" name="subject" id="emailSubject" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                <textarea name="body" id="emailBody" rows="10" class="w-full border border-gray-300 rounded-md px-3 py-2" required></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Attachments (Optional)</label>
                <input type="file" name="attachments[]" multiple class="w-full border border-gray-300 rounded-md px-3 py-2">
                <p class="text-sm text-gray-500 mt-1">You can select multiple files</p>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('emails.index') }}" class="px-6 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Send Email
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let recipientCount = 1;

function addRecipient() {
    const container = document.getElementById('additionalRecipients');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2';
    div.innerHTML = `
        <input type="email" name="recipients[]" class="flex-1 border border-gray-300 rounded-md px-3 py-2" placeholder="Enter email address">
        <button type="button" onclick="removeRecipient(this)" class="bg-red-600 text-white px-3 py-2 rounded-md hover:bg-red-700">-</button>
    `;
    container.appendChild(div);
    recipientCount++;
}

function removeRecipient(button) {
    button.parentElement.remove();
    recipientCount--;
}

function addPatientEmail(select) {
    if (select.value) {
        const inputs = document.querySelectorAll('input[name="recipients[]"]');
        const emptyInput = Array.from(inputs).find(input => !input.value);
        
        if (emptyInput) {
            emptyInput.value = select.value;
        } else {
            addRecipient();
            const newInputs = document.querySelectorAll('input[name="recipients[]"]');
            newInputs[newInputs.length - 1].value = select.value;
        }
        
        select.value = '';
    }
}

function loadTemplate() {
    const templateSelect = document.getElementById('templateSelect');
    const templateKey = templateSelect.value;
    
    if (templateKey) {
        fetch(`/emails/template/${templateKey}`)
            .then(response => response.json())
            .then(template => {
                document.getElementById('emailSubject').value = template.subject;
                document.getElementById('emailBody').value = template.body;
            })
            .catch(error => console.error('Error loading template:', error));
    }
}
</script>
@endsection