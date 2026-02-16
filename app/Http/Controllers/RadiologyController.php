<?php

namespace App\Http\Controllers;

use App\Models\ImagingStudy;
use App\Models\ImagingFile;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RadiologyController extends Controller
{
    public function index()
    {
        $studies = ImagingStudy::with(['patient', 'dentist', 'files'])
            ->where('clinic_id', Auth::user()->clinic_id)
            ->latest()
            ->paginate(20);

        return view('radiology.index', compact('studies'));
    }

    public function create()
    {
        $patients = Patient::where('clinic_id', Auth::user()->clinic_id)
            ->orderBy('first_name')
            ->get();

        return view('radiology.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'          => 'required|exists:patients,id',
            'type'                => 'required|in:x_ray,cbct,panoramic,periapical,bitewing,cephalometric,intraoral',
            'tooth_area'          => 'nullable|string|max:100',
            'clinical_indication' => 'nullable|string',
            'study_date'          => 'required|date',
            'images'              => 'nullable|array',
            'images.*'            => 'file|mimes:jpg,jpeg,png,gif,dicom,dcm|max:20480',
        ]);

        $study = ImagingStudy::create([
            'clinic_id'           => Auth::user()->clinic_id,
            'patient_id'          => $validated['patient_id'],
            'dentist_id'          => Auth::id(),
            'type'                => $validated['type'],
            'tooth_area'          => $validated['tooth_area'] ?? null,
            'clinical_indication' => $validated['clinical_indication'] ?? null,
            'study_date'          => $validated['study_date'],
            'status'              => 'captured',
        ]);

        // Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store("clinic/{$study->clinic_id}/radiology", 'public');

                $study->files()->create([
                    'file_path'  => $path,
                    'file_name'  => $image->getClientOriginalName(),
                    'mime_type'  => $image->getMimeType(),
                    'file_size'  => $image->getSize(),
                ]);
            }
        }

        return redirect()->route('clinic.radiology.show', $study)
            ->with('success', 'Imaging study recorded successfully.');
    }

    public function show(ImagingStudy $study)
    {
        if ($study->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $study->load(['patient', 'dentist', 'files']);
        return view('radiology.show', compact('study'));
    }

    public function updateFindings(Request $request, ImagingStudy $study)
    {
        if ($study->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $validated = $request->validate([
            'findings'          => 'required|string',
            'radiologist_notes' => 'nullable|string',
            'status'            => 'required|in:ordered,captured,reported,reviewed',
        ]);

        $study->update($validated);

        return back()->with('success', 'Study findings updated.');
    }
}
