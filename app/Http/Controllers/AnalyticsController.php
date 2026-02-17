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
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'appointments' => Appointment::where('clinic_id', $clinicId)
                    ->whereYear('appointment_date', $date->year)
                    ->whereMonth('appointment_date', $date->month)
                    ->count()
            ];
        }
        return $months;
    }
}
