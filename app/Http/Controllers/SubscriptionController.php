<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function plans()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        
        $features = [
            'Patient Management System',
            'Appointment Scheduling',
            'Billing & Invoicing',
            'Treatment Plans',
            'Staff Management',
            'Reports & Analytics'
        ];
        
        $colors = [
            ['from-blue-500', 'to-indigo-600', 'bg-blue-50', 'text-blue-700', 'border-blue-200'],
            ['from-purple-500', 'to-pink-600', 'bg-purple-50', 'text-purple-700', 'border-purple-200'],
            ['from-green-500', 'to-emerald-600', 'bg-green-50', 'text-green-700', 'border-green-200']
        ];
        
        return view('subscriptions.plans', compact('plans', 'features', 'colors'));
    }

    public function current()
    {
        $user = Auth::user();
        
        if (!$user->clinic_id) {
            return redirect()->route('dashboard')->with('error', 'No clinic assigned.');
        }

        $subscription = Subscription::where('clinic_id', $user->clinic_id)
            ->with('plan')
            ->latest()
            ->first();

        // Mock data for usage statistics
        $staffCount = 5;
        $maxUsers = 10;
        $staffPercentage = ($staffCount / $maxUsers) * 100;
        
        $patientCount = 150;
        $maxPatients = 500;
        $patientPercentage = ($patientCount / $maxPatients) * 100;
        
        // Color scheme for the plan
        $color = ['from-blue-500', 'to-indigo-600', 'bg-blue-50', 'text-blue-700', 'border-blue-200'];

        // If subscription exists, ensure plan has features
        if ($subscription && $subscription->plan) {
            if (!$subscription->plan->features || empty($subscription->plan->features)) {
                $subscription->plan->features = [
                    'Patient Management System',
                    'Appointment Scheduling', 
                    'Billing & Invoicing',
                    'Treatment Plans',
                    'Staff Management',
                    'Reports & Analytics'
                ];
            }
        } elseif ($subscription && !$subscription->plan) {
            // If subscription exists but plan is missing, create a mock plan
            $subscription->plan = (object) [
                'name' => 'Basic Plan',
                'description' => 'Standard dental practice features',
                'price' => 2500,
                'slug' => 'basic',
                'features' => [
                    'Patient Management System',
                    'Appointment Scheduling', 
                    'Billing & Invoicing',
                    'Treatment Plans',
                    'Staff Management',
                    'Reports & Analytics'
                ]
            ];
        }

        return view('subscriptions.current', compact(
            'subscription', 
            'staffCount', 
            'maxUsers', 
            'staffPercentage',
            'patientCount', 
            'maxPatients', 
            'patientPercentage',
            'color'
        ));
    }

    public function upgrade($planId)
    {
        $user = Auth::user();
        
        if (!$user->clinic_id) {
            return redirect()->route('dashboard')->with('error', 'No clinic assigned.');
        }

        $plan = SubscriptionPlan::findOrFail($planId);
        
        $currentSubscription = Subscription::where('clinic_id', $user->clinic_id)->latest()->first();

        // Create new subscription
        Subscription::create([
            'clinic_id' => $user->clinic_id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'amount' => $plan->price
        ]);

        // Cancel current subscription
        if ($currentSubscription) {
            $currentSubscription->update(['status' => 'cancelled']);
        }

        return redirect()->route('subscriptions.current')
            ->with('success', 'Successfully upgraded to ' . $plan->name . ' plan!');
    }
}