<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Reception Tools</h3>
        
        <!-- Quick Patient Search -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Quick Patient Search</label>
            <div class="relative">
                <input type="text" id="patient-search" placeholder="Search by name, phone, or patient ID..." 
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <div id="search-results" class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-200 hidden"></div>
            </div>
        </div>

        <!-- Emergency Appointment -->
        <div class="mb-6">
            <button onclick="openEmergencyModal()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                Emergency Appointment
            </button>
        </div>

        <!-- Today's Actions -->
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('clinic.today-schedule') }}" class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Today's Schedule
            </a>
            
            <a href="{{ route('clinic.print-schedule') }}" target="_blank" class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Schedule
            </a>
        </div>
    </div>
</div>

<!-- Emergency Appointment Modal -->
<div id="emergency-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full">
            <form action="{{ route('clinic.emergency-appointment') }}" method="POST" class="p-6">
                @csrf
                <h3 class="text-lg font-medium text-gray-900 mb-4">Emergency Appointment</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Patient</label>
                    <select name="patient_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Patient</option>
                        @foreach(App\Models\Patient::where('clinic_id', auth()->user()->clinic_id ?? 0)->get() as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dentist</label>
                    <select name="dentist_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Dentist</option>
                        @foreach(App\Models\User::where('clinic_id', auth()->user()->clinic_id ?? 0)->get() as $dentist)
                        <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Notes</label>
                    <textarea name="notes" required rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEmergencyModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                        Create Emergency Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEmergencyModal() {
    document.getElementById('emergency-modal').classList.remove('hidden');
}

function closeEmergencyModal() {
    document.getElementById('emergency-modal').classList.add('hidden');
}

// Patient search functionality
document.getElementById('patient-search').addEventListener('input', function(e) {
    const query = e.target.value;
    if (query.length > 2) {
        fetch(`{{ route('clinic.patient-search') }}?q=${query}`)
            .then(response => response.json())
            .then(data => {
                const results = document.getElementById('search-results');
                results.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(patient => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer';
                        div.innerHTML = `<strong>${patient.first_name} ${patient.last_name}</strong><br><small>${patient.phone} - ${patient.patient_id}</small>`;
                        div.onclick = () => {
                            window.location.href = `/clinic/patients/${patient.id}`;
                        };
                        results.appendChild(div);
                    });
                    results.classList.remove('hidden');
                } else {
                    results.classList.add('hidden');
                }
            });
    } else {
        document.getElementById('search-results').classList.add('hidden');
    }
});
</script>