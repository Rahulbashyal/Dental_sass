<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::middleware(['auth'])->group(function () {
        Route::resource('branches', \App\Http\Controllers\Tenant\BranchController::class)->names('tenant.branches');
        Route::resource('users', \App\Http\Controllers\Tenant\UserController::class)->names('tenant.users');
        
        // Clinic Settings
        Route::get('/settings', [\App\Http\Controllers\Tenant\SettingsController::class, 'index'])->name('tenant.settings.index');
        Route::put('/settings', [\App\Http\Controllers\Tenant\SettingsController::class, 'update'])->name('tenant.settings.update');
        Route::put('/settings/hours', [\App\Http\Controllers\Tenant\SettingsController::class, 'updateBusinessHours'])->name('tenant.settings.hours');
    });
});
