@if(auth()->check())
    @php
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('superadmin');
        $isClinicAdmin = $user->hasRole('clinic_admin');
        $isDentist = $user->hasRole('dentist');
        $isReceptionist = $user->hasRole('receptionist');
        $isAccountant = $user->hasRole('accountant');
        $clinic = $user->clinic;
    @endphp

    <!-- Dashboard -->
    <li>
        <a href="{{ route('dashboard') }}" class="group nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home text-blue-400"></i>
            <span>Dashboard</span>
        </a>
    </li>

    @if($isSuperAdmin)
        <li class="pt-1">
            <div class="nav-section-title">Infrastucture</div>
            <ul role="list" class="space-y-1">
                <li><a href="{{ route('clinics.index') }}" class="group nav-link {{ request()->routeIs('clinics.*') ? 'active' : '' }}"><i class="fas fa-clinic-medical text-sky-400"></i><span>Clinics</span></a></li>
                <li><a href="{{ route('superadmin.tenants.index') }}" class="group nav-link {{ request()->routeIs('superadmin.tenants*') ? 'active' : '' }}"><i class="fas fa-server text-violet-400"></i><span>Accounts</span></a></li>
                <li><a href="{{ route('superadmin.users') }}" class="group nav-link {{ request()->routeIs('superadmin.users*') ? 'active' : '' }}"><i class="fas fa-users-cog text-blue-500"></i><span>Admins</span></a></li>
                <li><a href="{{ route('admin.roles.index') }}" class="group nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"><i class="fas fa-user-shield text-indigo-400"></i><span>Access</span></a></li>
                <li><a href="{{ route('superadmin.modules.index') }}" class="group nav-link {{ request()->routeIs('superadmin.modules*') ? 'active' : '' }}"><i class="fas fa-cubes text-cyan-400"></i><span>Add-ons</span></a></li>
            </ul>
        </li>

        <li class="pt-1">
            <div class="nav-section-title">Growth & CRM</div>
            <ul role="list" class="space-y-1">
                <li><a href="{{ route('superadmin.analytics') }}" class="group nav-link {{ request()->routeIs('superadmin.analytics') ? 'active' : '' }}"><i class="fas fa-chart-line text-emerald-400"></i><span>Insights</span></a></li>
                <li><a href="{{ route('superadmin.crm.leads') }}" class="group nav-link {{ request()->routeIs('superadmin.crm.leads*') ? 'active' : '' }}"><i class="fas fa-user-tag text-orange-400"></i><span>Leads</span></a></li>
                <li><a href="{{ route('superadmin.crm.campaigns') }}" class="group nav-link {{ request()->routeIs('superadmin.crm.campaigns*') ? 'active' : '' }}"><i class="fas fa-bullhorn text-pink-400"></i><span>Ads</span></a></li>
                @if(Route::has('superadmin.logs.traffic'))
                <li><a href="{{ route('superadmin.logs.traffic') }}" class="group nav-link {{ request()->routeIs('superadmin.logs.traffic*') ? 'active' : '' }}"><i class="fas fa-chart-bar text-sky-300"></i><span>Traffic Logs</span></a></li>
                @endif
            </ul>
        </li>

        <li class="pt-1">
            <div class="nav-section-title">Web & Content</div>
            <ul role="list" class="space-y-1">
                <li><a href="{{ route('superadmin.content.landing') }}" class="group nav-link {{ request()->routeIs('superadmin.content.landing*') ? 'active' : '' }}"><i class="fas fa-pager text-violet-400"></i><span>Editor</span></a></li>
                <li><a href="{{ route('superadmin.content.blog') }}" class="group nav-link {{ request()->routeIs('superadmin.content.blog*') ? 'active' : '' }}"><i class="fas fa-blog text-teal-400"></i><span>Blog</span></a></li>
                <li><a href="{{ route('superadmin.content.testimonials') }}" class="group nav-link {{ request()->routeIs('superadmin.content.testimonials*') ? 'active' : '' }}"><i class="fas fa-quote-left text-yellow-400"></i><span>Reviews</span></a></li>
                <li><a href="{{ route('superadmin.templates.index') }}" class="group nav-link {{ request()->routeIs('superadmin.templates.*') ? 'active' : '' }}"><i class="fas fa-layer-group text-blue-400"></i><span>Themes (Lego)</span></a></li>
            </ul>
        </li>

        <li class="pt-1">
            <div class="nav-section-title">System & Security</div>
            <ul role="list" class="space-y-1">
                <li><a href="{{ route('superadmin.debug') }}" class="group nav-link {{ request()->routeIs('superadmin.debug*') ? 'active' : '' }}"><i class="fas fa-microchip text-rose-500"></i><span>Status</span></a></li>
                @if(Route::has('superadmin.logs.security'))
                <li><a href="{{ route('superadmin.logs.security') }}" class="group nav-link {{ request()->routeIs('superadmin.logs.security*') ? 'active' : '' }}"><i class="fas fa-shield-alt text-amber-500"></i><span>Security Logs</span></a></li>
                @endif
                <li><a href="{{ route('superadmin.settings') }}" class="group nav-link {{ request()->routeIs('superadmin.settings*') ? 'active' : '' }}"><i class="fas fa-sliders-h text-slate-400"></i><span>Settings</span></a></li>
            </ul>
        </li>
    @endif

    @if($isClinicAdmin || $isReceptionist || $isDentist)
        <li class="pt-1">
            <div class="nav-section-title">Clinic</div>
            <ul role="list" class="space-y-1">
                <li><a href="{{ route('clinic.patients.index') }}" class="group nav-link {{ request()->routeIs('clinic.patients.*') ? 'active' : '' }}"><i class="fas fa-user-injured text-blue-400"></i><span>Patients</span></a></li>
                <li><a href="{{ route('clinic.appointments.index') }}" class="group nav-link {{ request()->routeIs('clinic.appointments.*') ? 'active' : '' }}"><i class="fas fa-calendar-check text-violet-400"></i><span>Calendar</span></a></li>
                <li><a href="{{ route('clinic.today-schedule') }}" class="group nav-link {{ request()->routeIs('clinic.today-schedule') ? 'active' : '' }}"><i class="fas fa-clock text-orange-400"></i><span>Today</span></a></li>
                <li><a href="{{ route('clinic.emergency-appointment') }}" class="group nav-link {{ request()->routeIs('clinic.emergency-appointment') ? 'active' : '' }}"><i class="fas fa-ambulance text-rose-500"></i><span>Urgent</span></a></li>
                <li><a href="{{ route('clinic.waitlist.index') }}" class="group nav-link {{ request()->routeIs('clinic.waitlist.*') ? 'active' : '' }}"><i class="fas fa-list-ol text-cyan-400"></i><span>Waitlist</span></a></li>
                <li><a href="{{ route('clinic.consents.templates') }}" class="group nav-link {{ request()->routeIs('clinic.consents.templates*') ? 'active' : '' }}"><i class="fas fa-file-signature text-amber-400"></i><span>Forms</span></a></li>
            </ul>
        </li>

        @if($isDentist || $isClinicAdmin)
            <li class="pt-1">
                <div class="nav-section-title">Treatment</div>
                <ul role="list" class="space-y-1">
                    <li><a href="{{ route('clinic.treatment-plans.index') }}" class="group nav-link {{ request()->routeIs('clinic.treatment-plans.*') ? 'active' : '' }}"><i class="fas fa-clipboard-list text-indigo-400"></i><span>Plans</span></a></li>
                    <li><a href="{{ route('clinic.clinical-notes.index') }}" class="group nav-link {{ request()->routeIs('clinic.clinical-notes.*') ? 'active' : '' }}"><i class="fas fa-file-medical text-teal-400"></i><span>Notes</span></a></li>
                    <li><a href="{{ route('clinic.lab-orders.index') }}" class="group nav-link {{ request()->routeIs('clinic.lab-orders.*') ? 'active' : '' }}"><i class="fas fa-flask text-emerald-400"></i><span>Labs</span></a></li>
                    <li><a href="{{ route('clinic.prescriptions.index') }}" class="group nav-link {{ request()->routeIs('clinic.prescriptions.*') ? 'active' : '' }}"><i class="fas fa-file-prescription text-sky-400"></i><span>Scripts</span></a></li>
                    <li><a href="{{ route('clinic.radiology.index') }}" class="group nav-link {{ request()->routeIs('clinic.radiology.*') ? 'active' : '' }}"><i class="fas fa-x-ray text-slate-400"></i><span>X-Rays</span></a></li>
                </ul>
            </li>
        @endif
    @endif

    @if(($isClinicAdmin || $isAccountant) && ($clinic && $clinic->hasModule('Financials')))
        <li class="pt-1">
            <div class="nav-section-title">Financials</div>
            <ul role="list" class="space-y-1">
                <li><a href="{{ route('clinic.invoices.index') }}" class="group nav-link {{ request()->routeIs('clinic.invoices.*') ? 'active' : '' }}"><i class="fas fa-file-invoice-dollar text-emerald-400"></i><span>Invoices</span></a></li>
                <li><a href="{{ route('clinic.payment-plans.index') }}" class="group nav-link {{ request()->routeIs('clinic.payment-plans.*') ? 'active' : '' }}"><i class="fas fa-hand-holding-usd text-blue-400"></i><span>Installments</span></a></li>
                <li><a href="{{ route('clinic.expenses.index') }}" class="group nav-link {{ request()->routeIs('clinic.expenses.*') ? 'active' : '' }}"><i class="fas fa-wallet text-rose-400"></i><span>Spending</span></a></li>
                <li><a href="{{ route('clinic.patient-balances') }}" class="group nav-link {{ request()->routeIs('clinic.patient-balances*') ? 'active' : '' }}"><i class="fas fa-balance-scale text-amber-400"></i><span>Unpaid</span></a></li>
                @if($isAccountant || $isClinicAdmin)
                    <li><a href="{{ route('clinic.profit-loss') }}" class="group nav-link {{ request()->routeIs('clinic.profit-loss') ? 'active' : '' }}"><i class="fas fa-chart-pie text-teal-400"></i><span>Profits</span></a></li>
                    <li><a href="{{ route('clinic.tax-report') }}" class="group nav-link {{ request()->routeIs('clinic.tax-report*') ? 'active' : '' }}"><i class="fas fa-university text-orange-400"></i><span>Taxes</span></a></li>
                @endif
            </ul>
        </li>
    @endif

    @if($isClinicAdmin)
        <li class="pt-1">
            <div class="nav-section-title">Stock & Team</div>
            <ul role="list" class="space-y-1">
                <li><a href="{{ route('clinic.inventory.index') }}" class="group nav-link {{ request()->routeIs('clinic.inventory.*') ? 'active' : '' }}"><i class="fas fa-boxes text-orange-400"></i><span>Inventory</span></a></li>
                <li><a href="{{ route('clinic.purchase-orders.index') }}" class="group nav-link {{ request()->routeIs('clinic.purchase-orders.*') ? 'active' : '' }}"><i class="fas fa-file-invoice text-indigo-400"></i><span>Orders</span></a></li>
                <li><a href="{{ route('clinic.equipment.index') }}" class="group nav-link {{ request()->routeIs('clinic.equipment.*') ? 'active' : '' }}"><i class="fas fa-tools text-yellow-500"></i><span>Tools</span></a></li>
                <li><a href="{{ route('clinic.staff.index') }}" class="group nav-link {{ request()->routeIs('clinic.staff.*') ? 'active' : '' }}"><i class="fas fa-user-nurse text-pink-400"></i><span>Staff</span></a></li>
            </ul>
        </li>

        <li class="pt-1">
            <div class="nav-section-title">Practice Control</div>
            <ul role="list" class="space-y-1">
                <li><a href="{{ route('clinic.settings.index', ['tab' => 'general']) }}" class="group nav-link {{ request()->routeIs('clinic.settings.*') ? 'active' : '' }}"><i class="fas fa-sliders-h text-blue-400"></i><span>Info Setup</span></a></li>
                <li><a href="{{ route('clinic.system-settings.index', ['tab' => 'features']) }}" class="group nav-link {{ request()->routeIs('clinic.system-settings.*') ? 'active' : '' }}"><i class="fas fa-cogs text-indigo-400"></i><span>Features</span></a></li>
                <li><a href="{{ route('clinic.system-settings.index', ['tab' => 'landing-page']) }}" class="group nav-link {{ request()->routeIs('clinic.system-settings.*') && request('tab') === 'landing-page' ? 'active' : '' }}"><i class="fas fa-globe text-emerald-400"></i><span>Web Editor</span></a></li>
            </ul>
        </li>
    @endif

    <li class="pt-1">
        <div class="nav-section-title">Communication</div>
        <ul role="list" class="space-y-1">
            <li><a href="{{ route('emails.index') }}" class="group nav-link {{ request()->routeIs('emails.*') ? 'active' : '' }}"><i class="fas fa-envelope text-blue-300"></i><span>Mail</span></a></li>
            <li><a href="{{ route('notifications.index') }}" class="group nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}"><i class="fas fa-bell text-cyan-300"></i><span>Alerts</span></a></li>
        </ul>
    </li>
@endif
