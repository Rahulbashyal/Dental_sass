<?php

namespace App\Services;

use App\Models\{Patient, Appointment, Invoice, Clinic};
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getRealtimeAnalytics($clinicId = null): array
    {
        $query = $clinicId ? ['clinic_id' => $clinicId] : [];
        
        return [
            'patients_today' => Patient::where($query)->whereDate('created_at', today())->count(),
            'appointments_today' => Appointment::where($query)->whereDate('appointment_date', today())->count(),
            'revenue_today' => Invoice::where($query)->whereDate('created_at', today())->sum('total_amount'),
            'active_users' => DB::table('sessions')->count(),
            'system_load' => sys_getloadavg()[0]
        ];
    }

    public function getPredictiveAnalytics($clinicId): array
    {
        $monthlyPatients = Patient::where('clinic_id', $clinicId)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $trend = $this->calculateTrend($monthlyPatients);
        
        return [
            'patient_growth_trend' => $trend,
            'predicted_next_month' => $this->predictNextMonth($monthlyPatients),
            'revenue_forecast' => $this->forecastRevenue($clinicId),
            'capacity_utilization' => $this->calculateCapacityUtilization($clinicId)
        ];
    }

    private function calculateTrend($data): string
    {
        if (count($data) < 2) return 'insufficient_data';
        
        $values = array_values($data);
        $recent = array_slice($values, -3);
        $avg = array_sum($recent) / count($recent);
        $previous = array_slice($values, -6, 3);
        $prevAvg = array_sum($previous) / count($previous);
        
        return $avg > $prevAvg ? 'increasing' : 'decreasing';
    }

    private function predictNextMonth($data): int
    {
        if (empty($data)) return 0;
        
        $values = array_values($data);
        $recent = array_slice($values, -3);
        return (int) (array_sum($recent) / count($recent));
    }

    private function forecastRevenue($clinicId): float
    {
        return Invoice::where('clinic_id', $clinicId)
            ->whereMonth('created_at', date('m'))
            ->avg('total_amount') * 30;
    }

    private function calculateCapacityUtilization($clinicId): float
    {
        $totalSlots = 8 * 5 * 4; // 8 hours, 5 days, 4 weeks
        $bookedSlots = Appointment::where('clinic_id', $clinicId)
            ->whereMonth('appointment_date', date('m'))
            ->count();
            
        return ($bookedSlots / $totalSlots) * 100;
    }
}