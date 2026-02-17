<div class="bg-red-800 text-white w-64 min-h-screen p-4">
    <div class="mb-8">
        <h2 class="text-xl font-bold">SuperAdmin Panel</h2>
        <p class="text-red-200 text-sm">System Management</p>
    </div>
    
    <nav class="space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded hover:bg-red-700 {{ request()->routeIs('dashboard') ? 'bg-red-700' : '' }}">
            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
        </a>
        
        <div class="space-y-1">
            <p class="text-red-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">System Management</p>
            <a href="{{ route('admin.roles.index') }}" class="flex items-center p-3 rounded hover:bg-red-700">
                <i class="fas fa-users-cog mr-3"></i> Role Management
            </a>
            <a href="{{ route('clinics.index') }}" class="flex items-center p-3 rounded hover:bg-red-700">
                <i class="fas fa-building mr-3"></i> Clinic Management
            </a>
        </div>
        
        <div class="space-y-1">
            <p class="text-red-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Analytics & Users</p>
            <a href="{{ route('superadmin.analytics') }}" class="flex items-center p-3 rounded hover:bg-red-700 {{ request()->routeIs('superadmin.analytics') ? 'bg-red-700' : '' }}">
                <i class="fas fa-chart-line mr-3"></i> Analytics
            </a>
            <a href="{{ route('superadmin.users') }}" class="flex items-center p-3 rounded hover:bg-red-700 {{ request()->routeIs('superadmin.users') ? 'bg-red-700' : '' }}">
                <i class="fas fa-users mr-3"></i> Users
            </a>
            <a href="{{ route('superadmin.tenants.index') }}" class="flex items-center p-3 rounded hover:bg-red-700 {{ request()->routeIs('superadmin.tenants*') ? 'bg-red-700' : '' }}">
                <i class="fas fa-network-wired mr-3"></i> Tenants
            </a>
        </div>
        
        <div class="space-y-1">
            <p class="text-red-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">Content Management</p>
            <a href="{{ route('superadmin.content.landing') }}" class="flex items-center p-3 rounded hover:bg-red-700 {{ request()->routeIs('superadmin.content.landing') ? 'bg-red-700' : '' }}">
                <i class="fas fa-home mr-3"></i> Landing Page
            </a>
            <a href="{{ route('superadmin.content.blog') }}" class="flex items-center p-3 rounded hover:bg-red-700 {{ request()->routeIs('superadmin.content.blog') ? 'bg-red-700' : '' }}">
                <i class="fas fa-blog mr-3"></i> Blog Posts
            </a>
            <a href="{{ route('superadmin.content.testimonials') }}" class="flex items-center p-3 rounded hover:bg-red-700 {{ request()->routeIs('superadmin.content.testimonials') ? 'bg-red-700' : '' }}">
                <i class="fas fa-star mr-3"></i> Testimonials
            </a>
        </div>
        
        <div class="space-y-1">
            <p class="text-red-300 text-xs uppercase tracking-wide font-semibold mt-4 mb-2">CRM</p>
            <a href="{{ route('superadmin.crm.leads') }}" class="flex items-center p-3 rounded hover:bg-red-700 {{ request()->routeIs('superadmin.crm.leads') ? 'bg-red-700' : '' }}">
                <i class="fas fa-user-plus mr-3"></i> Leads
            </a>
            <a href="{{ route('superadmin.crm.campaigns') }}" class="flex items-center p-3 rounded hover:bg-red-700 {{ request()->routeIs('superadmin.crm.campaigns') ? 'bg-red-700' : '' }}">
                <i class="fas fa-bullhorn mr-3"></i> Campaigns
            </a>
        </div>
    </nav>
</div>