<?php

namespace App\Http\Controllers;

use App\Models\LabOrder;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabOrderController extends Controller
{
    public function index()
    {
        $orders = LabOrder::with(['patient', 'dentist'])
            ->where('clinic_id', Auth::user()->clinic_id)
            ->latest()
            ->paginate(20);

        $overdueCount = $orders->filter(fn($o) => $o->isOverdue())->count();

        return view('lab.index', compact('orders', 'overdueCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'           => 'required|exists:patients,id',
            'lab_name'             => 'nullable|string|max:100',
            'category'             => 'required|in:impression,crown,bridge,denture,bleaching_tray,night_guard,orthodontic,other',
            'instructions'         => 'required|string',
            'shade'                => 'nullable|string|max:20',
            'materials'            => 'nullable|array',
            'expected_return_date' => 'nullable|date',
            'lab_cost'             => 'nullable|numeric',
        ]);

        $count = LabOrder::where('clinic_id', Auth::user()->clinic_id)->count() + 1;

        LabOrder::create(array_merge($validated, [
            'clinic_id'    => Auth::user()->clinic_id,
            'dentist_id'   => Auth::id(),
            'order_number' => 'LAB' . date('Ymd') . str_pad($count, 4, '0', STR_PAD_LEFT),
            'status'       => 'draft',
            'sent_date'    => now(),
        ]));

        return back()->with('success', 'Lab order created successfully.');
    }

    public function updateStatus(Request $request, LabOrder $order)
    {
        if ($order->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,sent,in_progress,received,fitted',
        ]);

        $updates = ['status' => $validated['status']];
        if ($validated['status'] === 'received') {
            $updates['received_date'] = now()->toDateString();
        }

        $order->update($updates);

        return back()->with('success', "Lab order marked as {$validated['status']}.");
    }
}
