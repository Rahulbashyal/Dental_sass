<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule Nepali date updates every hour
Schedule::command('nepali:update-dates')->hourly();

// Schedule daily encrypted database backups
Schedule::command('backup:database --encrypt')->daily()->at('02:00');

// Schedule installment overdue monitoring
Schedule::command('financials:check-overdue')->daily()->at('05:00');

// Schedule security monitoring
Schedule::call(function () {
    // Clean old security events from cache
    \Illuminate\Support\Facades\Cache::flush();
})->daily()->at('03:00');

// Schedule data retention cleanup weekly
Schedule::command('data:cleanup')->weekly()->sundays()->at('01:00');

// Schedule backup verification daily
Schedule::command('backup:verify')->daily()->at('04:00');

// ============================================
// STAFF EMAIL NOTIFICATIONS
// ============================================

// Send dentist daily schedules at 7:00 AM
Schedule::call(function () {
    $dentists = \App\Models\User::role('dentist')->get();
    
    foreach ($dentists as $dentist) {
        // Get today's appointments for this dentist
        $appointments = \App\Models\Appointment::where('dentist_id', $dentist->id)
            ->whereDate('appointment_date', today())
            ->with(['patient', 'clinic'])
            ->orderBy('appointment_time')
            ->get();
        
        // Send daily schedule
        $dentist->notify(new \App\Notifications\DailySchedule($dentist, $appointments));
    }
})->dailyAt('07:00')->name('send-dentist-schedules');

// Send accountant daily financial summary at 6:00 PM
Schedule::call(function () {
    $accountants = \App\Models\User::role('accountant')->get();
    
    foreach ($accountants as $accountant) {
        $clinic = $accountant->clinic;
        
        // Calculate today's financial summary
        $today = today();
        $invoices = \App\Models\Invoice::where('clinic_id', $clinic->id)
            ->whereDate('created_at', $today)
            ->get();
        
        $summary = [
            'total_revenue' => $invoices->where('status', 'paid')->sum('total_amount'),
            'invoices_generated' => $invoices->count(),
            'payments_received' => $invoices->where('status', 'paid')->count(),
            'outstanding_balance' => $invoices->where('status', 'pending')->sum('total_amount'),
            'payment_methods' => [
                'cash' => $invoices->where('payment_method', 'cash')->sum('total_amount'),
                'card' => $invoices->where('payment_method', 'card')->sum('total_amount'),
                'bank_transfer' => $invoices->where('payment_method', 'bank_transfer')->sum('total_amount'),
            ],
            'monthly_total' => \App\Models\Invoice::where('clinic_id', $clinic->id)
                ->whereMonth('created_at', now()->month)
                ->where('status', 'paid')
                ->sum('total_amount'),
            'pending_invoices' => \App\Models\Invoice::where('clinic_id', $clinic->id)
                ->where('status', 'pending')
                ->count(),
            'overdue_invoices' => \App\Models\Invoice::where('clinic_id', $clinic->id)
                ->where('status', 'pending')
                ->where('due_date', '<', now())
                ->count(),
            'collection_rate' => $invoices->count() > 0 
                ? ($invoices->where('status', 'paid')->count() / $invoices->count()) * 100 
                : 0,
        ];
        
        $accountant->notify(new \App\Notifications\DailyFinancialSummary($accountant, $summary));
    }
})->dailyAt('18:00')->name('send-financial-summaries');

// Send clinic admin weekly report every Monday at 9:00 AM
Schedule::call(function () {
    $admins = \App\Models\User::role('clinic_admin')->get();
    
    foreach ($admins as $admin) {
        $clinic = $admin->clinic;
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        
        // Calculate weekly summary
        $appointments = \App\Models\Appointment::where('clinic_id', $clinic->id)
            ->whereBetween('appointment_date', [$weekStart, $weekEnd])
            ->get();
        
        $invoices = \App\Models\Invoice::where('clinic_id', $clinic->id)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->get();
        
        $summary = [
            'total_appointments' => $appointments->count(),
            'completed_appointments' => $appointments->where('status', 'completed')->count(),
            'cancelled_appointments' => $appointments->where('status', 'cancelled')->count(),
            'no_show_appointments' => $appointments->where('status', 'no_show')->count(),
            'total_revenue' => $invoices->where('status', 'paid')->sum('total_amount'),
            'avg_revenue_per_appointment' => $appointments->where('status', 'completed')->count() > 0
                ? $invoices->where('status', 'paid')->sum('total_amount') / $appointments->where('status', 'completed')->count()
                : 0,
            'outstanding_amount' => $invoices->where('status', 'pending')->sum('total_amount'),
            'new_patients' => \App\Models\Patient::where('clinic_id', $clinic->id)
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->count(),
            'total_patients' => \App\Models\Patient::where('clinic_id', $clinic->id)->count(),
            'returning_patients' => $appointments->unique('patient_id')->count() - \App\Models\Patient::where('clinic_id', $clinic->id)
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->count(),
            'completion_rate' => $appointments->count() > 0
                ? ($appointments->where('status', 'completed')->count() / $appointments->count()) * 100
                : 0,
            'top_treatments' => $appointments->groupBy('treatment_type')
                ->map(function ($group) {
                    return [
                        'name' => $group->first()->treatment_type,
                        'count' => $group->count()
                    ];
                })
                ->sortByDesc('count')
                ->take(5)
                ->values()
                ->toArray(),
        ];
        
        $admin->notify(new \App\Notifications\WeeklyReport($admin, $summary));
    }
})->weeklyOn(1, '09:00')->name('send-weekly-reports');