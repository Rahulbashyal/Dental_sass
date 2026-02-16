<div class="bg-blue-800 text-white w-64 min-h-screen p-4">
    <div class="mb-8">
        <h2 class="text-xl font-bold">Dentist Panel</h2>
        <p class="text-blue-200 text-sm">Clinical Management</p>
    </div>
    
    <nav class="space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
        </a>
        
        <div class="space-y-1">
            <p class="text-blue-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Patient Care</p>
            <a href="{{ route('clinic.appointments.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.appointments.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-calendar-check mr-3"></i> My Appointments
            </a>
            <a href="{{ route('clinic.patients.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.patients.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-users mr-3"></i> My Patients
            </a>
            <a href="{{ route('clinic.prescriptions.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.prescriptions.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-prescription-bottle-alt mr-3"></i> Prescriptions
            </a>
        </div>

        <div class="space-y-1">
            <p class="text-blue-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Diagnostics</p>
            <a href="{{ route('clinic.radiology.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.radiology.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-x-ray mr-3"></i> Radiology & Imaging
            </a>
            <a href="{{ route('clinic.lab-orders.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.lab-orders.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-flask mr-3"></i> Lab Orders
            </a>
        </div>
        
        <div class="space-y-1">
            <p class="text-blue-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Records</p>
            <a href="{{ route('clinic.invoices.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.invoices.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-file-invoice mr-3"></i> Patient Invoices
            </a>
            <a href="{{ route('clinic.reports.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.reports.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-chart-line mr-3"></i> Reports
            </a>
        </div>
    </nav>
</div>