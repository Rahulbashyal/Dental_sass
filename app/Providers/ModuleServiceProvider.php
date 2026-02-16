<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Core\Modular\ModuleLoader;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        ModuleLoader::syncModules();
        ModuleLoader::bootModules();
    }
}
