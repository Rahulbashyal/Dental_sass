<?php

use Illuminate\Support\Facades\Route;
use Modules\Patients\Http\Controllers\PatientController;

Route::prefix('patients')->group(function () {
    Route::get('/', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/{patient}', [PatientController::class, 'update'])->name('patients.update');
});
