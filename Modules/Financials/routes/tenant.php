<?php

use Illuminate\Support\Facades\Route;
use Modules\Financials\Http\Controllers\InvoiceController;

Route::prefix('financials')->group(function () {
    Route::get('/dashboard', [\Modules\Financials\Http\Controllers\DashboardController::class, 'index'])->name('financials.dashboard');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::patch('/invoices/{invoice}/pay', [InvoiceController::class, 'markAsPaid'])->name('invoices.pay');

    // Expenses
    Route::get('/expenses', [\Modules\Financials\Http\Controllers\ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [\Modules\Financials\Http\Controllers\ExpenseController::class, 'store'])->name('expenses.store');
    Route::delete('/expenses/{expense}', [\Modules\Financials\Http\Controllers\ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Payments
    Route::get('/payments', [\Modules\Financials\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');

    // Payment Plans
    Route::get('/payment-plans', [\Modules\Financials\Http\Controllers\PaymentPlanController::class, 'index'])->name('payment-plans.index');
    Route::get('/payment-plans/create', [\Modules\Financials\Http\Controllers\PaymentPlanController::class, 'create'])->name('payment-plans.create');
    Route::post('/payment-plans', [\Modules\Financials\Http\Controllers\PaymentPlanController::class, 'store'])->name('payment-plans.store');
    Route::get('/payment-plans/{paymentPlan}', [\Modules\Financials\Http\Controllers\PaymentPlanController::class, 'show'])->name('payment-plans.show');
    Route::patch('/installments/{installment}/pay', [\Modules\Financials\Http\Controllers\PaymentPlanController::class, 'payInstallment'])->name('installments.pay');
});
