<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\NepaliDateHelper;
use App\Helpers\LayoutHelper;

class HelperServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Make NepaliDateHelper available globally
        $this->app->singleton('nepali-date', function () {
            return new NepaliDateHelper();
        });
        
        // Make LayoutHelper available globally
        $this->app->singleton('layout-helper', function () {
            return new LayoutHelper();
        });
    }
}