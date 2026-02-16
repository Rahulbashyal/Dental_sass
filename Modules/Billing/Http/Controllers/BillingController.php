<?php

namespace Modules\Billing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function index()
    {
        $clinic = tenant()->clinic;
        $activeSubscription = $clinic->subscriptions()
            ->with('plan')
            ->where('status', 'active')
            ->orWhere('status', 'trial')
            ->latest()
            ->first();

        return view('billing::index', compact('activeSubscription', 'clinic'));
    }

    public function upgrade()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        $clinic = tenant()->clinic;
        $currentPlanId = $clinic->subscriptions()
            ->where('status', 'active')
            ->value('subscription_plan_id');

        return view('billing::upgrade', compact('plans', 'currentPlanId'));
    }

    public function subscribe(Request $request, SubscriptionPlan $plan)
    {
        $clinic = tenant()->clinic;

        // Cancel existing active subscriptions
        $clinic->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

        // Create new subscription (In reality, this would involve Stripe/Payment gateway)
        $subscription = Subscription::create([
            'clinic_id' => $clinic->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::now()->addMonth(),
            'amount' => $plan->price,
        ]);

        return redirect()->route('billing.index')
            ->with('status', "Successfully subscribed to the {$plan->name} plan.");
    }

    public function invoices()
    {
        $clinic = tenant()->clinic;
        // This is assuming a central billing system or tenant-level invoice logging
        $invoices = []; // Placeholder for actual subscription invoices
        return view('billing::invoices.index', compact('invoices'));
    }
}
