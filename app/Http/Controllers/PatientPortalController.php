<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PatientPortalController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('patient-portal.login');
        }

        $this->ensureIsNotRateLimited($request);

        $credentials = $request->validate([
            'email' => 'required|email:rfc,dns|max:255',
            'patient_id' => 'required|string|max:20|regex:/^P[0-9]{6}$/'
        ], [
            'patient_id.regex' => 'Patient ID must be in format P000000'
        ]);

        // Sanitize input
        $credentials['email'] = strtolower(trim($credentials['email']));
        $credentials['patient_id'] = strtoupper(trim($credentials['patient_id']));

        $patient = Patient::where('email', $credentials['email'])
            ->where('patient_id', $credentials['patient_id'])
            ->where('is_active', true)
            ->first();

        if (!$patient) {
            RateLimiter::hit($this->throttleKey($request));
            return back()->withErrors(['email' => 'Invalid credentials'])
                ->withInput($request->only('email'));
        }

        RateLimiter::clear($this->throttleKey($request));
        session(['patient_id' => $patient->id, 'patient_login_time' => now()]);
        return redirect()->route('patient.dashboard');
    }

    public function dashboard()
    {
        $patientId = session('patient_id');
        if (!$patientId) {
            return redirect()->route('patient.login');
        }

        $patient = Patient::findOrFail($patientId);
        
        $stats = [
            'upcoming_appointments' => $patient->appointments()
                ->where('appointment_date', '>=', now())
                ->where('status', '!=', 'cancelled')
                ->count(),
            'total_appointments' => $patient->appointments()->count(),
            'pending_invoices' => $patient->invoices()
                ->where('status', 'pending')
                ->count(),
            'total_amount_due' => $patient->invoices()
                ->where('status', 'pending')
                ->sum('total_amount')
        ];

        $recentAppointments = $patient->appointments()
            ->with('clinic')
            ->latest()
            ->take(5)
            ->get();

        return view('patient-portal.dashboard', compact('patient', 'stats', 'recentAppointments'));
    }

    public function appointments()
    {
        $patientId = session('patient_id');
        if (!$patientId) {
            return redirect()->route('patient.login');
        }

        $patient = Patient::findOrFail($patientId);
        $appointments = $patient->appointments()
            ->with('clinic')
            ->latest()
            ->paginate(10);

        return view('patient-portal.appointments', compact('appointments'));
    }

    public function invoices()
    {
        $patientId = session('patient_id');
        if (!$patientId) {
            return redirect()->route('patient.login');
        }

        $patient = Patient::findOrFail($patientId);
        $invoices = $patient->invoices()
            ->with('clinic')
            ->latest()
            ->paginate(10);

        return view('patient-portal.invoices', compact('invoices'));
    }

    public function profile()
    {
        $patientId = session('patient_id');
        if (!$patientId) {
            return redirect()->route('patient.login');
        }

        $patient = Patient::findOrFail($patientId);
        return view('patient-portal.profile', compact('patient'));
    }

    public function updateProfile(Request $request)
    {
        $patientId = session('patient_id');
        if (!$patientId) {
            return redirect()->route('patient.login');
        }

        $patient = Patient::findOrFail($patientId);
        
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $patient->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function logout()
    {
        session()->forget(['patient_id', 'patient_login_time']);
        return redirect()->route('patient.login')->with('success', 'Logged out successfully.');
    }
    
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));
            
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }
    }
    
    protected function throttleKey(Request $request): string
    {
        return 'patient_login:'.Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }
    
    protected function validatePatientSession(): ?Patient
    {
        $patientId = session('patient_id');
        $loginTime = session('patient_login_time');
        
        if (!$patientId || !$loginTime) {
            return null;
        }
        
        // Session expires after 2 hours
        if (now()->diffInHours($loginTime) > 2) {
            session()->forget(['patient_id', 'patient_login_time']);
            return null;
        }
        
        $patient = Patient::where('id', $patientId)
            ->where('is_active', true)
            ->first();
            
        if (!$patient) {
            session()->forget(['patient_id', 'patient_login_time']);
            return null;
        }
        
        return $patient;
    }
}