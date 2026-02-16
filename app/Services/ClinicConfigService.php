<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class ClinicConfigService
{
    public static function hasModule($module)
    {
        $clinic = Auth::user()?->clinic;
        return $clinic?->hasModule($module) ?? false;
    }

    public static function hasFeature($feature)
    {
        $clinic = Auth::user()?->clinic;
        return $clinic?->hasFeature($feature) ?? false;
    }

    public static function hasRole($role)
    {
        $clinic = Auth::user()?->clinic;
        return $clinic?->hasRole($role) ?? false;
    }

    public static function getSidebarItems()
    {
        $items = [];
        
        if (self::hasModule('appointments')) {
            $items[] = ['name' => 'Appointments', 'route' => 'clinic.appointments.index', 'icon' => 'calendar'];
            $items[] = ['name' => 'Recurring Visits', 'route' => 'clinic.recurring-appointments.index', 'icon' => 'clock'];
            $items[] = ['name' => 'Waitlist', 'route' => 'clinic.waitlist.index', 'icon' => 'user-clock'];
        }
        
        if (self::hasModule('patients')) {
            $items[] = ['name' => 'Patients', 'route' => 'clinic.patients.index', 'icon' => 'users'];
        }
        
        if (self::hasModule('invoicing')) {
            $items[] = ['name' => 'Invoices', 'route' => 'clinic.invoices.index', 'icon' => 'receipt'];
            $items[] = ['name' => 'Payment Plans', 'route' => 'clinic.payment-plans.index', 'icon' => 'credit-card'];
        }

        $items[] = ['name' => 'Expenses', 'route' => 'clinic.expenses.index', 'icon' => 'money-bill-wave'];
        $items[] = ['name' => 'Inventory', 'route' => 'clinic.inventory.index', 'icon' => 'boxes'];
        
        if (self::hasFeature('has_analytics')) {
            $items[] = ['name' => 'Analytics', 'route' => 'clinic.analytics.dashboard', 'icon' => 'chart-bar'];
        }
        
        if (self::hasFeature('has_accounting')) {
            $items[] = ['name' => 'Accounting', 'route' => 'clinic.journal-entries', 'icon' => 'calculator'];
        }
        
        if (self::hasFeature('has_landing_page') && Auth::user()?->hasRole('clinic_admin')) {
            $items[] = ['name' => 'Landing Page', 'route' => 'clinic.landing-page-manager', 'icon' => 'globe'];
        }
        
        return $items;
    }
}