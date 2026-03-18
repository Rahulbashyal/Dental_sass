<?php

namespace App\Services;

use App\Models\UiTemplate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ThemeManagerService
{
    protected $templatesPath;

    public function __construct()
    {
        $this->templatesPath = resource_path('views/clinic/templates');
    }

    /**
     * Discover and sync templates from the file system to the database.
     */
    public function syncTemplates()
    {
        if (!File::exists($this->templatesPath)) {
            File::makeDirectory($this->templatesPath, 0755, true);
        }

        $directories = File::directories($this->templatesPath);
        $discoveredSlugs = [];

        foreach ($directories as $directory) {
            $slug = basename($directory);
            $manifestPath = $directory . '/manifest.json';

            if (File::exists($manifestPath)) {
                try {
                    $manifest = json_decode(File::get($manifestPath), true);
                    $this->registerTemplate($slug, $manifest);
                    $discoveredSlugs[] = $slug;
                } catch (\Exception $e) {
                    Log::error("Failed to register template {$slug}: " . $e->getMessage());
                }
            }
        }

        return count($discoveredSlugs);
    }

    /**
     * Register or update a template in the database.
     */
    protected function registerTemplate(string $slug, array $manifest)
    {
        UiTemplate::updateOrCreate(
            ['slug' => $slug],
            [
                'name' => $manifest['name'] ?? ucfirst($slug),
                'description' => $manifest['description'] ?? '',
                'preview_image' => $manifest['preview_image'] ?? null,
                'config' => $manifest['config'] ?? [],
            ]
        );
    }

    /**
     * Get all active templates.
     */
    public function getActiveTemplates()
    {
        return UiTemplate::where('is_active', true)->get();
    }

    /**
     * Resolve the view path for a template.
     */
    public function getTemplateView(string $slug, bool $force = false)
    {
        $template = UiTemplate::where('slug', $slug)->first();
        
        if (($force || ($template && $template->is_active)) && File::exists($this->templatesPath . '/' . $slug . '/index.blade.php')) {
            return "clinic.templates.{$slug}.index";
        }

        return 'clinic.templates.default.index'; // Use default node as fallback
    }
    /**
     * Get default custom sections for a template based on manifest cms_fields.
     */
    public function getThemeDefaults(string $slug)
    {
        $template = UiTemplate::where('slug', $slug)->first();
        if (!$template || empty($template->config['cms_fields'])) {
            return [];
        }

        $defaults = [];
        foreach ($template->config['cms_fields'] as $field) {
            $defaults[$field['name']] = $field['default'] ?? null;
        }

        return $defaults;
    }
}
