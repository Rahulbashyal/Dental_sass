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
        }
        
        if (self::hasModule('patients')) {
            $items[] = ['name' => 'Patients', 'route' => 'patients.index', 'icon' => 'users'];
        }
        
        if (self::hasModule('invoicing')) {
            $items[] = ['name' => 'Invoices', 'route' => 'invoices.index', 'icon' => 'receipt'];
        }
        
        if (self::hasFeature('has_analytics')) {
            $items[] = ['name' => 'Analytics', 'route' => 'analytics.dashboard', 'icon' => 'chart-bar'];
        }
        
        if (self::hasFeature('has_accounting')) {
            $items[] = ['name' => 'Accounting', 'route' => 'journal-entries', 'icon' => 'calculator'];
        }
        
        if (self::hasFeature('has_landing_page')) {
            $items[] = ['name' => 'Landing Page', 'route' => 'landing-page-manager', 'icon' => 'globe'];
        }
        
        return $items;
    }
}