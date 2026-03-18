<?php

use App\Http\Controllers\{AuthController, DashboardController, ClinicController, PatientController, AppointmentController};
use Illuminate\Support\Facades\Route;
use App\Models\Clinic;

// Test route - refactored to SuperAdminController
Route::get('/test-clinic-users/{clinic}', [\App\Http\Controllers\SuperAdminController::class, 'clinicUsers'])->middleware(['auth', 'role:superadmin']);

// Email verification routes - refactored to AuthController
Route::get('/email/verify', [AuthController::class, 'showVerifyEmail'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationNotification'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// 2FA routes

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/2fa/setup', [\App\Http\Controllers\TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/enable', [\App\Http\Controllers\TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::get('/2fa/verify', [\App\Http\Controllers\TwoFactorController::class, 'verify'])->name('2fa.verify');
    Route::post('/2fa/verify', [\App\Http\Controllers\TwoFactorController::class, 'verifyCode'])->name('2fa.verify.code');
    Route::post('/2fa/disable', [\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('2fa.disable');
    
    // User profile routes
    Route::get('/profile', [\App\Http\Controllers\SettingsController::class, 'profile'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/password', [\App\Http\Controllers\SettingsController::class, 'editPassword'])->name('password.edit');
    Route::put('/profile/password', [\App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('password.update');
});

// Health check route
Route::get('/health', [\App\Http\Controllers\HealthCheckController::class, 'check'])->name('health.check');

// Homepage - refactored to ClinicController
Route::get('/', [ClinicController::class, 'welcome'])->name('home');

// Auth routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->middleware(['throttle:10,5', 'auto.verify.admins']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register'])->middleware(['throttle:3,60']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware(['auth']);

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
    // Tenant provisioning UI
    Route::get('/tenants', [\App\Http\Controllers\SuperAdmin\TenantProvisionController::class, 'index'])->name('superadmin.tenants.index');
    Route::get('/tenants/create', [\App\Http\Controllers\SuperAdmin\TenantProvisionController::class, 'create'])->name('superadmin.tenants.create');
    Route::post('/tenants', [\App\Http\Controllers\SuperAdmin\TenantProvisionController::class, 'store'])->name('superadmin.tenants.store');
    Route::get('/tenants/{id}/edit', [\App\Http\Controllers\SuperAdmin\TenantProvisionController::class, 'edit'])->name('superadmin.tenants.edit');
    Route::put('/tenants/{id}', [\App\Http\Controllers\SuperAdmin\TenantProvisionController::class, 'update'])->name('superadmin.tenants.update');
    Route::delete('/tenants/{id}', [\App\Http\Controllers\SuperAdmin\TenantProvisionController::class, 'destroy'])->name('superadmin.tenants.destroy');
    Route::post('/tenants/{id}/reprovision', [\App\Http\Controllers\SuperAdmin\TenantProvisionController::class, 'reprovision'])->name('superadmin.tenants.reprovision');
    Route::get('/tenants/{id}/logs', [\App\Http\Controllers\SuperAdmin\TenantProvisionController::class, 'logs'])->name('superadmin.tenants.logs');
    
    // Module Management (superadmin only - no module check needed here)
    Route::get('/modules', [\App\Http\Controllers\SuperAdmin\ModuleController::class, 'index'])->name('superadmin.modules.index');
    Route::patch('/modules/{module}/toggle', [\App\Http\Controllers\SuperAdmin\ModuleController::class, 'toggle'])->name('superadmin.modules.toggle');
    Route::put('/modules/{module}', [\App\Http\Controllers\SuperAdmin\ModuleController::class, 'update'])->name('superadmin.modules.update');
    Route::patch('/modules/{module}/status', [\App\Http\Controllers\SuperAdmin\ModuleController::class, 'setStatus'])->name('superadmin.modules.setStatus');
    
    // UI Template Management
    Route::get('/templates', [\App\Http\Controllers\SuperAdmin\TemplateController::class, 'index'])->name('superadmin.templates.index');
    Route::post('/templates/sync', [\App\Http\Controllers\SuperAdmin\TemplateController::class, 'sync'])->name('superadmin.templates.sync');
    Route::get('/templates/download-sample', [\App\Http\Controllers\SuperAdmin\TemplateController::class, 'downloadSample'])->name('superadmin.templates.download-sample');
    Route::post('/templates/upload', [\App\Http\Controllers\SuperAdmin\TemplateController::class, 'upload'])->name('superadmin.templates.upload');
    Route::post('/templates/{id}/toggle', [\App\Http\Controllers\SuperAdmin\TemplateController::class, 'toggle'])->name('superadmin.templates.toggle');
    Route::get('/templates/preview/{slug}', [\App\Http\Controllers\SuperAdmin\TemplateController::class, 'preview'])->name('superadmin.templates.preview');

    Route::get('/users', [\App\Http\Controllers\SuperAdminController::class, 'users'])->name('superadmin.users');
    Route::get('/users/clinic/{clinic:id}', [\App\Http\Controllers\SuperAdminController::class, 'clinicUsers'])->name('superadmin.users.clinic');
    Route::get('/users/create', [\App\Http\Controllers\SuperAdminController::class, 'createUser'])->name('superadmin.users.create');
    Route::post('/users', [\App\Http\Controllers\SuperAdminController::class, 'storeUser'])->name('superadmin.users.store');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\SuperAdminController::class, 'editUser'])->name('superadmin.users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\SuperAdminController::class, 'updateUser'])->name('superadmin.users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\SuperAdminController::class, 'destroyUser'])->name('superadmin.users.destroy');
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
    Route::get('/debug', [\App\Http\Controllers\SuperAdminController::class, 'debug'])->name('superadmin.debug');
});

// Test routes - Disabled for production safety
// Route::get('/test-invoice', [\App\Http\Controllers\InvoiceController::class, 'test'])->middleware('auth');
// Route::get('/test-simple-clinic/{id}', [ClinicController::class, 'testSimple']);
// Route::get('/test-invoice-create', [\App\Http\Controllers\InvoiceController::class, 'create'])->middleware('auth');
// Route::get('/test-config', [\App\Http\Controllers\TestConfigController::class, 'testConfig'])->middleware('auth');

// Direct invoice create route - protected with auth and role
Route::get('/invoices/create', [\App\Http\Controllers\InvoiceController::class, 'create'])
    ->middleware(['auth', 'verified', 'role:accountant|clinic_admin|superadmin'])
    ->name('direct.invoices.create');



// Clinic routes - Common for all clinic staff
Route::middleware(['auth', 'verified', 'clinic.access', 'resource.owner'])->prefix('clinic')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'clinic'])->name('clinic.dashboard');
    
    // Patients Module
    Route::middleware(['role:receptionist|dentist|clinic_admin', 'module.enabled:Patients'])->group(function () {
        Route::resource('patients', PatientController::class)->names([
            'index' => 'clinic.patients.index',
            'create' => 'clinic.patients.create',
            'store' => 'clinic.patients.store',
            'show' => 'clinic.patients.show',
            'edit' => 'clinic.patients.edit',
            'update' => 'clinic.patients.update',
            'destroy' => 'clinic.patients.destroy'
        ]);
    });

    // Appointments Module
    Route::middleware(['role:receptionist|dentist|clinic_admin', 'module.enabled:Appointments'])->group(function () {
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
        Route::put('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('clinic.appointments.cancel');
    });

    // Prescription routes (Treatments Module - dentist only)
    Route::middleware(['role:dentist|clinic_admin', 'module.enabled:Treatments'])->group(function () {
            Route::resource('prescriptions', \App\Http\Controllers\PrescriptionController::class)->names([
                'index' => 'clinic.prescriptions.index',
                'create' => 'clinic.prescriptions.create',
                'store' => 'clinic.prescriptions.store',
                'show' => 'clinic.prescriptions.show',
                'edit' => 'clinic.prescriptions.edit',
                'update' => 'clinic.prescriptions.update',
                'destroy' => 'clinic.prescriptions.destroy'
            ]);
            Route::get('prescriptions/{prescription}/pdf', [\App\Http\Controllers\PrescriptionController::class, 'pdf'])->name('clinic.prescriptions.pdf');
            Route::get('prescriptions/{prescription}/print', [\App\Http\Controllers\PrescriptionController::class, 'print'])->name('clinic.prescriptions.print');
            Route::post('prescriptions/{prescription}/dispense', [\App\Http\Controllers\PrescriptionController::class, 'dispense'])->name('clinic.prescriptions.dispense');
            
            // Medication search
            Route::get('medications/search', [\App\Http\Controllers\MedicationController::class, 'search'])->name('clinic.medications.search');

            // Clinical Ops
            Route::get('/clinical-notes', [\App\Http\Controllers\ClinicalNoteController::class, 'index'])->name('clinic.clinical-notes.index');
            Route::post('/patients/{patient}/clinical-notes', [\App\Http\Controllers\ClinicalNoteController::class, 'store'])->name('clinic.clinical-notes.store');
            Route::delete('/clinical-notes/{note}', [\App\Http\Controllers\ClinicalNoteController::class, 'destroy'])->name('clinic.clinical-notes.destroy');
            
            // Case Discussions
            Route::get('/patients/{patient}/messages', [\App\Http\Controllers\CaseDiscussionController::class, 'index'])->name('clinic.patients.messages.index');
            Route::post('/patients/{patient}/messages', [\App\Http\Controllers\CaseDiscussionController::class, 'store'])->name('clinic.patients.messages.store');
            
            Route::post('/patients/{patient}/treatment-plans', [\App\Http\Controllers\TreatmentPlanController::class, 'store'])->name('clinic.treatment-plans.store');
            Route::patch('/treatment-plans/{plan}/status', [\App\Http\Controllers\TreatmentPlanController::class, 'updateStatus'])->name('clinic.treatment-plans.status');

            // Consent & Legal
            Route::get('/consents/templates', [\App\Http\Controllers\ConsentController::class, 'index'])->name('clinic.consents.templates');
            Route::post('/consents/templates', [\App\Http\Controllers\ConsentController::class, 'storeTemplate'])->name('clinic.consents.templates.store');
            Route::post('/patients/{patient}/consents', [\App\Http\Controllers\ConsentController::class, 'sign'])->name('clinic.patients.consents.sign');

            // Radiology & Imaging
            Route::get('/radiology', [\App\Http\Controllers\RadiologyController::class, 'index'])->name('clinic.radiology.index');
            Route::get('/radiology/create', [\App\Http\Controllers\RadiologyController::class, 'create'])->name('clinic.radiology.create');
            Route::post('/radiology', [\App\Http\Controllers\RadiologyController::class, 'store'])->name('clinic.radiology.store');
            Route::get('/radiology/{study}', [\App\Http\Controllers\RadiologyController::class, 'show'])->name('clinic.radiology.show');
            Route::patch('/radiology/{study}/findings', [\App\Http\Controllers\RadiologyController::class, 'updateFindings'])->name('clinic.radiology.findings');

            // Lab Orders
            Route::get('/lab-orders', [\App\Http\Controllers\LabOrderController::class, 'index'])->name('clinic.lab-orders.index');
            Route::post('/lab-orders', [\App\Http\Controllers\LabOrderController::class, 'store'])->name('clinic.lab-orders.store');
            Route::patch('/lab-orders/{order}/status', [\App\Http\Controllers\LabOrderController::class, 'updateStatus'])->name('clinic.lab-orders.status');
        });

        Route::resource('waitlist', \App\Http\Controllers\WaitlistController::class)->names([
            'index' => 'clinic.waitlist.index',
            'create' => 'clinic.waitlist.create',
            'store' => 'clinic.waitlist.store',
            'show' => 'clinic.waitlist.show',
            'edit' => 'clinic.waitlist.edit',
            'update' => 'clinic.waitlist.update',
            'destroy' => 'clinic.waitlist.destroy'
        ]);
        Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'clinicIndex'])->name('clinic.notifications.index');
        Route::post('/notifications/send-reminders', [\App\Http\Controllers\NotificationController::class, 'sendAppointmentReminders'])->name('clinic.notifications.send-reminders');
        
        // Enhanced Receptionist Features
        Route::post('/appointments/{appointment}/check-in', [\App\Http\Controllers\ReceptionistController::class, 'checkIn'])->name('clinic.appointments.check-in');
        Route::post('/appointments/{appointment}/no-show', [\App\Http\Controllers\ReceptionistController::class, 'noShow'])->name('clinic.appointments.no-show');
        Route::get('/emergency-appointment', [\App\Http\Controllers\ReceptionistController::class, 'createEmergencyAppointment'])->name('clinic.emergency-appointment');
        Route::post('/emergency-appointment', [\App\Http\Controllers\ReceptionistController::class, 'emergencyAppointment']);
        Route::get('/today-schedule', [\App\Http\Controllers\ReceptionistController::class, 'todaySchedule'])->name('clinic.today-schedule');
        Route::get('/patient-search', [\App\Http\Controllers\ReceptionistController::class, 'quickSearch'])->name('clinic.patient-search');
        Route::get('/print-schedule', [\App\Http\Controllers\ReceptionistController::class, 'printSchedule'])->name('clinic.print-schedule');

