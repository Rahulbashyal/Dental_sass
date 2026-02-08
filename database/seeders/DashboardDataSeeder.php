<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clinic;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;

class DashboardDataSeeder extends Seeder
{
    public function run()
    {
        // Create subscription plans if they don't exist
        $plans = [
            ['name' => 'Basic', 'slug' => 'basic', 'price' => 3500, 'billing_cycle' => 'monthly'],
            ['name' => 'Professional', 'slug' => 'professional', 'price' => 7000, 'billing_cycle' => 'monthly'],
            ['name' => 'Enterprise', 'slug' => 'enterprise', 'price' => 12000, 'billing_cycle' => 'monthly'],
        ];

        foreach ($plans as $planData) {
            SubscriptionPlan::firstOrCreate(
                ['slug' => $planData['slug']],
                [
                    'name' => $planData['name'],
                    'description' => $planData['name'] . ' plan for dental clinics',
                    'price' => $planData['price'],
                    'billing_cycle' => $planData['billing_cycle'],
                    'features' => json_encode(['feature1', 'feature2']),
                    'max_users' => $planData['slug'] === 'basic' ? 5 : ($planData['slug'] === 'professional' ? 15 : 50),
                    'max_patients' => $planData['slug'] === 'basic' ? 500 : ($planData['slug'] === 'professional' ? 2000 : 10000),
                ]
            );
        }

        // Create sample clinics with historical dates
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $clinicsCount = random_int(2, 8);
            
            for ($j = 0; $j < $clinicsCount; $j++) {
                $clinic = Clinic::create([
                    'name' => 'Sample Clinic ' . ($i * 10 + $j),
                    'slug' => 'sample-clinic-' . ($i * 10 + $j),
                    'email' => 'clinic' . ($i * 10 + $j) . '@example.com',
                    'phone' => '98' . random_int(10000000, 99999999),
                    'address' => 'Sample Address ' . ($i * 10 + $j),
                    'city' => 'Kathmandu',
                    'created_at' => $date->copy()->addDays(random_int(1, 28)),
                    'updated_at' => now(),
                ]);

                // Create users for each clinic
                $usersCount = random_int(1, 3);
                for ($k = 0; $k < $usersCount; $k++) {
                    User::create([
                        'name' => 'User ' . ($i * 10 + $j) . '-' . $k,
                        'email' => 'user' . ($i * 10 + $j) . '-' . $k . '@example.com',
                        'password' => \Illuminate\Support\Facades\Hash::make('password'),
                        'clinic_id' => $clinic->id,
                        'created_at' => $date->copy()->addDays(random_int(1, 28)),
                        'updated_at' => now(),
                    ]);
                }

                // Create subscription for clinic
                $plan = SubscriptionPlan::inRandomOrder()->first();
                if ($plan) {
                    Subscription::create([
                        'clinic_id' => $clinic->id,
                        'subscription_plan_id' => $plan->id,
                        'status' => 'active',
                        'starts_at' => $date->copy()->addDays(random_int(1, 28)),
                        'ends_at' => $date->copy()->addYear(),
                        'amount' => $plan->price,
                        'created_at' => $date->copy()->addDays(random_int(1, 28)),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}