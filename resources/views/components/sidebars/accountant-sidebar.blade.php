<div class="bg-green-800 text-white w-64 min-h-screen p-4">
    <div class="mb-8">
        <h2 class="text-xl font-bold">Finance Panel</h2>
        <p class="text-green-200 text-sm">Financial Management</p>
    </div>
    
    <nav class="space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded hover:bg-green-700 {{ request()->routeIs('dashboard') ? 'bg-green-700' : '' }}">
            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
        </a>
        
        <div class="space-y-1">
            <p class="text-green-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Financial Management</p>
            <a href="{{ route('invoices.index') }}" class="flex items-center p-3 rounded hover:bg-green-700 {{ request()->routeIs('invoices.*') ? 'bg-green-700' : '' }}">
                <i class="fas fa-file-invoice mr-3"></i> Invoices
            </a>
            <a href="{{ route('direct.invoices.create') }}" class="flex items-center p-3 rounded hover:bg-green-700 {{ request()->routeIs('direct.invoices.create') ? 'bg-green-700' : '' }}">
                <i class="fas fa-plus mr-3"></i> Create Invoice
            </a>
            <a href="{{ route('payment-tracking') }}" class="flex items-center p-3 rounded hover:bg-green-700 {{ request()->routeIs('payment-tracking') ? 'bg-green-700' : '' }}">
                <i class="fas fa-credit-card mr-3"></i> Payment Tracking
            </a>
            <a href="{{ route('expenses') }}" class="flex items-center p-3 rounded hover:bg-green-700 {{ request()->routeIs('expenses') ? 'bg-green-700' : '' }}">
                <i class="fas fa-receipt mr-3"></i> Expenses
            </a>
        </div>
        
        <div class="space-y-1">
            <p class="text-green-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Accounting</p>
            <a href="{{ route('journal-entries') }}" class="flex items-center p-3 rounded hover:bg-green-700 {{ request()->routeIs('journal-entries*') ? 'bg-green-700' : '' }}">
                <i class="fas fa-book mr-3"></i> Journal Entries
            </a>
            <a href="{{ route('ledger') }}" class="flex items-center p-3 rounded hover:bg-green-700 {{ request()->routeIs('ledger') ? 'bg-green-700' : '' }}">
                <i class="fas fa-list-alt mr-3"></i> General Ledger
            </a>
            <a href="{{ route('chart-of-accounts') }}" class="flex items-center p-3 rounded hover:bg-green-700 {{ request()->routeIs('chart-of-accounts') ? 'bg-green-700' : '' }}">
                <i class="fas fa-sitemap mr-3"></i> Chart of Accounts
            </a>
        </div>
        
        <div class="space-y-1">
            <p class="text-green-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Reports & Analytics</p>
            <a href="{{ route('reports.index') }}" class="flex items-center p-3 rounded hover:bg-green-700 {{ request()->routeIs('reports.*') ? 'bg-green-700' : '' }}">
                <i class="fas fa-chart-bar mr-3"></i> Financial Reports
            </a>
            <a href="{{ route('analytics.dashboard') }}" class="flex items-center p-3 rounded hover:bg-green-700 {{ request()->routeIs('analytics.*') ? 'bg-green-700' : '' }}">
                <i class="fas fa-chart-line mr-3"></i> Analytics
            </a>
            <a href="{{ route('profit-loss') }}" class="flex items-center p-3 rounded hover:bg-green-700 {{ request()->routeIs('profit-loss') ? 'bg-green-700' : '' }}">
                <i class="fas fa-dollar-sign mr-3"></i> Profit & Loss
            </a>
        </div>
    </nav>
</div>