<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClinicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:clinic_admin|superadmin']);
    }

    public function index()
    {
        $services = ClinicService::where('clinic_id', Auth::user()->clinic_id)
            ->ordered()
            ->paginate(15);

        return view('admin.cms.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.cms.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'duration_minutes' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'show_pricing' => 'boolean',
            'featured_image' => 'nullable|image|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $validated['clinic_id'] = Auth::user()->clinic_id;
        $validated['slug'] = Str::slug($validated['name']);

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('services', 'public');
            $validated['featured_image'] = $path;
        }

        ClinicService::create($validated);

        return redirect()->route('admin.cms.services.index')
            ->with('success', 'Service created successfully!');
    }

    public function edit(ClinicService $service)
    {
        // Ensure service belongs to user's clinic
        if ($service->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        return view('admin.cms.services.edit', compact('service'));
    }

    public function update(Request $request, ClinicService $service)
    {
        // Ensure service belongs to user's clinic
        if ($service->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'duration_minutes' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'show_pricing' => 'boolean',
            'featured_image' => 'nullable|image|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($service->featured_image) {
                Storage::disk('public')->delete($service->featured_image);
            }
            $path = $request->file('featured_image')->store('services', 'public');
            $validated['featured_image'] = $path;
        }

        $service->update($validated);

        return redirect()->route('admin.cms.services.index')
            ->with('success', 'Service updated successfully!');
    }

    public function destroy(ClinicService $service)
    {
        // Ensure service belongs to user's clinic
        if ($service->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        // Delete image
        if ($service->featured_image) {
            Storage::disk('public')->delete($service->featured_image);
        }

        $service->delete();

        return redirect()->route('admin.cms.services.index')
            ->with('success', 'Service deleted successfully!');
    }

    public function toggle(ClinicService $service)
    {
        // Ensure service belongs to user's clinic
        if ($service->clinic_id !== Auth::user()->clinic_id) {
            abort(403);
        }

        $service->update(['is_active' => !$service->is_active]);

        return back()->with('success', 'Service status updated!');
    }
}
