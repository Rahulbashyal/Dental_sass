<?php

use Illuminate\Support\Facades\Route;
use Modules\Appointments\Http\Controllers\AppointmentController;

Route::prefix('appointments')->group(function () {
    Route::get('/', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');
    Route::get('/events', [AppointmentController::class, 'events'])->name('appointments.events');
    Route::get('/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::patch('/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status.update');
});
