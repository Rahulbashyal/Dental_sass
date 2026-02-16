<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class PatientPortalController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('patient-portal.login');
        }

        $this->ensureIsNotRateLimited($request);

        $credentials = $request->validate([
            'email' => 'required|string|max:255',
            'patient_id' => 'required|string|max:20'
        ]);

        // Sanitize input
        $email = strtolower(trim($credentials['email']));
        $patientId = strtoupper(trim($credentials['patient_id']));

        $patient = Patient::where('email', $email)
            ->where('patient_id', $patientId)
            ->first();

        if (!$patient) {
            // Try case-insensitive search if direct match fails
            $patient = Patient::whereRaw('LOWER(email) = ?', [$email])
                ->whereRaw('UPPER(patient_id) = ?', [$patientId])
                ->first();
        }

        if (!$patient) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => 'Authentication failed. Please check your credentials.',
            ]);
        }

        if (!$patient->is_active) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => 'Your portal access is currently inactive.',
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));
        
        Auth::guard('patient')->login($patient);
        
        return redirect()->route('patient.dashboard');
    }

    public function dashboard()
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            return redirect()->route('patient.login');
        }

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
                ->sum('total_amount'),
            'active_payment_plans' => $patient->paymentPlans()
                ->where('status', 'active')
                ->count(),
            'pending_consents' => $patient->consents()
                ->where('status', 'pending')
                ->count()
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
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            return redirect()->route('patient.login');
        }

        $appointments = $patient->appointments()
            ->with('clinic')
            ->latest()
            ->paginate(10);

        return view('patient-portal.appointments', compact('appointments'));
    }

    public function recurringAppointments()
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            return redirect()->route('patient.login');
        }

        $recurringAppointments = $patient->recurringAppointments()
            ->with(['dentist', 'clinic'])
            ->latest()
            ->paginate(10);

        return view('patient-portal.recurring-appointments', compact('recurringAppointments'));
    }

    public function paymentPlans()
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            return redirect()->route('patient.login');
        }

        $paymentPlans = $patient->paymentPlans()
            ->with(['invoice', 'clinic', 'paymentInstallments'])
            ->latest()
            ->paginate(10);

        return view('patient-portal.payment-plans.index', compact('paymentPlans'));
    }

    public function paymentPlan(\App\Models\PaymentPlan $paymentPlan)
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient || $paymentPlan->patient_id !== $patient->id) {
            return redirect()->route('patient.login');
        }

        $paymentPlan->load(['paymentInstallments', 'invoice', 'clinic']);
        
        return view('patient-portal.payment-plans.show', compact('paymentPlan'));
    }

    public function consents()
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            return redirect()->route('patient.login');
        }

        $consents = $patient->consents()
            ->with(['template', 'clinic'])
            ->latest()
            ->paginate(10);

        return view('patient-portal.consents.index', compact('consents'));
    }

    public function signConsent(\App\Models\PatientConsent $consent, Request $request)
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient || $consent->patient_id !== $patient->id) {
            return redirect()->route('patient.login');
        }

        if ($request->isMethod('get')) {
            $consent->load(['template', 'clinic']);
            return view('patient-portal.consents.sign', compact('consent'));
        }

        $validated = $request->validate([
            'signature_data' => 'required|string', // Base64 signature image
        ]);

        $consent->update([
            'signature_data' => $validated['signature_data'],
            'status' => 'signed',
            'signed_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('patient.consents')->with('success', 'Document signed successfully.');
    }

    public function invoices()
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            return redirect()->route('patient.login');
        }
        $invoices = $patient->invoices()
            ->with('clinic')
            ->latest()
            ->paginate(10);

        return view('patient-portal.invoices', compact('invoices'));
    }

    public function profile()
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            return redirect()->route('patient.login');
        }
        return view('patient-portal.profile', compact('patient'));
    }

    public function updateProfile(Request $request)
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            return redirect()->route('patient.login');
        }
        
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $patient->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePhoto(Request $request)
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            return redirect()->route('patient.login');
        }
        
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,gif|max:2048',
        ]);

        // Delete old photo if exists
        if ($patient->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($patient->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($patient->photo);
        }

        // Store new photo
        $path = $request->file('photo')->store('patient-photos', 'public');
        $patient->update(['photo' => $path]);

        return back()->with('success', 'Profile photo updated successfully.');
    }

    public function logout()
    {
        Auth::guard('patient')->logout();
        return redirect()->route('patient.login')->with('success', 'Logged out successfully.');
    }
    
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 20)) {
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
}