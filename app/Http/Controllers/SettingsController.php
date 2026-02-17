<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $clinic = Auth::user()->clinic;
        
        $days = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday', 
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];
        
        $workingDays = $clinic->working_days ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        
        return view('settings.index', compact('clinic', 'days', 'workingDays'));
    }

    public function update(Request $request)
    {
        $clinic = Auth::user()->clinic;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'website' => 'nullable|url',
            'primary_color' => 'nullable|string',
            'secondary_color' => 'nullable|string',
        ]);

        $clinic->update($validated);

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}