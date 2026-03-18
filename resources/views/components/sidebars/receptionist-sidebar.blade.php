<div class="bg-blue-800 text-white w-64 min-h-screen p-4">
    <div class="mb-8">
        <h2 class="text-xl font-bold">Reception Panel</h2>
        <p class="text-blue-200 text-sm">Patient Management</p>
    </div>
    
    <nav class="space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
        </a>
        
        <div class="space-y-1">
            <p class="text-blue-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Patient Management</p>
            <a href="{{ route('clinic.patients.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700">
                <i class="fas fa-users mr-3"></i> Patients
            </a>
            <a href="{{ route('clinic.patients.create', ['iframe' => 1]) }}" data-modal-url="{{ route('dashboard') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
        </a>
        
        <div class="space-y-1">
            <p class="text-blue-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Patient Management</p>
            <a href="{{ route('clinic.patients.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700">
                <i class="fas fa-users mr-3"></i> Patients
            </a>
            <a href="{{ route('clinic.patients.create', ['iframe' => 1]) }}" data-modal-title="Add Patient" class="flex items-center p-3 rounded hover:bg-blue-700">
                <i class="fas fa-user-plus mr-3"></i> Add Patient
            </a>
        </div>
        
        <div class="space-y-1">
            <p class="text-blue-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Appointments</p>
            <a href="{{ route('clinic.appointments.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.appointments.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-calendar mr-3"></i> Appointments
            </a>
            <a href="{{ route('clinic.appointments.create', ['iframe' => 1]) }}" data-modal-url="{{ route('clinic.appointments.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.appointments.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-calendar mr-3"></i> Appointments
            </a>
            <a href="{{ route('clinic.appointments.create', ['iframe' => 1]) }}" data-modal-title="routeIs('clinic.appointments.create') ? 'bg-blue-700' : '' }}">
                 Schedule" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.appointments.create') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-calendar-plus mr-3"></i> Schedule
            </a>
            <a href="{{ route('clinic.waitlist.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.waitlist.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-clock mr-3"></i> Waiting List
            </a>
            <a href="{{ route('clinic.print-schedule') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->route('clinic.print-schedule') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-print mr-3"></i> Print Schedule
            </a>
        </div>
    </nav>
</div>