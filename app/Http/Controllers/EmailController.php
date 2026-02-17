<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Patient;
use App\Models\Appointment;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function index()
    {
        $emails = Email::with(['sender'])
            ->where('clinic_id', Auth::user()->clinic_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('emails.index', compact('emails'));
    }

    public function compose()
    {
        $patients = Patient::where('clinic_id', Auth::user()->clinic_id)->get();
        $templates = $this->emailService->getEmailTemplates();
        
        return view('emails.compose', compact('patients', 'templates'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        try {
            $email = $this->emailService->sendEmail([
                'sender_id' => Auth::id(),
                'recipients' => $request->recipients,
                'subject' => $request->subject,
                'body' => $request->body,
                'clinic_id' => Auth::user()->clinic_id,
                'attachments' => $request->attachments ?? []
            ]);

            return redirect()->route('emails.index')
                ->with('success', 'Email sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function sendBulk(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:all_patients,selected_patients',
            'selected_patients' => 'required_if:recipient_type,selected_patients|array',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        try {
            $recipients = [];
            
            if ($request->recipient_type === 'all_patients') {
                $recipients = Patient::where('clinic_id', Auth::user()->clinic_id)
                    ->whereNotNull('email')
                    ->pluck('email')
                    ->toArray();
            } else {
                $recipients = Patient::whereIn('id', $request->selected_patients)
                    ->whereNotNull('email')
                    ->pluck('email')
                    ->toArray();
            }

            $this->emailService->sendBulkEmail(
                $recipients,
                $request->subject,
                $request->body,
                Auth::user()->clinic_id
            );

            return redirect()->route('emails.index')
                ->with('success', 'Bulk email sent to ' . count($recipients) . ' recipients!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send bulk email: ' . $e->getMessage());
        }
    }

    public function sendAppointmentReminder(Appointment $appointment)
    {
        // Authorization: Check clinic ownership
        if ($appointment->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'Unauthorized access to appointment.');
        }
        
        try {
            $this->emailService->sendAppointmentReminder($appointment);
            
            return back()->with('success', 'Appointment reminder sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send reminder: ' . $e->getMessage());
        }
    }

    public function show(Email $email)
    {
        if ($email->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        return view('emails.show', compact('email'));
    }

    public function getTemplate(Request $request)
    {
        $templates = $this->emailService->getEmailTemplates();
        $template = $templates[$request->template] ?? null;
        
        return response()->json($template);
    }
}