<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaseDiscussionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'clinic.access', 'resource.owner']);
    }

    public function store(Request $request, Patient $patient)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'patient_id' => $patient->id,
            'body' => $request->body,
            'type' => 'case_discussion',
            'clinic_id' => Auth::user()->clinic_id
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender')
            ]);
        }

        return back()->with('success', 'Comment added to case discussion.');
    }

    public function index(Patient $patient)
    {
        $messages = Message::where('patient_id', $patient->id)
            ->where('type', 'case_discussion')
            ->with('sender')
            ->latest()
            ->get();

        return response()->json($messages);
    }
}
