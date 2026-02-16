<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('settings.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo && \Storage::disk('public')->exists($user->photo)) {
                \Storage::disk('public')->delete($user->photo);
            }
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->photo = $path;
        }

        $socialLinks = [
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
            'instagram' => $request->instagram,
        ];

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->specialization = $validated['specialization'];
        $user->bio = $validated['bio'];
        $user->address = $validated['address'];
        $user->city = $validated['city'];
        $user->state = $validated['state'];
        $user->postal_code = $validated['postal_code'];
        $user->country = $validated['country'];
        $user->website = $validated['website'];
        $user->social_links = $socialLinks;

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function editPassword()
    {
        return view('settings.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function index()
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic) {
            return redirect()->route('dashboard')
                ->with('error', 'No clinic assigned to your account. Please contact the superadmin.');
        }

        $days = [
            'monday'    => 'Monday',
            'tuesday'   => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday'  => 'Thursday',
            'friday'    => 'Friday',
            'saturday'  => 'Saturday',
            'sunday'    => 'Sunday',
        ];

        $workingDays = $clinic->working_days ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        // Ensure working_days is always an array
        if (is_string($workingDays)) {
            $workingDays = json_decode($workingDays, true) ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        }

        return view('settings.index', compact('clinic', 'days', 'workingDays'));
    }

    public function update(Request $request)
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic) {
            return redirect()->route('dashboard')
                ->with('error', 'No clinic assigned to your account.');
        }

        $validated = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email',
            'phone'                => 'nullable|string|max:20',
            'address'              => 'nullable|string|max:500',
            'city'                 => 'nullable|string|max:100',
            'state'                => 'nullable|string|max:100',
            'website'              => 'nullable|url|max:255',
            'primary_color'        => 'nullable|string|max:7',
            'secondary_color'      => 'nullable|string|max:7',
            'currency'             => 'nullable|string|in:NPR,USD',
            'timezone'             => 'nullable|string|max:50',
            'appointment_duration' => 'nullable|integer|min:15|max:180',
            'working_hours_start'  => 'nullable|date_format:H:i',
            'working_hours_end'    => 'nullable|date_format:H:i',
            'working_days'         => 'nullable|array',
            'working_days.*'       => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'description'          => 'nullable|string|max:1000',
        ]);

        $clinic->update($validated);

        return redirect()->route('settings.index')
            ->with('success', 'Clinic settings updated successfully.');
    }

    public function businessHours()
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic) {
            return redirect()->route('dashboard')
                ->with('error', 'No clinic assigned to your account.');
        }

        $days = [
            'monday'    => 'Monday',
            'tuesday'   => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday'  => 'Thursday',
            'friday'    => 'Friday',
            'saturday'  => 'Saturday',
            'sunday'    => 'Sunday',
        ];

        $workingDays = $clinic->working_days ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        if (is_string($workingDays)) {
            $workingDays = json_decode($workingDays, true) ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        }

        return view('settings.business-hours', compact('clinic', 'days', 'workingDays'));
    }

    public function updateBusinessHours(Request $request)
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic) {
            return redirect()->route('dashboard')
                ->with('error', 'No clinic assigned to your account.');
        }

        $validated = $request->validate([
            'working_hours_start' => 'required|date_format:H:i',
            'working_hours_end'   => 'required|date_format:H:i|after:working_hours_start',
            'working_days'        => 'required|array|min:1',
            'working_days.*'      => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ]);

        $clinic->update($validated);

        return redirect()->route('settings.business-hours')
            ->with('success', 'Business hours updated successfully.');
    }
}