<?php

use App\Http\Controllers\{AuthController, DashboardController, ClinicController, PatientController, AppointmentController};
use Illuminate\Support\Facades\Route;

// Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// 2FA routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/2fa/setup', [\App\Http\Controllers\TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/enable', [\App\Http\Controllers\TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::get('/2fa/verify', [\App\Http\Controllers\TwoFactorController::class, 'verify'])->name('2fa.verify');
    Route::post('/2fa/verify', [\App\Http\Controllers\TwoFactorController::class, 'verifyCode'])->name('2fa.verify.code');
    Route::post('/2fa/disable', [\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('2fa.disable');
});

// Health check route
Route::get('/health', [\App\Http\Controllers\HealthCheckController::class, 'check'])->name('health.check');

Route::get('/', function () {
    $content = \App\Models\LandingPageContent::getContent();
    return view('welcome', compact('content'));
});

// Auth routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->middleware(['throttle:10,5', 'csrf', 'auto.verify.admins']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register'])->middleware(['throttle:3,60', 'csrf']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware(['auth', 'csrf']);

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes (Superadmin and Clinic Admin)
Route::middleware(['auth', 'verified', 'role:superadmin|clinic_admin'])->prefix('admin')->group(function () {
    // Role Management
    Route::resource('roles', \App\Http\Controllers\RoleManagementController::class)->names([
        'index' => 'admin.roles.index',
        'create' => 'admin.roles.create',
        'store' => 'admin.roles.store',
        'edit' => 'admin.roles.edit',
        'update' => 'admin.roles.update',
        'destroy' => 'admin.roles.destroy'
    ]);
    Route::post('/roles/assign', [\App\Http\Controllers\RoleManagementController::class, 'assignRole'])->name('admin.roles.assign');
    
    // CMS Routes
    Route::prefix('cms')->group(function () {
        // Services Management
        Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class)->names([
            'index' => 'admin.cms.services.index',
            'create' => 'admin.cms.services.create',
            'store' => 'admin.cms.services.store',
            'edit' => 'admin.cms.services.edit',
            'update' => 'admin.cms.services.update',
            'destroy' => 'admin.cms.services.destroy',
        ]);
        Route::patch('/services/{service}/toggle', [\App\Http\Controllers\Admin\ServiceController::class, 'toggle'])->name('admin.cms.services.toggle');
        
        // Team Management
        Route::resource('team', \App\Http\Controllers\Admin\TeamController::class)->names([
            'index' => 'admin.cms.team.index',
            'create' => 'admin.cms.team.create',
            'store' => 'admin.cms.team.store',
            'edit' => 'admin.cms.team.edit',
            'update' => 'admin.cms.team.update',
            'destroy' => 'admin.cms.team.destroy',
        ]);
        Route::patch('/team/{team}/toggle', [\App\Http\Controllers\Admin\TeamController::class, 'toggle'])->name('admin.cms.team.toggle');
    });
});

