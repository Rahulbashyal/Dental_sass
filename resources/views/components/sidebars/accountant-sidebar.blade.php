<div class="bg-blue-800 text-white w-64 min-h-screen p-4">
    <div class="mb-8">
        <h2 class="text-xl font-bold">Finance Panel</h2>
        <p class="text-blue-200 text-sm">Financial Management</p>
    </div>
    
    <nav class="space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
        </a>
        
        <div class="space-y-1">
            <p class="text-blue-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Financial Management</p>
            <a href="{{ route('clinic.invoices.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('invoices.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-file-invoice mr-3"></i> Invoices
            </a>
            <a href="{{ route('direct.invoices.create', ['iframe' => 1]) }}" data-modal-url="{{ route('direct.invoices.create', ['iframe' => 1]) }}" data-modal-title="Create" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('direct.invoices.create') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-plus mr-3"></i> Create Invoice
            </a>
            <a href="{{ route('clinic.payment-tracking') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->route('clinic.payment-tracking') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-credit-card mr-3"></i> Payment Tracking
            </a>
            <a href="{{ route('clinic.expenses') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.expenses') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-receipt mr-3"></i> Expenses
            </a>
            <a href="{{ route('clinic.vendors.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.vendors.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-truck mr-3"></i> Vendors
            </a>
            <a href="{{ route('clinic.credit-notes.index', ['iframe' => 1]) }}" data-modal-url="{{ route('dashboard') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
        </a>
        
        <div class="space-y-1">
            <p class="text-blue-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Financial Management</p>
            <a href="{{ route('clinic.invoices.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('invoices.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-file-invoice mr-3"></i> Invoices
            </a>
            <a href="{{ route('direct.invoices.create', ['iframe' => 1]) }}" data-modal-url="{{ route('direct.invoices.create', ['iframe' => 1]) }}" data-modal-title="Create" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('direct.invoices.create') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-plus mr-3"></i> Create Invoice
            </a>
            <a href="{{ route('clinic.payment-tracking') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->route('clinic.payment-tracking') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-credit-card mr-3"></i> Payment Tracking
            </a>
            <a href="{{ route('clinic.expenses') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.expenses') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-receipt mr-3"></i> Expenses
            </a>
            <a href="{{ route('clinic.vendors.index') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.vendors.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-truck mr-3"></i> Vendors
            </a>
            <a href="{{ route('clinic.credit-notes.index', ['iframe' => 1]) }}" data-modal-title="routeIs('clinic.credit-notes.*') ? 'bg-blue-700' : '' }}">
                 Credit Notes" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.credit-notes.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-undo mr-3"></i> Credit Notes
            </a>
        </div>
        
        <div class="space-y-1">
            <p class="text-blue-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Accounting</p>
            <a href="{{ route('clinic.journal-entries') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.journal-entries*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-book mr-3"></i> Journal Entries
            </a>
            <a href="{{ route('clinic.ledger') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.ledger') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-list-alt mr-3"></i> General Ledger
            </a>
            <a href="{{ route('clinic.chart-of-accounts') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.chart-of-accounts') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-sitemap mr-3"></i> Chart of Accounts
            </a>
        </div>
        
        <div class="space-y-1">
            <p class="text-blue-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Reports & Analytics</p>
            <a href="{{ route('clinic.analytics.dashboard') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.analytics.dashboard') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-chart-line mr-3"></i> Analytics Dashboard
            </a>
            <a href="{{ route('clinic.analytics.pro') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.analytics.pro') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-rocket mr-3"></i> Revenue Pro
            </a>
            <a href="{{ route('clinic.profit-loss') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.profit-loss') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-dollar-sign mr-3"></i> Profit & Loss
            </a>
            <a href="{{ route('clinic.branch-comparison') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.branch-comparison') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-code-branch mr-3"></i> Branch Comparison
            </a>
            <a href="{{ route('clinic.service-profitability') }}" class="flex items-center p-3 rounded hover:bg-blue-700 {{ request()->routeIs('clinic.service-profitability') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-chart-pie mr-3"></i> Service Profitability
            </a>
        </div>
    </nav>
</div>