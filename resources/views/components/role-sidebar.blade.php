@props(['role'])

@if($role === 'receptionist')
    <!-- Receptionist Sidebar -->
    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>Reception Desk</span>
    </h6>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('clinic.appointments.*') ? 'active' : '' }}" href="{{ route('clinic.appointments.index') }}">
                <i class="fas fa-calendar-check"></i> Appointments
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('clinic.appointments.create') }}">
                <i class="fas fa-plus-circle"></i> Book Appointment
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                <i class="fas fa-users"></i> Patients
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('patients.create') }}">
                <i class="fas fa-user-plus"></i> Add Patient
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('waitlist.*') ? 'active' : '' }}" href="{{ route('waitlist.index') }}">
                <i class="fas fa-clock"></i> Waiting List
            </a>
        </li>
    </ul>
    
    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>Communication</span>
    </h6>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('clinic.notifications.*') ? 'active' : '' }}" href="{{ route('clinic.notifications.index') }}">
                <i class="fas fa-bell"></i> Send Reminders
            </a>
        </li>
    </ul>

@elseif($role === 'accountant')
    <!-- Accountant Sidebar -->
    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>Financial Management</span>
    </h6>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                <i class="fas fa-file-invoice"></i> Invoices
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('invoices.create') }}">
                <i class="fas fa-plus-circle"></i> Create Invoice
            </a>
        </li>
    </ul>
    
    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>Payments</span>
    </h6>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('invoices.index') }}?status=pending">
                <i class="fas fa-exclamation-triangle"></i> Pending Payments
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('invoices.index') }}?status=overdue">
                <i class="fas fa-clock"></i> Overdue Payments
            </a>
        </li>
    </ul>
    
    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>Reports</span>
    </h6>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                <i class="fas fa-chart-bar"></i> Financial Reports
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}" href="{{ route('analytics.dashboard') }}">
                <i class="fas fa-analytics"></i> Revenue Analytics
            </a>
        </li>
    </ul>

@else
    <!-- Default Clinic Sidebar -->
    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>Clinic Management</span>
    </h6>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('clinic.appointments.*') ? 'active' : '' }}" href="{{ route('clinic.appointments.index') }}">
                <i class="fas fa-calendar-check"></i> Appointments
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                <i class="fas fa-users"></i> Patients
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                <i class="fas fa-file-invoice"></i> Invoices
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('staff.*') ? 'active' : '' }}" href="{{ route('staff.index') }}">
                <i class="fas fa-user-md"></i> Staff
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                <i class="fas fa-chart-bar"></i> Reports
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                <i class="fas fa-cog"></i> Settings
            </a>
        </li>
    </ul>
@endif