// Superadmin routes
Route::middleware(['auth', 'verified', 'role:superadmin'])->prefix('superadmin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::get('/chart-data', [\App\Http\Controllers\SuperAdminController::class, 'getChartData'])->name('superadmin.chart-data');
    Route::resource('clinics', ClinicController::class);
    Route::get('/users', [\App\Http\Controllers\SuperAdminController::class, 'users'])->name('superadmin.users');
    Route::patch('/clinics/{clinic}/toggle-status', [\App\Http\Controllers\SuperAdminController::class, 'toggleClinicStatus'])->name('superadmin.toggle-clinic-status');
    Route::get('/analytics', [\App\Http\Controllers\SuperAdminController::class, 'analytics'])->name('superadmin.analytics');
    Route::get('/settings', [\App\Http\Controllers\SuperAdminController::class, 'systemSettings'])->name('superadmin.settings');
    
    // Medication Management
    Route::get('/medications', [\App\Http\Controllers\MedicationController::class, 'index'])->name('medications.index');
    Route::post('/medications', [\App\Http\Controllers\MedicationController::class, 'store'])->name('medications.store');
    Route::patch('/medications/{medication}/toggle', [\App\Http\Controllers\MedicationController::class, 'toggle'])->name('medications.toggle');
    
    // CRM Routes
    Route::prefix('crm')->group(function () {
        Route::get('/leads', [\App\Http\Controllers\CrmController::class, 'leads'])->name('superadmin.crm.leads');
        Route::get('/leads/create', [\App\Http\Controllers\CrmController::class, 'createLead'])->name('superadmin.crm.create-lead');
        Route::post('/leads', [\App\Http\Controllers\CrmController::class, 'storeLead'])->name('superadmin.crm.store-lead');
        Route::get('/campaigns', [\App\Http\Controllers\CrmController::class, 'campaigns'])->name('superadmin.crm.campaigns');
        Route::get('/campaigns/create', [\App\Http\Controllers\CrmController::class, 'createCampaign'])->name('superadmin.crm.create-campaign');
        Route::post('/campaigns', [\App\Http\Controllers\CrmController::class, 'storeCampaign'])->name('superadmin.crm.store-campaign');
        Route::get('/email-logs', [\App\Http\Controllers\CrmController::class, 'emailLogs'])->name('superadmin.crm.email-logs');
    });
    
    // Content Management Routes
    Route::prefix('content')->group(function () {
        Route::get('/landing', [\App\Http\Controllers\SuperAdminController::class, 'landingPage'])->name('superadmin.content.landing');
        Route::post('/landing', [\App\Http\Controllers\SuperAdminController::class, 'updateLanding'])->name('superadmin.content.landing.update');
        Route::get('/blog', [\App\Http\Controllers\SuperAdminController::class, 'blogPosts'])->name('superadmin.content.blog');
        Route::post('/blog', [\App\Http\Controllers\SuperAdminController::class, 'storeBlog'])->name('superadmin.content.blog.store');
        Route::get('/testimonials', [\App\Http\Controllers\SuperAdminController::class, 'testimonials'])->name('superadmin.content.testimonials');
        Route::post('/testimonials', [\App\Http\Controllers\SuperAdminController::class, 'storeTestimonial'])->name('superadmin.content.testimonials.store');
    });
    
    // Debug route
    Route::get('/debug', function() {
        return response()->json([
            'user' => \Illuminate\Support\Facades\Auth::user()->name,
            'roles' => \Illuminate\Support\Facades\Auth::user()->getRoleNames(),
            'is_superadmin' => \Illuminate\Support\Facades\Auth::user()->hasRole('superadmin')
        ]);
    })->name('superadmin.debug');
});

// Test routes
Route::get('/test-invoice', function() {
    return 'Invoice test route works';
})->middleware('auth');

Route::get('/test-invoice-create', [\App\Http\Controllers\InvoiceController::class, 'create'])->middleware('auth');

// Test clinic configuration
Route::get('/test-config', [\App\Http\Controllers\TestConfigController::class, 'testConfig'])->middleware('auth');

// Direct invoice create route without clinic prefix
Route::get('/invoices/create', [\App\Http\Controllers\InvoiceController::class, 'create'])->middleware('auth')->name('direct.invoices.create');

// Invoice create route - direct access for accountants
Route::get('/clinic/invoices/create', [\App\Http\Controllers\InvoiceController::class, 'create'])
    ->middleware(['auth', 'role:accountant|clinic_admin'])
    ->name('invoices.create');

