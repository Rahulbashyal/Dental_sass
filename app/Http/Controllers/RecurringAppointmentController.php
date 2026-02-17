<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecurringAppointmentController extends Controller
{
    public function index()
    {
        return view('recurring-appointments.index');
    }

    public function create()
    {
        return view('recurring-appointments.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('recurring-appointments.index')->with('success', 'Recurring appointment created successfully.');
    }

    public function show($id)
    {
        return view('recurring-appointments.show');
    }

    public function edit($id)
    {
        return view('recurring-appointments.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('recurring-appointments.index')->with('success', 'Recurring appointment updated successfully.');
    }

    public function destroy($id)
    {
        return redirect()->route('recurring-appointments.index')->with('success', 'Recurring appointment deleted successfully.');
    }
}