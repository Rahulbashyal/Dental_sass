<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    protected $reportsService;

    public function __construct(\App\Services\ReportsService $reportsService)
    {
        $this->reportsService = $reportsService;
    }

    public function index()
    {
        $user = Auth::user();
        
        if (!$user->clinic_id) {
            return redirect()->route('dashboard')->with('error', 'Access denied. No clinic assigned.');
        }

        // Base queries
        $patientQuery = Patient::where('clinic_id', $user->clinic_id);
        $appointmentQuery = Appointment::where('clinic_id', $user->clinic_id);
        
        // If user is dentist, filter to their data only
        if ($user->hasRole('dentist')) {
            $appointmentQuery->where('dentist_id', $user->id);
            $patientQuery->whereHas('appointments', function($q) use ($user) {
                $q->where('dentist_id', $user->id);
            });
        }

        $stats = [
            'total_patients' => $patientQuery->count(),
            'monthly_appointments' => (clone $appointmentQuery)
                ->whereMonth('appointment_date', now()->month)
                ->count(),
            'monthly_revenue' => (clone $appointmentQuery)
                ->whereMonth('appointment_date', now()->month)
                ->where('payment_status', 'paid')
                ->sum('treatment_cost'),
            'total_appointments' => $appointmentQuery->count()
        ];

        $completed = (clone $appointmentQuery)
            ->where('status', 'completed')
            ->count();
        
        $stats['completion_rate'] = $stats['total_appointments'] > 0 
            ? round(($completed / $stats['total_appointments']) * 100) 
            : 0;

        // Executive Intelligence Data
        $executiveStats = $this->reportsService->getExecutiveStats($user->clinic_id);

        $recent_appointments = (clone $appointmentQuery)
            ->with('patient')
            ->latest('appointment_date')
            ->take(5)
            ->get();

        $treatment_stats = (clone $appointmentQuery)
            ->select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->orderBy('count', 'desc')
            ->get();

        return view('reports.index', compact('stats', 'recent_appointments', 'treatment_stats', 'executiveStats'));
    }
}