<?php

namespace App\Console\Commands;

use App\Services\ThemeManagerService;
use Illuminate\Console\Command;

class SyncUiTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ui:sync-templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Discover and sync UI templates from the file system to the database';

    /**
     * Execute the console command.
     */
    public function handle(ThemeManagerService $themeManager)
    {
        $this->info('Discovering UI templates...');
        $count = $themeManager->syncTemplates();
        $this->info("Successfully synced {$count} templates.");
    }
}
