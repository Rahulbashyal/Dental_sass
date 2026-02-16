<?php

namespace Modules\Patients\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class PatientsServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->registerRoutes();
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'patients');
    }

    protected function registerRoutes()
    {
        if (file_exists(__DIR__ . '/../routes/tenant.php')) {
            Route::middleware(['web', 'auth', \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class, \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class, 'module.enabled:Patients'])
                ->group(__DIR__ . '/../routes/tenant.php');
        }
    }
}
