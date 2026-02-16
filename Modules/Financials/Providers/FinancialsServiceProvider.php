<?php

namespace Modules\Financials\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class FinancialsServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->registerRoutes();
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'financials');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Modules\Financials\Console\Commands\CheckOverdueInstallments::class,
            ]);
        }
    }

    protected function registerRoutes()
    {
        if (file_exists(__DIR__ . '/../routes/tenant.php')) {
            Route::middleware(['web', 'auth', \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class, \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class, 'module.enabled:Financials'])
                ->group(__DIR__ . '/../routes/tenant.php');
        }
    }
}
