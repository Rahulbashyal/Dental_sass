<?php

namespace Modules\Financials\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentInstallment;
use Carbon\Carbon;

class CheckOverdueInstallments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'financials:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for pending installments that are past their due date and marks them as overdue.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        $overdueInstallments = PaymentInstallment::with(['paymentPlan.patient'])
            ->where('status', 'pending')
            ->where('due_date', '<', $today)
            ->get();

        foreach ($overdueInstallments as $installment) {
            $installment->update(['status' => 'overdue']);
            
            // Send notification to the patient
            $patient = $installment->paymentPlan->patient;
            if ($patient) {
                $patient->notify(new \App\Notifications\InstallmentReminder($installment));
            }
        }

        $this->info("Successfully processed " . $overdueInstallments->count() . " overdue installments.");
    }
}
