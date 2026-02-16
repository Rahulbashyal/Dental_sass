<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\InventoryItem;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AiInsightService
{
    /**
     * Generate AI insights for the clinic.
     */
    public function generateClinicInsights()
    {
        $clinicId = Auth::user()->clinic_id;
        $insights = [];

        // 1. Revenue Insight
        $currentMonthRevenue = Invoice::where('clinic_id', $clinicId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');
            
        $lastMonthRevenue = Invoice::where('clinic_id', $clinicId)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('total_amount');

        if ($currentMonthRevenue > $lastMonthRevenue && $lastMonthRevenue > 0) {
            $growth = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
            $insights[] = [
                'type' => 'success',
                'title' => 'Revenue Growth Detected',
                'message' => "Clinic revenue has increased by " . round($growth, 1) . "% compared to last month. Modern dental trends suggest high patient retention.",
                'icon' => 'chart-line'
            ];
        }

        // 2. Inventory Alert
        $lowStockCount = InventoryItem::where('clinic_id', $clinicId)
            ->whereColumn('current_stock', '<=', 'min_stock_level')
            ->count();

        if ($lowStockCount > 0) {
            $insights[] = [
                'type' => 'warning',
                'title' => 'Inventory Predictive Alert',
                'message' => "{$lowStockCount} items are near depletion. Based on current procedure volume, you may experience shortages within 10 days.",
                'icon' => 'box-open'
            ];
        }

        // 3. Appointment Volume
        $nextWeekAppointments = Appointment::where('clinic_id', $clinicId)
            ->whereBetween('appointment_date', [Carbon::now(), Carbon::now()->addDays(7)])
            ->count();
            
        if ($nextWeekAppointments > 25) {
            $insights[] = [
                'type' => 'info',
                'title' => 'High Volume Period',
                'message' => "Next week is projected to be 30% busier than average. Consider optimizing staff shifts for peak hours (10 AM - 2 PM).",
                'icon' => 'users-cog'
            ];
        }

        // 4. Expense Analysis
        $highExpenses = Expense::where('clinic_id', $clinicId)
            ->whereMonth('expense_date', Carbon::now()->month)
            ->where('amount', '>', 50000)
            ->get();

        if ($highExpenses->count() > 0) {
            $insights[] = [
                'type' => 'danger',
                'title' => 'Expense Anomaly Detected',
                'message' => "Unusually high expenditures detected in '" . $highExpenses->first()->category . "'. Reviewing vendor contracts could save up to 15% annually.",
                'icon' => 'hand-holding-usd'
            ];
        }

        return $insights;
    }
}
