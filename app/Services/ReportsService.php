<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Clinic;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsService
{
    /**
     * Get Executive Intelligence Dashboard Data
     */
    public function getExecutiveStats($clinicId, $startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: now()->startOfMonth();
        $endDate = $endDate ?: now()->endOfMonth();

        return [
            'chair_utilization' => $this->calculateChairUtilization($clinicId, $startDate, $endDate),
            'revenue_performance' => $this->calculateRevenuePerformance($clinicId, $startDate, $endDate),
            'doctor_efficiency' => $this->calculateDoctorEfficiency($clinicId, $startDate, $endDate),
            'leakage' => $this->calculateRevenueLeakage($clinicId, $startDate, $endDate)
        ];
    }

    private function calculateChairUtilization($clinicId, $start, $end)
    {
        $totalSlots = 100; // Mock total slots for now
        $occupiedSlots = Appointment::where('clinic_id', $clinicId)
            ->whereBetween('appointment_date', [$start, $end])
            ->whereIn('status', ['confirmed', 'completed'])
            ->count();
        
        return ($occupiedSlots / $totalSlots) * 100;
    }

    private function calculateRevenuePerformance($clinicId, $start, $end)
    {
        return Invoice::where('clinic_id', $clinicId)
            ->whereBetween('created_at', [$start, $end])
            ->sum('total_amount');
    }

    private function calculateDoctorEfficiency($clinicId, $start, $end)
    {
        return DB::table('appointments')
            ->join('users', 'appointments.dentist_id', '=', 'users.id')
            ->select('users.name', DB::raw('count(*) as appointment_count'))
            ->where('appointments.clinic_id', $clinicId)
            ->whereBetween('appointments.appointment_date', [$start, $end])
            ->groupBy('users.id', 'users.name')
            ->get();
    }

    private function calculateRevenueLeakage($clinicId, $start, $end)
    {
        // Potential revenue from cancelled/no-shows (Average of 5000 NPR per appt)
        $lostAppts = Appointment::where('clinic_id', $clinicId)
            ->whereBetween('appointment_date', [$start, $end])
            ->whereIn('status', ['cancelled', 'no_show'])
            ->count();
            
        return $lostAppts * 5000;
    }
}
