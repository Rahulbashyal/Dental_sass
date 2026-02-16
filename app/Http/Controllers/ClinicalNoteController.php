<?php

namespace App\Http\Controllers;

use App\Models\ClinicalNote;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicalNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'clinic.access', 'resource.owner']);
    }

    public function index(Request $request)
    {
        $clinicalNotes = ClinicalNote::with(['patient', 'dentist'])
            ->where('clinic_id', Auth::user()->clinic_id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('clinical-notes.index', compact('clinicalNotes'));
    }

    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'tooth_number' => 'nullable|integer',
            'surface' => 'nullable|string|max:10',
            'condition' => 'nullable|string|max:50',
            'note' => 'required|string',
        ]);

        $clinicalNote = new ClinicalNote($validated);
        $clinicalNote->clinic_id = Auth::user()->clinic_id;
        $clinicalNote->patient_id = $patient->id;
        $clinicalNote->dentist_id = Auth::id();
        $clinicalNote->save();

        return back()->with('success', 'Clinical finding recorded.');
    }

    public function destroy(ClinicalNote $note)
    {
        $note->delete();
        return back()->with('success', 'Finding removed.');
    }
}
