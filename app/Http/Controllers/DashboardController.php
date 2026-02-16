<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('superadmin')) {
            return $this->superadminDashboard();
        }
        
        if ($user->hasRole('receptionist')) {
            return $this->receptionistDashboard();
        }
        
        if ($user->hasRole('accountant')) {
            return $this->accountantDashboard();
        }
        
        if ($user->hasRole('dentist')) {
            return $this->dentistDashboard();
        }
        
        if ($user->hasRole('clinic_admin')) {
            return $this->clinicAdminDashboard();
        }
        
        // Default clinic dashboard for other roles
        return $this->clinicDashboard();
    }
    
    private function superadminDashboard()
    {
        $stats = [
            'total_clinics' => Clinic::count(),
            'active_clinics' => Clinic::where('is_active', true)->count(),
            'total_patients' => Patient::count(),
            'total_appointments' => Appointment::count(),
        ];
        
        // Chart data for last 6 months
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartData[] = [
                'month' => $date->format('M'),
                'clinics' => Clinic::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'users' => \App\Models\User::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'revenue' => \App\Models\Subscription::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->where('status', 'active')
                    ->sum('amount')
            ];
        }
        
        $subscriptionPlans = [];
        $recentClinics = Clinic::latest()->take(5)->get();
        $recentUsers = \App\Models\User::latest()->take(5)->with('clinic')->get();
        
        return view('dashboard.superadmin', compact('stats', 'chartData', 'subscriptionPlans', 'recentClinics', 'recentUsers'));
    }
    
    private function receptionistDashboard()
    {
        $user = Auth::user();
        $clinic = $user->clinic;
        
        if (!$clinic) {
            return $this->clinicDashboard();
        }
        
        $today = today();
        
        $stats = [
            'todays_appointments' => $clinic->appointments()->whereDate('appointment_date', $today)->count(),
            'pending_appointments' => $clinic->appointments()->where('status', 'scheduled')->count(),
            'total_patients' => $clinic->patients()->count(),
            'waiting_patients' => $clinic->appointments()
                ->whereDate('appointment_date', $today)
                ->where('status', 'confirmed')
                ->count(),
        ];
        
        $todaysAppointments = $clinic->appointments()
            ->with(['patient', 'dentist'])
            ->whereDate('appointment_date', $today)
            ->orderBy('appointment_time')
            ->get();
            
        return view('dashboard.receptionist', compact('stats', 'todaysAppointments', 'clinic'));
    }
    
    private function accountantDashboard()
    {
        $user = Auth::user();
        $clinic = $user->clinic;
        
        if (!$clinic) {
            return $this->clinicDashboard();
        }
        
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $stats = [
            'monthly_revenue' => $clinic->invoices()
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->where('status', 'paid')
                ->sum('total_amount'),
            'pending_payments' => $clinic->invoices()
                ->where('status', 'pending')
                ->sum('total_amount'),
            'total_invoices' => $clinic->invoices()->count(),
            'overdue_invoices' => $clinic->invoices()
                ->where('status', 'pending')
                ->where('due_date', '<', now())
                ->count(),
        ];
        
        $recentInvoices = $clinic->invoices()
            ->with('patient')
            ->latest()
            ->take(10)
            ->get();
            
        return view('dashboard.accountant', compact('stats', 'recentInvoices', 'clinic'));
    }
    
    private function clinicDashboard()
    {
        $user = Auth::user();
        $clinic = $user->clinic;
        
        $aiService = new \App\Services\AiInsightService();
        $aiInsights = $aiService->generateClinicInsights();

        if (!$clinic) {
            $stats = [
                'total_patients' => 0, 
                'todays_appointments' => 0, 
                'pending_appointments' => 0, 
                'monthly_revenue' => 0,
                'total_staff' => 0
            ];
            $recentAppointments = collect();
            $recentPatients = collect();
            
            return view('dashboard.clinic', compact('clinic', 'stats', 'aiInsights', 'recentAppointments', 'recentPatients'))
                ->with('warning', 'No clinic assigned to your account. Contact administrator.');
        }
        
        $today = today();
        $currentMonth = now()->month;
        
        $stats = [
            'total_patients' => $clinic->patients()->count(),
            'todays_appointments' => $clinic->appointments()->whereDate('appointment_date', $today)->count(),
            'pending_appointments' => $clinic->appointments()->where('status', 'scheduled')->count(),
            'monthly_revenue' => $clinic->appointments()
                ->whereMonth('appointment_date', $currentMonth)
                ->where('payment_status', 'paid')
                ->sum('treatment_cost') ?? 0,
        ];
        
        return view('dashboard.clinic', compact('clinic', 'stats', 'aiInsights'));
    }
    
    private function dentistDashboard()
    {
        $user = Auth::user();
        $clinic = $user->clinic;
        
        if (!$clinic) {
            return $this->clinicDashboard();
        }
        
        $today = today();
        
        $stats = [
            'todays_appointments' => $clinic->appointments()
                ->where('dentist_id', $user->id)
                ->whereDate('appointment_date', $today)
                ->count(),
            'upcoming_appointments' => $clinic->appointments()
                ->where('dentist_id', $user->id)
                ->where('appointment_date', '>', $today)
                ->count(),
            'total_patients' => $clinic->patients()
                ->whereHas('appointments', function ($query) use ($user) {
                    $query->where('dentist_id', $user->id);
                })
                ->count(),
            'completed_treatments' => $clinic->appointments()
                ->where('dentist_id', $user->id)
                ->where('status', 'completed')
                ->whereMonth('appointment_date', $today->month)
                ->whereYear('appointment_date', $today->year)
                ->count(),
            'active_treatment_plans' => $clinic->treatmentPlans()
                ->where('dentist_id', $user->id)
                ->where('status', 'active')
                ->count(),
        ];
        
        $todaysAppointments = $clinic->appointments()
            ->with('patient')
            ->where('dentist_id', $user->id)
            ->whereDate('appointment_date', $today)
            ->orderBy('appointment_time')
            ->get();
            
        return view('dashboard.dentist', compact('stats', 'todaysAppointments', 'clinic'));
    }

    private function clinicAdminDashboard()
    {
        $user = Auth::user();
        $clinic = $user->clinic;
        
        $aiService = new \App\Services\AiInsightService();
        $aiInsights = $aiService->generateClinicInsights();

        if (!$clinic) {
            return $this->clinicDashboard();
        }
        
        $today = today();
        $currentMonth = now()->month;
        
        $stats = [
            'total_patients' => $clinic->patients()->count(),
            'todays_appointments' => $clinic->appointments()->whereDate('appointment_date', $today)->count(),
            'total_staff' => $clinic->users()->count(),
            'monthly_revenue' => $clinic->invoices()
                ->whereMonth('created_at', $currentMonth)
                ->where('status', 'paid')
                ->sum('total_amount'),
            'pending_appointments' => $clinic->appointments()->where('status', 'scheduled')->count(),
            'completed_treatments' => $clinic->appointments()
                ->where('status', 'completed')
                ->whereMonth('appointment_date', $currentMonth)
                ->count(),
        ];
        
        $recentAppointments = $clinic->appointments()
            ->with(['patient', 'dentist'])
            ->latest()
            ->take(5)
            ->get();
            
        $recentPatients = $clinic->patients()
            ->latest()
            ->take(5)
            ->get();
            
        return view('dashboard.clinic', compact('stats', 'recentAppointments', 'recentPatients', 'clinic', 'aiInsights'));
    }

    public function clinic()
    {
        return $this->index();
    }
}