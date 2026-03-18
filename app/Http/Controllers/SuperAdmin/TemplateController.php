<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\UiTemplate;
use App\Services\ThemeManagerService;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    protected $themeManager;

    public function __construct(ThemeManagerService $themeManager)
    {
        $this->themeManager = $themeManager;
    }

    public function index()
    {
        $templates = UiTemplate::all();
        return view('admin.templates.index', compact('templates'));
    }

    public function sync()
    {
        $count = $this->themeManager->syncTemplates();
        return redirect()->route('superadmin.templates.index')
            ->with('success', "Found and synced {$count} templates from file system.");
    }

    public function toggle($id)
    {
        $template = UiTemplate::findOrFail($id);
        
        if ($template->slug === 'default') {
            return redirect()->route('superadmin.templates.index')
                ->with('error', "The default template cannot be disabled.");
        }

        $template->update(['is_active' => !$template->is_active]);

        $status = $template->is_active ? 'enabled' : 'disabled';
        return redirect()->route('superadmin.templates.index')
            ->with('success', "Template '{$template->name}' has been {$status}.");
    }

    public function preview($slug)
    {
        // For preview, we use the first available clinic and its content
        // Or create mock data if none exists
        $clinic = \App\Models\Clinic::first() ?? new \App\Models\Clinic([
            'name' => 'Demo Dental Clinic',
            'slug' => 'demo-clinic',
            'email' => 'demo@example.com',
            'address' => '123 Health Street, Medical District',
            'phone' => '+977-1-4444444',
            'show_team_menu' => true,
            'show_services_menu' => true,
            'show_gallery_menu' => true,
            'show_contact_menu' => true,
            'show_booking_button' => true,
        ]);

        $content = \App\Models\LandingPageContent::where('clinic_id', $clinic->id)->first() ?? new \App\Models\LandingPageContent([
            'hero_title' => 'Sample Clinic Title',
            'hero_subtitle' => 'This is how your theme looks with actual content.',
        ]);
        
        // Ensure some arrays exist for the template if they use foreach
        $services = property_exists($content, 'services_data') ? ($content->services_data ?? []) : [];
        $testimonials = property_exists($content, 'testimonials_data') ? ($content->testimonials_data ?? []) : [];
        
        $view = $this->themeManager->getTemplateView($slug, true); // Force true for preview

        return view($view, compact('clinic', 'content', 'services', 'testimonials'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'theme_zip' => 'required|file|mimes:zip|max:10240', // max 10MB
        ]);

        $zipPath = $request->file('theme_zip')->getRealPath();
        $zip = new \ZipArchive;

        if ($zip->open($zipPath) === true) {
            // Create a temporary directory for extraction
            $tempDir = storage_path('app/temp/theme_' . uniqid());
            \Illuminate\Support\Facades\File::makeDirectory($tempDir, 0755, true);
            
            $zip->extractTo($tempDir);
            $zip->close();

            // Find the directory containing manifest.json.
            // Some zip files wrap the contents in a parent folder.
            $manifestPath = null;
            $themeDir = null;

            $allFiles = \Illuminate\Support\Facades\File::allFiles($tempDir);
            foreach ($allFiles as $file) {
                if ($file->getFilename() === 'manifest.json') {
                    $manifestPath = $file->getPathname();
                    $themeDir = $file->getPath();
                    break;
                }
            }

            if (!$manifestPath) {
                \Illuminate\Support\Facades\File::deleteDirectory($tempDir);
                return redirect()->back()->with('error', 'Invalid theme format: manifest.json not found in the ZIP archive.');
            }

            // Read the manifest to get properties and validate
            try {
                $manifest = json_decode(\Illuminate\Support\Facades\File::get($manifestPath), true);
                if (!isset($manifest['name'])) {
                    throw new \Exception('Manifest must define at least a "name".');
                }

                // Check for index.blade.php
                if (!\Illuminate\Support\Facades\File::exists($themeDir . '/index.blade.php')) {
                    throw new \Exception('Theme must contain index.blade.php');
                }

                // Generate a slug from the theme name if the folder name is generic,
                // otherwise use the folder name containing the manifest.
                $slug = \Illuminate\Support\Str::slug($manifest['name']);
                
                $destinationPath = resource_path('views/clinic/templates/' . $slug);

                // Check if theme already exists
                if (\Illuminate\Support\Facades\File::exists($destinationPath)) {
                    \Illuminate\Support\Facades\File::deleteDirectory($tempDir);
                    return redirect()->back()->with('error', "Theme slug '{$slug}' already exists. Please delete it first or rename the uploaded theme.");
                }

                // Move the theme directory to the templates folder
                \Illuminate\Support\Facades\File::moveDirectory($themeDir, $destinationPath);

                // Clean up the temporary directory
                \Illuminate\Support\Facades\File::deleteDirectory($tempDir);

                // Run the sync to register it immediately
                $this->themeManager->syncTemplates();

                return redirect()->back()->with('success', "Theme '{$manifest['name']}' uploaded and registered successfully.");

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\File::deleteDirectory($tempDir);
                return redirect()->back()->with('error', 'Invalid theme structure: ' . $e->getMessage());
            }

        } else {
            return redirect()->back()->with('error', 'Failed to open the ZIP file. Please ensure it is a valid archive.');
        }
    }

    public function downloadSample()
    {
        $defaultThemeDir = resource_path('views/clinic/templates/default');
        
        if (!\Illuminate\Support\Facades\File::exists($defaultThemeDir)) {
            return redirect()->back()->with('error', 'Sample theme not found.');
        }

        $zipPath = storage_path('app/temp/sample_theme.zip');
        
        // Ensure temp directory exists
        \Illuminate\Support\Facades\File::ensureDirectoryExists(storage_path('app/temp'));

        $zip = new \ZipArchive;
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $files = \Illuminate\Support\Facades\File::allFiles($defaultThemeDir);
            foreach ($files as $file) {
                // Add file to ZIP with relative path
                $zip->addFile($file->getRealPath(), $file->getRelativePathname());
            }
            $zip->close();
            
            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return redirect()->back()->with('error', 'Could not create sample ZIP file.');
    }
}
