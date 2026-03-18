<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Lead;
use App\Models\Campaign;
use App\Models\EmailLog;
use App\Models\LandingPageContent;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;

class SuperAdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $stats = [
            'total_clinics' => Clinic::count(),
            'active_clinics' => Clinic::where('is_active', true)->count(),
            'total_users' => User::count(),
            'total_patients' => Patient::count(),
            'total_appointments' => Appointment::count(),
            'monthly_revenue' => Subscription::where('status', 'active')->sum('amount'),
            'pending_subscriptions' => Subscription::where('status', 'pending')->count()
        ];

        // Get date filter (default to 6 months)
        $days = $request->get('days', 180);
        $periods = $this->getDatePeriods($days);
        
        // Chart data based on selected period
        $chartData = [];
        foreach ($periods as $period) {
            $chartData[] = [
                'month' => $period['label'],
                'clinics' => Clinic::whereBetween('created_at', [$period['start'], $period['end']])->count(),
                'users' => User::whereBetween('created_at', [$period['start'], $period['end']])->count(),
                'revenue' => Subscription::whereBetween('created_at', [$period['start'], $period['end']])
                    ->where('status', 'active')
                    ->sum('amount')
            ];
        }

        // Subscription plan distribution
        $subscriptionPlans = Subscription::select('subscription_plan_id')
            ->selectRaw('COUNT(*) as count')
            ->where('status', 'active')
            ->groupBy('subscription_plan_id')
            ->get();

        // Recent activity
        $recentClinics = Clinic::latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->with('clinic')->get();
        
        return view('dashboard.superadmin', compact('stats', 'chartData', 'subscriptionPlans', 'recentClinics', 'recentUsers'));
    }

    private function getDatePeriods($days)
    {
        $periods = [];
        
        if ($days <= 7) {
            // Last 7 days - daily periods
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $periods[] = [
                    'label' => $date->format('M j'),
                    'start' => $date->startOfDay(),
                    'end' => $date->endOfDay()
                ];
            }
        } elseif ($days <= 30) {
            // Last 30 days - weekly periods
            for ($i = 3; $i >= 0; $i--) {
                $start = now()->subWeeks($i + 1)->startOfWeek();
                $end = now()->subWeeks($i)->endOfWeek();
                $periods[] = [
                    'label' => 'Week ' . (4 - $i),
                    'start' => $start,
                    'end' => $end
                ];
            }
        } elseif ($days <= 90) {
            // Last 3 months - monthly periods
            for ($i = 2; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $periods[] = [
                    'label' => $date->format('M'),
                    'start' => $date->startOfMonth(),
                    'end' => $date->endOfMonth()
                ];
            }
        } elseif ($days <= 180) {
            // Last 6 months - monthly periods
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $periods[] = [
                    'label' => $date->format('M'),
                    'start' => $date->startOfMonth(),
                    'end' => $date->endOfMonth()
                ];
            }
        } else {
            // Last year - monthly periods
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $periods[] = [
                    'label' => $date->format('M'),
                    'start' => $date->startOfMonth(),
                    'end' => $date->endOfMonth()
                ];
            }
        }
        
        return $periods;
    }

    public function getChartData(Request $request)
    {
        $days = $request->get('days', 180);
        $periods = $this->getDatePeriods($days);
        
        $chartData = [];
        foreach ($periods as $period) {
            $chartData[] = [
                'month' => $period['label'],
                'clinics' => Clinic::whereBetween('created_at', [$period['start'], $period['end']])->count(),
                'users' => User::whereBetween('created_at', [$period['start'], $period['end']])->count(),
                'revenue' => Subscription::whereBetween('created_at', [$period['start'], $period['end']])
                    ->where('status', 'active')
                    ->sum('amount')
            ];
        }
        
        return response()->json($chartData);
    }

    public function users(Request $request)
    {
        // Get all clinics with user counts for the cards
        $clinics = Clinic::withCount('users')->orderBy('name')->get();
        
        return view('superadmin.users.index', compact('clinics'));
    }
    
    /**
     * Show users for a specific clinic
     */
    public function clinicUsers(Clinic $clinic)
    {
        $clinic->load(['users' => function($query) {
            $query->with('roles')->orderBy('name');
        }]);
        
        $users = $clinic->users()->with('roles')->paginate(15);
        
        return view('superadmin.users.clinic', compact('clinic', 'users'));
    }
    
    /**
     * Show create user form
     */
    public function createUser(Request $request)
    {
        $clinics = Clinic::all();
        $roles = \Spatie\Permission\Models\Role::all();
        $selectedClinic = $request->get('clinic_id');
        
        return view('superadmin.users.create', compact('clinics', 'roles', 'selectedClinic'));
    }
    
    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'clinic_id' => 'nullable|exists:clinics,id'
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Model handles hashing via cast
            'clinic_id' => $request->clinic_id,
            'is_active' => true,
            'email_verified_at' => now() // Auto-verify manually added users
        ]);
        
        $user->assignRole($request->role);
    
    event(new Registered($user));
    
    return redirect()->route('superadmin.users')->with('success', 'User created successfully!');
    }
    
    /**
     * Show edit user form
     */
    public function editUser(User $user)
    {
        $clinics = Clinic::all();
        $roles = \Spatie\Permission\Models\Role::all();
        
        return view('superadmin.users.edit', compact('user', 'clinics', 'roles'));
    }
    
    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'clinic_id' => 'nullable|exists:clinics,id'
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'clinic_id' => $request->clinic_id
        ]);
        
        if ($request->password) {
            $user->update(['password' => $request->password]);
        }
        
        $user->syncRoles([$request->role]);
        
        return redirect()->route('superadmin.users')->with('success', 'User updated successfully!');
    }
    
    /**
     * Delete user
     */
    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account!');
        }
        
        $user->delete();
        
        return redirect()->route('superadmin.users')->with('success', 'User deleted successfully!');
    }
    
    public function toggleClinicStatus(Clinic $clinic)
    {
        $clinic->update(['is_active' => !$clinic->is_active]);
        return redirect()->back()->with('success', 'Clinic status updated successfully!');
    }

    public function systemSettings()
    {
        return view('superadmin.settings');
    }

    public function analytics()
    {
        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'clinics' => Clinic::whereMonth('created_at', '=', $date->month)
                    ->whereYear('created_at', '=', $date->year)
                    ->count(),
                'revenue' => Subscription::whereMonth('created_at', '=', $date->month)
                    ->whereYear('created_at', '=', $date->year)
                    ->where('status', 'active')
                    ->sum('amount')
            ];
        }

        return view('superadmin.analytics', compact('monthlyStats'));
    }

    public function landingPage()
    {
        $content = LandingPageContent::getContent();
        return view('superadmin.content.landing', compact('content'));
    }

    public function blogPosts()
    {
        $posts = \App\Models\BlogPost::latest()->paginate(15);
        return view('superadmin.content.blog', compact('posts'));
    }

    public function storeBlog(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|max:2048'
        ]);

        \App\Models\BlogPost::create($validated);

        return redirect()->back()->with('success', 'Blog post created successfully.');
    }

    public function testimonials()
    {
        $testimonials = \App\Models\Testimonial::orderBy('created_at', 'desc')->get();
        return view('superadmin.content.testimonials', compact('testimonials'));
    }

    public function updateLanding(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string',
            'hero_cta_primary' => 'required|string|max:255',
            'hero_cta_secondary' => 'required|string|max:255',
            'trust_clinics' => 'nullable|string|max:255',
            'trust_patients' => 'nullable|string|max:255',
            'trust_appointments' => 'nullable|string|max:255',
            'trust_uptime' => 'nullable|string|max:255',
            'trust_revenue' => 'nullable|string|max:255',
            'about_title' => 'required|string|max:255',
            'about_description' => 'required|string',
            'company_name' => 'nullable|string|max:255',
            'company_tagline' => 'nullable|string|max:255',
            'company_rating' => 'nullable|string|max:10',
            'company_description' => 'nullable|string',
            'contact_title' => 'required|string|max:255',
            'contact_subtitle' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_address' => 'nullable|string|max:255',
            'footer_description' => 'required|string',
            'footer_copyright' => 'required|string|max:255',
            'theme_primary_color' => 'required|string|max:7',
            'theme_secondary_color' => 'required|string|max:7',
            'theme_accent_color' => 'required|string|max:7',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hero_carousel_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'services' => 'nullable|array',
            'services.*.title' => 'nullable|string|max:255',
            'services.*.description' => 'nullable|string',
            'services.*.icon' => 'nullable|string|max:255',
            'testimonials' => 'nullable|array',
            'testimonials.*.name' => 'nullable|string|max:255',
            'testimonials.*.rating' => 'nullable|integer|min:1|max:5',
            'testimonials.*.designation' => 'nullable|string|max:255',
            'testimonials.*.review' => 'nullable|string',
            'faq' => 'nullable|array',
            'faq.*.question' => 'nullable|string',
            'faq.*.answer' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
        ]);

        $content = LandingPageContent::where('clinic_id', null)->first();
        if (!$content) {
            $content = new LandingPageContent();
            $content->clinic_id = null;
        }

        // Handle image uploads
        $imageFields = ['hero_image', 'about_image', 'company_logo'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/landing-images/global', $filename);
                $validated[$field] = $filename;
            }
        }
        
        // Handle hero carousel images
        if ($request->hasFile('hero_carousel_images')) {
            $carouselImages = [];
            foreach ($request->file('hero_carousel_images') as $index => $file) {
                $filename = time() . '_hero_carousel_' . $index . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/landing-images/global', $filename);
                $carouselImages[] = $filename;
            }
            $validated['hero_carousel_images'] = $carouselImages;
        }
        
        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $index => $file) {
                $filename = time() . '_gallery_' . $index . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/landing-images/global', $filename);
                $galleryImages[] = $filename;
            }
            $validated['gallery_images'] = $galleryImages;
        }
        
        // Handle dynamic data arrays
        if ($request->has('services')) {
            $validated['services_data'] = array_filter($request->input('services', []), function($service) {
                return !empty($service['title']) || !empty($service['description']);
            });
        }
        
        if ($request->has('testimonials')) {
            $validated['testimonials_data'] = array_filter($request->input('testimonials', []), function($testimonial) {
                return !empty($testimonial['name']) || !empty($testimonial['review']);
            });
        }
        
        if ($request->has('faq')) {
            $validated['faq_data'] = array_filter($request->input('faq', []), function($faq) {
                return !empty($faq['question']) || !empty($faq['answer']);
            });
        }

        // Remove the array keys that shouldn't be directly filled
        $fillableData = $validated;
        unset($fillableData['services'], $fillableData['testimonials'], $fillableData['faq']);
        
        $content->fill($fillableData);
        $content->save();

        return redirect()->back()->with('success', 'Landing page updated successfully!');
    }

    public function storeTestimonial(Request $request)
    {
        $validated = $request->validate([
            'doctor_name' => 'required|string|max:255',
            'clinic_name' => 'required|string|max:255',
            'testimonial' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5'
        ]);
        
        // Save testimonial to database
        \App\Models\Testimonial::create([
            'doctor_name' => $validated['doctor_name'],
            'clinic_name' => $validated['clinic_name'],
            'testimonial' => $validated['testimonial'],
            'rating' => $validated['rating']
        ]);
        
        return back()->with('success', 'Testimonial added successfully!');
    }
    public function debug()
    {
        return response()->json([
            'user' => \Illuminate\Support\Facades\Auth::user()->name,
            'roles' => \Illuminate\Support\Facades\Auth::user()->getRoleNames(),
            'is_superadmin' => \Illuminate\Support\Facades\Auth::user()->hasRole('superadmin')
        ]);
    }
}