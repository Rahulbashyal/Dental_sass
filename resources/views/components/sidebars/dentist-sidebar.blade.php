<div class="bg-purple-800 text-white w-64 min-h-screen p-4">
    <div class="mb-8">
        <h2 class="text-xl font-bold">Dentist Panel</h2>
        <p class="text-purple-200 text-sm">Clinical Management</p>
    </div>
    
    <nav class="space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded hover:bg-purple-700 {{ request()->routeIs('dashboard') ? 'bg-purple-700' : '' }}">
            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
        </a>
        
        <div class="space-y-1">
            <p class="text-purple-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Patient Care</p>
            <a href="{{ route('clinic.appointments.index') }}" class="flex items-center p-3 rounded hover:bg-purple-700 {{ request()->routeIs('clinic.appointments.*') ? 'bg-purple-700' : '' }}">
                <i class="fas fa-calendar-check mr-3"></i> My Appointments
            </a>
            <a href="{{ route('patients.index') }}" class="flex items-center p-3 rounded hover:bg-purple-700 {{ request()->routeIs('patients.*') ? 'bg-purple-700' : '' }}">
                <i class="fas fa-users mr-3"></i> My Patients
            </a>
            <a href="{{ route('treatment-plans.index') }}" class="flex items-center p-3 rounded hover:bg-purple-700 {{ request()->routeIs('treatment-plans.*') ? 'bg-purple-700' : '' }}">
                <i class="fas fa-tooth mr-3"></i> Treatment Plans
            </a>
        </div>
        
        <div class="space-y-1">
            <p class="text-purple-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Records</p>
            <a href="{{ route('invoices.index') }}" class="flex items-center p-3 rounded hover:bg-purple-700 {{ request()->routeIs('invoices.*') ? 'bg-purple-700' : '' }}">
                <i class="fas fa-file-invoice mr-3"></i> Patient Invoices
            </a>
            <a href="{{ route('reports.index') }}" class="flex items-center p-3 rounded hover:bg-purple-700 {{ request()->routeIs('reports.*') ? 'bg-purple-700' : '' }}">
                <i class="fas fa-chart-line mr-3"></i> Reports
            </a>
        </div>
    </nav>
</div>