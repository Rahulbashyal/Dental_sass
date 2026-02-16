<?php

namespace App\Core\Modular;

use Illuminate\Support\Facades\File;

class ModuleLoader
{
    /**
     * Get all available modules in the Modules directory.
     */
    public static function getAvailableModules(): array
    {
        $path = base_path('Modules');
        if (!File::exists($path)) {
            return [];
        }

        return collect(File::directories($path))
            ->map(function ($directory) {
                return basename($directory);
            })
            ->toArray();
    }

    /**
     * Sync discovered modules with the database registry.
     */
    public static function syncModules()
    {
        try {
            $discovered = self::getAvailableModules();
            
            foreach ($discovered as $name) {
                \App\Models\Module::firstOrCreate(
                    ['name' => $name],
                    [
                        'display_name' => $name,
                        'is_active' => true,
                        'is_core' => false
                    ]
                );
            }
        } catch (\Exception $e) {
            // Silently fail during boot if database is not reachable
            \Illuminate\Support\Facades\Log::warning('Module syncing failed: ' . $e->getMessage());
        }
    }

    /**
     * Load routes and providers for discovered modules.
     */
    public static function bootModules()
    {
        // 1. Discovery & Sync
        $discovered = self::getAvailableModules();
        
        // 2. Load globally active modules from DB
        try {
            // We use a simple query to avoid recursive boot issues if possible
            $activeModules = \App\Models\Module::whereIn('name', $discovered)
                ->where('is_active', true)
                ->pluck('name')
                ->toArray();
        } catch (\Exception $e) {
            // Fallback during migrations or if table doesn't exist
            $activeModules = [];
        }

        foreach ($activeModules as $module) {
            $providerClass = "Modules\\$module\\Providers\\{$module}ServiceProvider";
            
            if (class_exists($providerClass)) {
                app()->register($providerClass);
            }
        }
    }
}
