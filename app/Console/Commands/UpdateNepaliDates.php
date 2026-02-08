<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NepaliCalendarService;
use Illuminate\Support\Facades\Cache;

class UpdateNepaliDates extends Command
{
    protected $signature = 'nepali:update-dates';
    protected $description = 'Update Nepali calendar dates and cache current date';

    public function handle()
    {
        $this->info('Updating Nepali calendar dates...');
        
        // Clear any cached Nepali dates
        Cache::forget('nepali_current_date');
        Cache::forget('nepali_calendar_widget');
        
        // Get fresh current Nepali date
        $currentNepaliDate = NepaliCalendarService::getCurrentNepaliDate();
        
        // Cache the current date for 1 hour
        Cache::put('nepali_current_date', $currentNepaliDate, 3600);
        
        // Generate and cache calendar widget
        $calendarWidget = NepaliCalendarService::generateCalendarWidget();
        Cache::put('nepali_calendar_widget', $calendarWidget, 3600);
        
        $this->info('Nepali dates updated successfully!');
        $this->info('Current Nepali Date: ' . $currentNepaliDate['formatted']);
        
        return 0;
    }
}