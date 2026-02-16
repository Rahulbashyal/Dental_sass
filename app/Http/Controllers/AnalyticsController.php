<?php

namespace App\Http\Controllers;

use App\Models\{Appointment, Patient, User};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'clinic.access']);
    }

    public function dashboard()
    {
        $clinicId = Auth::user()->clinic_id;
        $now = Carbon::now();
        
        $analytics = [
            'appointments_today' => Appointment::where('clinic_id', $clinicId)
                ->whereDate('appointment_date', $now->toDateString())
                ->count(),
            
            'appointments_this_week' => Appointment::where('clinic_id', $clinicId)
                ->whereBetween('appointment_date', [$now->startOfWeek(), $now->endOfWeek()])
                ->count(),
                
            'appointments_this_month' => Appointment::where('clinic_id', $clinicId)
                ->whereMonth('appointment_date', $now->month)
                ->count(),
                
            'patient_growth' => Patient::where('clinic_id', $clinicId)
                ->whereMonth('created_at', $now->month)
                ->count(),
                
            'completion_rate' => $this->getCompletionRate($clinicId),
            
            'popular_treatments' => $this->getPopularTreatments($clinicId),
            
            'monthly_trends' => $this->getMonthlyTrends($clinicId)
        ];
        
        return view('analytics.dashboard', compact('analytics'));
    }

    public function pro(Request $request)
    {
        $clinicId = Auth::user()->clinic_id;
        
        $analytics = [
            'doctor_performance' => $this->getDoctorPerformance($clinicId),
            'monthly_growth' => $this->getMonthlyGrowth($clinicId),
            'revenue_per_patient' => $this->getRevenuePerPatient($clinicId),
            'repeat_visit_rate' => $this->getRepeatVisitRate($clinicId),
            'chair_utilization' => $this->getChairUtilizationRate($clinicId),
            'popular_treatments' => $this->getPopularTreatments($clinicId)
        ];
        
        return view('analytics.pro', compact('analytics'));
    }

    private function getDoctorPerformance($clinicId)
    {
        return User::role('dentist')
            ->where('clinic_id', $clinicId)
            ->withCount(['appointments' => function($query) {
                $query->where('status', 'completed');
            }])
            ->get()
            ->map(function($dentist) {
                return [
                    'name' => $dentist->name,
                    'appointments_count' => $dentist->appointments_count,
                    'revenue' => \App\Models\Invoice::whereHas('appointment', function($q) use ($dentist) {
                        $q->where('dentist_id', $dentist->id);
                    })->where('status', 'paid')->sum('total_amount')
                ];
            });
    }

    private function getMonthlyGrowth($clinicId)
    {
        $currentMonth = \App\Models\Invoice::where('clinic_id', $clinicId)
            ->where('status', 'paid')
            ->whereMonth('paid_at', Carbon::now()->month)
            ->sum('total_amount');
            
        $lastMonth = \App\Models\Invoice::where('clinic_id', $clinicId)
            ->where('status', 'paid')
            ->whereMonth('paid_at', Carbon::now()->subMonth()->month)
            ->sum('total_amount');
            
        if ($lastMonth == 0) return 100;
        
        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    private function getRevenuePerPatient($clinicId)
    {
        $totalRevenue = \App\Models\Invoice::where('clinic_id', $clinicId)
            ->where('status', 'paid')
            ->sum('total_amount');
            
        $patientCount = Patient::where('clinic_id', $clinicId)->count();
        
        return $patientCount > 0 ? round($totalRevenue / $patientCount, 2) : 0;
    }

    private function getRepeatVisitRate($clinicId)
    {
        $totalPatients = Patient::where('clinic_id', $clinicId)->count();
        if ($totalPatients == 0) return 0;
        
        $repeatPatients = Patient::where('clinic_id', $clinicId)
            ->whereHas('appointments', [], '>', 1)
            ->count();
            
        return round(($repeatPatients / $totalPatients) * 100, 1);
    }

    private function getChairUtilizationRate($clinicId)
    {
        $chairCount = \App\Models\Chair::where('clinic_id', $clinicId)->where('is_active', true)->count();
        if ($chairCount == 0) return 0;

        $totalAppointmentMinutes = Appointment::where('clinic_id', $clinicId)
            ->whereDate('appointment_date', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->sum('duration');

        // Assuming 8 hour working day (480 minutes)
        $totalAvailableMinutes = $chairCount * 480;
        
        return round(($totalAppointmentMinutes / $totalAvailableMinutes) * 100, 1);
    }

    private function getCompletionRate($clinicId)
    {
        $total = Appointment::where('clinic_id', $clinicId)
            ->whereMonth('appointment_date', Carbon::now()->month)
            ->count();
            
        $completed = Appointment::where('clinic_id', $clinicId)
            ->whereMonth('appointment_date', Carbon::now()->month)
            ->where('status', 'completed')
            ->count();
            
        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }

    private function getPopularTreatments($clinicId)
    {
        return Appointment::where('clinic_id', $clinicId)
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
    }

    private function getMonthlyTrends($clinicId)
    {
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        
        $results = Appointment::where('clinic_id', $clinicId)
            ->where('appointment_date', '>=', $startDate)
            ->selectRaw("DATE_FORMAT(appointment_date, '%b %Y') as month_year, COUNT(*) as count, MIN(appointment_date) as sort_date")
            ->groupBy('month_year')
            ->orderBy('sort_date')
            ->get()
            ->pluck('count', 'month_year');

        $trends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $key = $date->format('M Y');
            $trends[] = [
                'month' => $key,
                'appointments' => $results->get($key, 0)
            ];
        }
        
        return $trends;
    }
}
