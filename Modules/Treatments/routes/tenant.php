<?php

use Illuminate\Support\Facades\Route;
use Modules\Treatments\Http\Controllers\TreatmentController;

Route::prefix('treatments')->group(function () {
    Route::get('/plans', [TreatmentController::class, 'index'])->name('treatment-plans.index');
    Route::get('/plans/create', [TreatmentController::class, 'create'])->name('treatment-plans.create');
    Route::post('/plans', [TreatmentController::class, 'store'])->name('treatment-plans.store');
    Route::get('/plans/{treatmentPlan}', [TreatmentController::class, 'show'])->name('treatment-plans.show');
    Route::patch('/plans/{treatmentPlan}/status', [TreatmentController::class, 'updateStatus'])->name('treatment-plans.status.update');
});
