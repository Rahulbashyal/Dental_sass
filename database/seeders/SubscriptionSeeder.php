<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\Clinic;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        // Create subscription plans
        $starter = SubscriptionPlan::create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => 'Perfect for small dental practices',
            'price' => 29.99,
            'billing_cycle' => 'monthly',
            'features' => [
                'Up to 3 staff members',
                'Up to 500 patients',
                'Basic appointment scheduling',
                'Patient records management',
                'Email support'
            ],
            'max_users' => 3,
            'max_patients' => 500
        ]);

        $professional = SubscriptionPlan::create([
            'name' => 'Professional',
            'slug' => 'professional',
            'description' => 'For growing dental practices',
            'price' => 59.99,
            'billing_cycle' => 'monthly',
            'features' => [
                'Up to 10 staff members',
                'Up to 2000 patients',
                'Advanced scheduling',
                'Treatment plans',
                'Billing & invoicing',
                'Reports & analytics',
                'Priority support'
            ],
            'max_users' => 10,
            'max_patients' => 2000
        ]);

        $enterprise = SubscriptionPlan::create([
            'name' => 'Enterprise',
            'slug' => 'enterprise',
            'description' => 'For large clinics and chains',
            'price' => 99.99,
            'billing_cycle' => 'monthly',
            'features' => [
                'Unlimited staff members',
                'Unlimited patients',
                'Multi-location support',
                'Custom branding',
                'API access',
                'Advanced reporting',
                'Dedicated support'
            ],
            'max_users' => -1, // unlimited
            'max_patients' => -1 // unlimited
        ]);

        // Assign starter plan to existing clinic
        $clinic = Clinic::first();
        if ($clinic) {
            Subscription::create([
                'clinic_id' => $clinic->id,
                'subscription_plan_id' => $starter->id,
                'status' => 'trial',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'trial_ends_at' => now()->addDays(14),
                'amount' => 0
            ]);
        }
    }
}