// Clinic routes - Common for all clinic staff
Route::middleware(['auth', 'verified'])->prefix('clinic')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'clinic'])->name('clinic.dashboard');
    
    // Receptionist & Dentist routes - Appointment and Patient Management
    Route::middleware(['role:receptionist|dentist|clinic_admin'])->group(function () {
        Route::resource('patients', PatientController::class);
        Route::resource('appointments', AppointmentController::class)->names([
            'index' => 'clinic.appointments.index',
            'create' => 'clinic.appointments.create',
            'store' => 'clinic.appointments.store',
            'show' => 'clinic.appointments.show',
            'edit' => 'clinic.appointments.edit',
            'update' => 'clinic.appointments.update',
            'destroy' => 'clinic.appointments.destroy'
        ]);

        // Additional appointment actions
        Route::put('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
        
        // Prescription routes (dentist only)
        Route::middleware('role:dentist|clinic_admin')->group(function () {
            Route::resource('prescriptions', \App\Http\Controllers\PrescriptionController::class);
            Route::get('prescriptions/{prescription}/pdf', [\App\Http\Controllers\PrescriptionController::class, 'pdf'])->name('prescriptions.pdf');
            Route::get('prescriptions/{prescription}/print', [\App\Http\Controllers\PrescriptionController::class, 'print'])->name('prescriptions.print');
            
            // Medication search
            Route::get('medications/search', [\App\Http\Controllers\MedicationController::class, 'search'])->name('medications.search');
        });

        Route::resource('waitlist', \App\Http\Controllers\WaitlistController::class);
        Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'clinicIndex'])->name('clinic.notifications.index');
        Route::post('/notifications/send-reminders', [\App\Http\Controllers\NotificationController::class, 'sendAppointmentReminders'])->name('notifications.send-reminders');
        
        // Enhanced Receptionist Features
        Route::post('/appointments/{appointment}/check-in', [\App\Http\Controllers\ReceptionistController::class, 'checkIn'])->name('appointments.check-in');
        Route::post('/appointments/{appointment}/no-show', [\App\Http\Controllers\ReceptionistController::class, 'noShow'])->name('appointments.no-show');
        Route::post('/emergency-appointment', [\App\Http\Controllers\ReceptionistController::class, 'emergencyAppointment'])->name('emergency-appointment');
        Route::get('/today-schedule', [\App\Http\Controllers\ReceptionistController::class, 'todaySchedule'])->name('today-schedule');
        Route::get('/patient-search', [\App\Http\Controllers\ReceptionistController::class, 'quickSearch'])->name('patient-search');
        Route::get('/print-schedule', [\App\Http\Controllers\ReceptionistController::class, 'printSchedule'])->name('print-schedule');
    });
    
    // Financial data - read access for dentists, full access for accountants/admins
    Route::middleware(['role:dentist|accountant|clinic_admin'])->group(function () {
        Route::get('/invoices', [\App\Http\Controllers\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/reports', [\App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
    });
    
    // Accountant routes - Financial Management (write access)
    Route::middleware(['role:accountant|clinic_admin'])->group(function () {
        Route::post('/invoices', [\App\Http\Controllers\InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/invoices/{invoice}/edit', [\App\Http\Controllers\InvoiceController::class, 'edit'])->name('invoices.edit');
        Route::put('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'update'])->name('invoices.update');
        Route::delete('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'destroy'])->name('invoices.destroy');
        Route::patch('/invoices/{invoice}/mark-paid', [\App\Http\Controllers\InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
        Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
        
        // Enhanced Accountant Features
        Route::get('/payment-tracking', [\App\Http\Controllers\AccountantController::class, 'paymentTracking'])->name('payment-tracking');
        Route::get('/expenses', [\App\Http\Controllers\AccountantController::class, 'expenseTracking'])->name('expenses');
        Route::get('/profit-loss', [\App\Http\Controllers\AccountantController::class, 'profitLossReport'])->name('profit-loss');
        Route::get('/patient-balances', [\App\Http\Controllers\AccountantController::class, 'patientBalances'])->name('patient-balances');
        Route::get('/tax-report', [\App\Http\Controllers\AccountantController::class, 'taxReport'])->name('tax-report');
        
        // Journal & Ledger Management
        Route::get('/journal-entries', [\App\Http\Controllers\AccountantController::class, 'journalEntries'])->name('journal-entries');
        Route::get('/journal-entries/create', [\App\Http\Controllers\AccountantController::class, 'createJournalEntry'])->name('journal-entries.create');
        Route::post('/journal-entries', [\App\Http\Controllers\AccountantController::class, 'storeJournalEntry'])->name('journal-entries.store');
        Route::get('/ledger', [\App\Http\Controllers\AccountantController::class, 'ledger'])->name('ledger');
        Route::get('/chart-of-accounts', [\App\Http\Controllers\AccountantController::class, 'chartOfAccounts'])->name('chart-of-accounts');
        Route::post('/invoices/{invoice}/send-reminder', [\App\Http\Controllers\AccountantController::class, 'sendPaymentReminder'])->name('invoices.send-reminder');
        Route::post('/invoices/bulk-actions', [\App\Http\Controllers\AccountantController::class, 'bulkInvoiceActions'])->name('invoices.bulk-actions');
    });
    
    // Treatment plans - accessible by dentists and admins
    Route::middleware('role:dentist|clinic_admin')->group(function () {
        Route::resource('treatment-plans', \App\Http\Controllers\TreatmentPlanController::class);
    });
    
    // Admin only routes
    Route::middleware('role:clinic_admin')->group(function () {
        Route::resource('staff', \App\Http\Controllers\StaffController::class);
        Route::resource('recurring-appointments', \App\Http\Controllers\RecurringAppointmentController::class);
        Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
        Route::get('/settings/business-hours', [\App\Http\Controllers\SettingsController::class, 'businessHours'])->name('settings.business-hours');
        Route::put('/settings/business-hours', [\App\Http\Controllers\SettingsController::class, 'updateBusinessHours'])->name('settings.update-business-hours');
        Route::get('/subscription', [\App\Http\Controllers\SubscriptionController::class, 'current'])->name('subscriptions.current');
        Route::get('/landing-page-manager', [\App\Http\Controllers\ClinicAdminController::class, 'landingPage'])->name('landing-page-manager');
        Route::put('/landing-page-manager', [\App\Http\Controllers\ClinicAdminController::class, 'updateLandingPage'])->name('landing-page-manager.update');
        
        // Clinic CRM Routes
        Route::prefix('crm')->name('clinic.crm.')->group(function () {
            Route::get('/leads', [\App\Http\Controllers\ClinicCrmController::class, 'index'])->name('leads.index');
            Route::get('/leads/create', [\App\Http\Controllers\ClinicCrmController::class, 'create'])->name('leads.create');
            Route::post('/leads', [\App\Http\Controllers\ClinicCrmController::class, 'store'])->name('leads.store');
        });
    });
});

// Subscription routes
Route::middleware(['auth', 'verified'])->prefix('subscriptions')->group(function () {
    Route::get('/plans', [\App\Http\Controllers\SubscriptionController::class, 'plans'])->name('subscriptions.plans');
    Route::post('/upgrade/{planId}', [\App\Http\Controllers\SubscriptionController::class, 'upgrade'])->name('subscriptions.upgrade');
});

// Email System Routes - Available to all authenticated users
Route::middleware(['auth', 'verified'])->prefix('emails')->group(function () {
    Route::get('/', [\App\Http\Controllers\EmailController::class, 'index'])->name('emails.index');
    Route::get('/compose', [\App\Http\Controllers\EmailController::class, 'compose'])->name('emails.compose');
    Route::post('/send', [\App\Http\Controllers\EmailController::class, 'send'])->name('emails.send');
    Route::post('/bulk', [\App\Http\Controllers\EmailController::class, 'sendBulk'])->name('emails.bulk');
    Route::get('/template/{template}', [\App\Http\Controllers\EmailController::class, 'getTemplate'])->name('emails.template');
    Route::get('/{email}', [\App\Http\Controllers\EmailController::class, 'show'])->name('emails.show');
    Route::post('/appointment/{appointment}/reminder', [\App\Http\Controllers\EmailController::class, 'sendAppointmentReminder'])->name('emails.appointment.reminder');
});

// Global Notification Routes - Available to all authenticated users
Route::middleware(['auth', 'verified'])->prefix('notifications')->group(function () {
    Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('global.notifications.index');
    Route::post('/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/unread-count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
});

// Patient Portal routes
Route::prefix('patient')->group(function () {
    Route::get('/login', [\App\Http\Controllers\PatientPortalController::class, 'login'])->name('patient.login');
    Route::post('/login', [\App\Http\Controllers\PatientPortalController::class, 'login'])->middleware('throttle:5,10');
    Route::get('/dashboard', [\App\Http\Controllers\PatientPortalController::class, 'dashboard'])->name('patient.dashboard');
    Route::get('/appointments', [\App\Http\Controllers\PatientPortalController::class, 'appointments'])->name('patient.appointments');
    Route::get('/invoices', [\App\Http\Controllers\PatientPortalController::class, 'invoices'])->name('patient.invoices');
    Route::get('/profile', [\App\Http\Controllers\PatientPortalController::class, 'profile'])->name('patient.profile');
    Route::put('/profile', [\App\Http\Controllers\PatientPortalController::class, 'updateProfile'])->name('patient.profile.update');
    Route::get('/payment/{invoice}', [\App\Http\Controllers\PaymentController::class, 'show'])->name('patient.payment');
    Route::post('/payment/{invoice}', [\App\Http\Controllers\PaymentController::class, 'process'])->name('patient.payment.process');
    Route::post('/logout', [\App\Http\Controllers\PatientPortalController::class, 'logout'])->name('patient.logout');
});

// Public clinic pages
Route::get('/clinic/{slug}', function($slug) {
    $clinic = \App\Models\Clinic::where('slug', $slug)->firstOrFail();
    $content = \App\Models\LandingPageContent::getContent($clinic->id);
    return view('clinic.comprehensive-landing', compact('content', 'clinic'));
})->name('clinic.landing');

// Public appointment booking
Route::get('/clinic/{clinic:slug}/book', [AppointmentController::class, 'publicBook'])->name('clinic.book');
Route::post('/clinic/{clinic:slug}/book', [AppointmentController::class, 'storePublicBook'])->name('clinic.book.store')->middleware('throttle:3,60');