// Financial data - read access for dentists, full access for accountants/admins
    Route::middleware(['role:dentist|accountant|clinic_admin'])->group(function () {
        Route::get('/invoices', [\App\Http\Controllers\InvoiceController::class, 'index'])->name('clinic.invoices.index');
        Route::get('/invoices/create', [\App\Http\Controllers\InvoiceController::class, 'create'])->name('clinic.invoices.create');
        Route::get('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'show'])->name('clinic.invoices.show');
        Route::get('/reports', [\App\Http\Controllers\ReportsController::class, 'index'])->name('clinic.reports.index');
    });
    
    // Accountant routes - Financial Management (write access)
    Route::middleware(['role:accountant|clinic_admin'])->group(function () {
        Route::post('/invoices', [\App\Http\Controllers\InvoiceController::class, 'store'])->name('clinic.invoices.store');
        Route::get('/invoices/{invoice}/edit', [\App\Http\Controllers\InvoiceController::class, 'edit'])->name('clinic.invoices.edit');
        Route::put('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'update'])->name('clinic.invoices.update');
        Route::delete('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'destroy'])->name('clinic.invoices.destroy');
        Route::patch('/invoices/{invoice}/mark-paid', [\App\Http\Controllers\InvoiceController::class, 'markPaid'])->name('clinic.invoices.mark-paid');
        Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'dashboard'])->name('clinic.analytics.dashboard');
        Route::get('/analytics/pro', [\App\Http\Controllers\AnalyticsController::class, 'pro'])->name('clinic.analytics.pro');
        // Enhanced Accountant Features
        Route::get('/payment-tracking', [\App\Http\Controllers\AccountantController::class, 'paymentTracking'])->name('clinic.payment-tracking');
        Route::get('/expenses', [\App\Http\Controllers\AccountantController::class, 'expenseTracking'])->name('clinic.expenses');
        Route::get('/profit-loss', [\App\Http\Controllers\AccountantController::class, 'profitLossReport'])->name('clinic.profit-loss');
        Route::get('/patient-balances', [\App\Http\Controllers\AccountantController::class, 'patientBalances'])->name('clinic.patient-balances');
        Route::get('/tax-report', [\App\Http\Controllers\AccountantController::class, 'taxReport'])->name('clinic.tax-report');
        Route::get('/branch-comparison', [\App\Http\Controllers\AccountantController::class, 'branchComparison'])->name('clinic.branch-comparison');
        Route::get('/service-profitability', [\App\Http\Controllers\AccountantController::class, 'serviceProfitability'])->name('clinic.service-profitability');
        
        // Credit Notes
        Route::get('/credit-notes', [\App\Http\Controllers\AccountantController::class, 'creditNotes'])->name('clinic.credit-notes.index');
        Route::post('/credit-notes', [\App\Http\Controllers\AccountantController::class, 'storeCreditNote'])->name('clinic.credit-notes.store');
        
        // Vendors
        Route::get('/vendors', [\App\Http\Controllers\AccountantController::class, 'vendors'])->name('clinic.vendors.index');
        Route::post('/vendors', [\App\Http\Controllers\AccountantController::class, 'storeVendor'])->name('clinic.vendors.store');
        
        // Journal & Ledger Management
        Route::get('/journal-entries', [\App\Http\Controllers\AccountantController::class, 'journalEntries'])->name('clinic.journal-entries');
        Route::get('/journal-entries/create', [\App\Http\Controllers\AccountantController::class, 'createJournalEntry'])->name('clinic.journal-entries.create');
        Route::post('/journal-entries', [\App\Http\Controllers\AccountantController::class, 'storeJournalEntry'])->name('clinic.journal-entries.store');
        Route::get('/ledger', [\App\Http\Controllers\AccountantController::class, 'ledger'])->name('clinic.ledger');
        Route::get('/chart-of-accounts', [\App\Http\Controllers\AccountantController::class, 'chartOfAccounts'])->name('clinic.chart-of-accounts');
        Route::post('/invoices/{invoice}/send-reminder', [\App\Http\Controllers\AccountantController::class, 'sendPaymentReminder'])->name('clinic.invoices.send-reminder');
        Route::post('/invoices/bulk-actions', [\App\Http\Controllers\AccountantController::class, 'bulkInvoiceActions'])->name('clinic.invoices.bulk-actions');
        Route::post('/payments/{payment}/confirm-cash', [\App\Http\Controllers\AccountantController::class, 'confirmCashPayment'])->name('clinic.payments.confirm-cash');
    });
    
    // Treatment plans - accessible by dentists and admins
    Route::middleware('role:dentist|clinic_admin')->group(function () {
        Route::resource('treatment-plans', \App\Http\Controllers\TreatmentPlanController::class)->names([
            'index' => 'clinic.treatment-plans.index',
            'create' => 'clinic.treatment-plans.create',
            'store' => 'clinic.treatment-plans.store',
            'show' => 'clinic.treatment-plans.show',
            'edit' => 'clinic.treatment-plans.edit',
            'update' => 'clinic.treatment-plans.update',
            'destroy' => 'clinic.treatment-plans.destroy'
        ]);
    });
    
    // Admin only routes
    Route::middleware('role:clinic_admin')->group(function () {
        Route::resource('staff', \App\Http\Controllers\StaffController::class)->names([
            'index' => 'clinic.staff.index',
            'create' => 'clinic.staff.create',
            'store' => 'clinic.staff.store',
            'show' => 'clinic.staff.show',
            'edit' => 'clinic.staff.edit',
            'update' => 'clinic.staff.update',
            'destroy' => 'clinic.staff.destroy'
        ]);
        Route::resource('branches', \App\Http\Controllers\BranchController::class)->names([
            'index' => 'clinic.branches.index',
            'create' => 'clinic.branches.create',
            'store' => 'clinic.branches.store',
            'show' => 'clinic.branches.show',
            'edit' => 'clinic.branches.edit',
            'update' => 'clinic.branches.update',
            'destroy' => 'clinic.branches.destroy'
        ]);
        Route::resource('recurring-appointments', \App\Http\Controllers\RecurringAppointmentController::class)->names([
            'index' => 'clinic.recurring-appointments.index',
            'create' => 'clinic.recurring-appointments.create',
            'store' => 'clinic.recurring-appointments.store',
            'show' => 'clinic.recurring-appointments.show',
            'edit' => 'clinic.recurring-appointments.edit',
            'update' => 'clinic.recurring-appointments.update',
            'destroy' => 'clinic.recurring-appointments.destroy'
        ]);
        Route::get('/settings', [\App\Http\Controllers\SystemSettingsController::class, 'index'])->name('clinic.settings.index');
        Route::put('/settings', [\App\Http\Controllers\SystemSettingsController::class, 'updateGeneral'])->name('clinic.settings.update');
        Route::get('/settings/business-hours', [\App\Http\Controllers\SystemSettingsController::class, 'index'])->name('clinic.settings.business-hours');
        Route::put('/settings/business-hours', [\App\Http\Controllers\SystemSettingsController::class, 'updateBusinessHours'])->name('clinic.system-settings.update-business-hours');
        Route::get('/subscription', [\App\Http\Controllers\SubscriptionController::class, 'current'])->name('subscriptions.current');

        // System Settings - Master Control for Landing Page
        Route::get('/system-settings', [\App\Http\Controllers\SystemSettingsController::class, 'index'])->name('clinic.system-settings.index');
        Route::put('/system-settings/general', [\App\Http\Controllers\SystemSettingsController::class, 'updateGeneral'])->name('clinic.system-settings.update-general');
        Route::put('/system-settings/branding', [\App\Http\Controllers\SystemSettingsController::class, 'updateBranding'])->name('clinic.system-settings.update-branding');
        Route::put('/system-settings/landing-page', [\App\Http\Controllers\SystemSettingsController::class, 'updateLandingPage'])->name('clinic.system-settings.update-landing-page');
        Route::delete('/system-settings/landing-page/image', [\App\Http\Controllers\SystemSettingsController::class, 'deleteLandingImage'])->name('clinic.system-settings.delete-landing-image');
        Route::put('/system-settings/navigation', [\App\Http\Controllers\SystemSettingsController::class, 'updateNavigation'])->name('clinic.system-settings.update-navigation');
        Route::put('/system-settings/seo', [\App\Http\Controllers\SystemSettingsController::class, 'updateSeo'])->name('clinic.system-settings.update-seo');
        Route::put('/system-settings/features', [\App\Http\Controllers\SystemSettingsController::class, 'updateFeatures'])->name('clinic.system-settings.update-features');
        Route::get('/system-settings/preview', [\App\Http\Controllers\SystemSettingsController::class, 'preview'])->name('clinic.system-settings.preview');
        Route::post('/system-settings/toggle-status', [\App\Http\Controllers\SystemSettingsController::class, 'toggleStatus'])->name('clinic.system-settings.toggle-status');
        Route::get('/landing-page-manager', [\App\Http\Controllers\ClinicAdminController::class, 'landingPage'])->name('clinic.landing-page-manager');
        Route::put('/landing-page-manager', [\App\Http\Controllers\ClinicAdminController::class, 'updateLandingPage'])->name('clinic.landing-page-manager.update');
        
        // Clinic CRM Routes
        Route::prefix('crm')->name('clinic.crm.')->group(function () {
            Route::get('/leads', [\App\Http\Controllers\ClinicCrmController::class, 'index'])->name('leads.index');
            Route::get('/leads/create', [\App\Http\Controllers\ClinicCrmController::class, 'create'])->name('leads.create');
            Route::post('/leads', [\App\Http\Controllers\ClinicCrmController::class, 'store'])->name('leads.store');
        });

        // Payment Plans
        Route::resource('payment-plans', \App\Http\Controllers\PaymentPlanController::class)->names([
            'index' => 'clinic.payment-plans.index',
            'create' => 'clinic.payment-plans.create',
            'store' => 'clinic.payment-plans.store',
            'show' => 'clinic.payment-plans.show',
            'edit' => 'clinic.payment-plans.edit',
            'update' => 'clinic.payment-plans.update',
            'destroy' => 'clinic.payment-plans.destroy'
        ]);

        // Expenses
        Route::resource('expenses', \App\Http\Controllers\ExpenseController::class)->names([
            'index' => 'clinic.expenses.index',
            'create' => 'clinic.expenses.create',
            'store' => 'clinic.expenses.store',
            'show' => 'clinic.expenses.show',
            'edit' => 'clinic.expenses.edit',
            'update' => 'clinic.expenses.update',
            'destroy' => 'clinic.expenses.destroy'
        ]);

        // Inventory
        Route::resource('inventory', \App\Http\Controllers\InventoryController::class)->names([
            'index' => 'clinic.inventory.index',
            'create' => 'clinic.inventory.create',
            'store' => 'clinic.inventory.store',
            'show' => 'clinic.inventory.show',
            'edit' => 'clinic.inventory.edit',
            'update' => 'clinic.inventory.update',
            'destroy' => 'clinic.inventory.destroy'
        ]);

        // Suppliers
        Route::resource('suppliers', \App\Http\Controllers\SupplierController::class)->names([
            'index' => 'clinic.suppliers.index',
            'create' => 'clinic.suppliers.create',
            'store' => 'clinic.suppliers.store',
            'show' => 'clinic.suppliers.show',
            'edit' => 'clinic.suppliers.edit',
            'update' => 'clinic.suppliers.update',
            'destroy' => 'clinic.suppliers.destroy'
        ]);

        // Purchase Orders
        Route::resource('purchase-orders', \App\Http\Controllers\PurchaseOrderController::class)->names([
            'index' => 'clinic.purchase-orders.index',
            'create' => 'clinic.purchase-orders.create',
            'store' => 'clinic.purchase-orders.store',
            'show' => 'clinic.purchase-orders.show',
            'edit' => 'clinic.purchase-orders.edit',
            'update' => 'clinic.purchase-orders.update',
            'destroy' => 'clinic.purchase-orders.destroy'
        ]);
        Route::patch('purchase-orders/{purchase_order}/status', [\App\Http\Controllers\PurchaseOrderController::class, 'updateStatus'])->name('clinic.purchase-orders.update-status');

        // Equipment
        Route::resource('equipment', \App\Http\Controllers\EquipmentController::class)->names([
            'index' => 'clinic.equipment.index',
            'create' => 'clinic.equipment.create',
            'store' => 'clinic.equipment.store',
            'show' => 'clinic.equipment.show',
            'edit' => 'clinic.equipment.edit',
            'update' => 'clinic.equipment.update',
            'destroy' => 'clinic.equipment.destroy'
        ]);
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
    Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    // Notification mark as read refactored to specific controller if needed, but for now it's fine
    Route::post('/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/unread-count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
});

// Patient Portal routes
Route::prefix('patient')->group(function () {
    Route::get('/login', [\App\Http\Controllers\PatientPortalController::class, 'login'])->name('patient.login');
    Route::post('/login', [\App\Http\Controllers\PatientPortalController::class, 'login'])->middleware('throttle:20,1');

    Route::middleware(['auth:patient'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\PatientPortalController::class, 'dashboard'])->name('patient.dashboard');
        Route::get('/appointments', [\App\Http\Controllers\PatientPortalController::class, 'appointments'])->name('patient.appointments');
        Route::get('/recurring-appointments', [\App\Http\Controllers\PatientPortalController::class, 'recurringAppointments'])->name('patient.recurring-appointments');
        Route::get('/payment-plans', [\App\Http\Controllers\PatientPortalController::class, 'paymentPlans'])->name('patient.payment-plans');
        Route::get('/payment-plans/{paymentPlan}', [\App\Http\Controllers\PatientPortalController::class, 'paymentPlan'])->name('patient.payment-plans.show');
        Route::get('/consents', [\App\Http\Controllers\PatientPortalController::class, 'consents'])->name('patient.consents');
        Route::get('/consents/{consent}/sign', [\App\Http\Controllers\PatientPortalController::class, 'signConsent'])->name('patient.consents.sign');
        Route::post('/consents/{consent}/sign', [\App\Http\Controllers\PatientPortalController::class, 'signConsent']);
        Route::get('/invoices', [\App\Http\Controllers\PatientPortalController::class, 'invoices'])->name('patient.invoices');
        Route::get('/profile', [\App\Http\Controllers\PatientPortalController::class, 'profile'])->name('patient.profile');
        Route::put('/profile', [\App\Http\Controllers\PatientPortalController::class, 'updateProfile'])->name('patient.profile.update');
        Route::post('/profile/photo', [\App\Http\Controllers\PatientPortalController::class, 'updatePhoto'])->name('patient.profile.photo');

        // Payment Form & Processing (Authenticated)
        Route::get('/payment/{invoice}', [\App\Http\Controllers\PaymentController::class, 'show'])->name('patient.payment');
        Route::post('/payment/{invoice}', [\App\Http\Controllers\PaymentController::class, 'process'])->name('patient.payment.process');
        
        Route::post('/logout', [\App\Http\Controllers\PatientPortalController::class, 'logout'])->name('patient.logout');
    });

    // Payment Gateway Callbacks (Publicly accessible but protected by internal signatures)
    Route::get('/payment/esewa/callback', [\App\Http\Controllers\PaymentController::class, 'esewaCallback'])->name('patient.payment.esewa.callback');
    Route::get('/payment/esewa/failed', [\App\Http\Controllers\PaymentController::class, 'esewaFailed'])->name('patient.payment.esewa.failed');
    Route::get('/payment/khalti/callback', [\App\Http\Controllers\PaymentController::class, 'khaltiCallback'])->name('patient.payment.khalti.callback');
});

// Public clinic pages
Route::get('/clinic/{slug}', [ClinicController::class, 'publicLanding'])->name('clinic.landing');

// Public appointment booking
Route::get('/clinic/{clinic:slug}/book', [AppointmentController::class, 'publicBook'])->name('clinic.book');
Route::post('/clinic/{clinic:slug}/book', [AppointmentController::class, 'storePublicBook'])->name('clinic.book.store')->middleware('throttle:3,60');
