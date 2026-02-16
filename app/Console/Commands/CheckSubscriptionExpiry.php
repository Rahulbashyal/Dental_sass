<?php

namespace App\Console\Commands;

use App\Models\Clinic;
use App\Notifications\SubscriptionExpiryAlert;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckSubscriptionExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-subscription-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for clinics whose subscription is about to expire or has expired and notify them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking subscription expiries...');

        // Check for clinics expiring in 7 days, 3 days, 1 day, and already expired
        $notificationIntervals = [7, 3, 1, 0];

        foreach ($notificationIntervals as $days) {
            $targetDate = Carbon::now()->addDays($days)->toDateString();
            
            $clinics = Clinic::whereDate('subscription_expires_at', $targetDate)
                ->where('is_active', true)
                ->get();

            foreach ($clinics as $clinic) {
                $this->info("Notifying {$clinic->name} about expiry in {$days} days.");
                
                // Notify clinic admins
                Notification::send($clinic->admins, new SubscriptionExpiryAlert($clinic, $days));
            }
        }

        // Also check for newly expired clinics (expired today or yesterday)
        $expiredClinics = Clinic::where('subscription_expires_at', '<', Carbon::now())
            ->where('is_active', true)
            ->whereDate('subscription_expires_at', '>=', Carbon::now()->subDay())
            ->get();

        foreach ($expiredClinics as $clinic) {
            $this->warn("Notifying {$clinic->name} about EXPIRED subscription.");
            Notification::send($clinic->admins, new SubscriptionExpiryAlert($clinic, 0));
        }

        $this->info('Subscription check completed.');
    }
}
