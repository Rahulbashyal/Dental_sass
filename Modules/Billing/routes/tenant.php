<?php

use Illuminate\Support\Facades\Route;
use Modules\Billing\Http\Controllers\BillingController;

Route::prefix('billing')->group(function () {
    Route::get('/', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/upgrade', [BillingController::class, 'upgrade'])->name('billing.upgrade');
    Route::post('/subscribe/{plan}', [BillingController::class, 'subscribe'])->name('billing.subscribe');
    Route::get('/invoices', [BillingController::class, 'invoices'])->name('billing.invoices');
    Route::get('/invoices/{invoice}', [BillingController::class, 'showInvoice'])->name('billing.invoices.show');
});
