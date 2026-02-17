<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:clinic_admin|superadmin']);
    }

    public function index()
    {
        $teamMembers = TeamMember::where('clinic_id', Auth::user()->clinic_id)
            ->with('user')
            ->ordered()
            ->paginate(15);

        return view('admin.cms.team.index', compact('teamMembers'));
    }

    public function create()
    {
        // Get staff users that can be linked
        $staffUsers = User::where('clinic_id', Auth::user()->clinic_id)
            ->role(['dentist', 'clinic_admin'])
            ->get();

        return view('admin.cms.team.create', compact('staffUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'education' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'languages' => 'nullable|string',
            'available_days' => 'nullable|array',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'social_links' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $validated['clinic_id'] = Auth::user()->clinic_id;

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('team', 'public');
            $validated['photo'] = $path;
        }

        TeamMember::create($validated);

        return redirect()->route('admin.cms.team.index')
            ->with('success', 'Team member added successfully!');
    }

    public function edit(TeamMember $team)
    {
        // Ensure team member belongs to user's clinic
        if ($team->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $staffUsers = User::where('clinic_id', Auth::user()->clinic_id)
            ->role(['dentist', 'clinic_admin'])
            ->get();

        return view('admin.cms.team.edit', compact('team', 'staffUsers'));
    }

    public function update(Request $request, TeamMember $team)
    {
        // Ensure team member belongs to user's clinic
        if ($team->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'education' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'languages' => 'nullable|string',
            'available_days' => 'nullable|array',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'social_links' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($team->photo) {
                Storage::disk('public')->delete($team->photo);
            }
            $path = $request->file('photo')->store('team', 'public');
            $validated['photo'] = $path;
        }

        $team->update($validated);

        return redirect()->route('admin.cms.team.index')
            ->with('success', 'Team member updated successfully!');
    }

    public function destroy(TeamMember $team)
    {
        // Ensure team member belongs to user's clinic
        if ($team->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        // Delete photo
        if ($team->photo) {
            Storage::disk('public')->delete($team->photo);
        }

        $team->delete();

        return redirect()->route('admin.cms.team.index')
            ->with('success', 'Team member removed successfully!');
    }

    public function toggle(TeamMember $team)
    {
        // Ensure team member belongs to user's clinic
        if ($team->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $team->update(['is_active' => !$team->is_active]);

        return back()->with('success', 'Team member status updated!');
    }
}
