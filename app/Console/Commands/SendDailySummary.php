<?php

namespace App\Console\Commands;

use App\Models\Clinic;
use App\Notifications\DailyFinancialSummary;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendDailySummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily financial and operational summary to clinic admins';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating daily summaries...');
        
        $today = Carbon::today();
        $clinics = Clinic::where('is_active', true)->get();

        foreach ($clinics as $clinic) {
            // Check if clinic has accounting/reporting enabled
            if (!$clinic->hasFeature('has_accounting') && !$clinic->hasFeature('has_analytics')) {
                continue;
            }

            $this->info("Processing {$clinic->name}...");

            // Get summary data
            $data = [
                'date' => $today->format('F j, Y'),
                'revenue' => $clinic->payments()
                    ->whereDate('created_at', $today)
                    ->where('status', 'completed')
                    ->sum('amount'),
                'new_appointments' => $clinic->appointments()
                    ->whereDate('created_at', $today)
                    ->count(),
                'completed_appointments' => $clinic->appointments()
                    ->whereDate('appointment_date', $today)
                    ->where('status', 'completed')
                    ->count(),
                'new_patients' => $clinic->patients()
                    ->whereDate('created_at', $today)
                    ->count(),
                'total_leads' => $clinic->hasFeature('has_crm') 
                    ? \App\Models\Lead::where('clinic_id', $clinic->id)->whereDate('created_at', $today)->count()
                    : 0,
            ];

            // Notify clinic admins
            Notification::send($clinic->admins, new DailyFinancialSummary($data));
        }

        $this->info('Daily summaries sent successfully.');
    }
}
