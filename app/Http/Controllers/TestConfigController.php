<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ClinicConfigService;

class TestConfigController extends Controller
{
    public function testConfig()
    {
        $user = Auth::user();
        $clinic = $user->clinic;
        
        if (!$clinic) {
            return response()->json(['error' => 'No clinic associated']);
        }
        
        return response()->json([
            'clinic_name' => $clinic->name,
            'enabled_modules' => $clinic->enabled_modules,
            'enabled_roles' => $clinic->enabled_roles,
            'features' => [
                'has_landing_page' => $clinic->has_landing_page,
                'has_crm' => $clinic->has_crm,
                'has_patient_portal' => $clinic->has_patient_portal,
                'has_email_system' => $clinic->has_email_system,
                'has_notifications' => $clinic->has_notifications,
                'has_analytics' => $clinic->has_analytics,
                'has_accounting' => $clinic->has_accounting,
            ],
            'subscription' => [
                'tier' => $clinic->subscription_tier,
                'max_users' => $clinic->max_users,
                'max_patients' => $clinic->max_patients,
                'max_appointments_per_month' => $clinic->max_appointments_per_month,
            ],
            'sidebar_items' => ClinicConfigService::getSidebarItems(),
            'user_can_access' => [
                'appointments' => ClinicConfigService::hasModule('appointments'),
                'patients' => ClinicConfigService::hasModule('patients'),
                'invoicing' => ClinicConfigService::hasModule('invoicing'),
                'analytics' => ClinicConfigService::hasFeature('has_analytics'),
            ]
        ]);
    }
}