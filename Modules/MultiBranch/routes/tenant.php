<?php

use Illuminate\Support\Facades\Route;
use Modules\MultiBranch\Http\Controllers\ExecutiveController;

Route::prefix('executive')->group(function () {
    Route::get('/dashboard', [ExecutiveController::class, 'dashboard'])->name('executive.dashboard');
    Route::get('/comparison', [ExecutiveController::class, 'comparison'])->name('executive.comparison');
});
