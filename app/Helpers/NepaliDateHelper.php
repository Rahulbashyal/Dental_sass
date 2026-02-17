<?php

namespace App\Helpers;

use App\Services\NepaliCalendarService;

class NepaliDateHelper
{
    public static function getCurrentNepaliDate()
    {
        return NepaliCalendarService::getCurrentNepaliDate();
    }
    
    public static function convertToNepaliDate($englishDate)
    {
        return NepaliCalendarService::englishToNepali($englishDate);
    }
    
    /**
     * Convert AD date to BS date
     * @param string $adDate Date in Y-m-d format
     * @return array|null BS date array with year, month, day
     */
    public static function convertADtoBS($adDate)
    {
        if (!$adDate) return null;
        
        try {
            $nepaliDate = NepaliCalendarService::englishToNepali($adDate);
            return [
                'year' => $nepaliDate['year'],
                'month' => $nepaliDate['month'],
                'day' => $nepaliDate['day']
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Convert BS date to AD date
     * @param int $year BS year
     * @param int $month BS month  
     * @param int $day BS day
     * @return string|null AD date in Y-m-d format
     */
    public static function convertBStoAD($year, $month, $day)
    {
        try {
            return NepaliCalendarService::nepaliToEnglish($year, $month, $day);
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public static function formatNepaliDate($date, $format = 'full')
    {
        $nepaliDate = self::convertToNepaliDate($date);
        
        switch ($format) {
            case 'short':
                return $nepaliDate['short'] ?? ($nepaliDate['day'] . ' ' . self::getMonthName($nepaliDate['month']));
            case 'medium':
                return $nepaliDate['medium'] ?? ($nepaliDate['day'] . ' ' . self::getMonthName($nepaliDate['month']) . ' ' . $nepaliDate['year']);
            case 'full':
            default:
                return $nepaliDate['formatted'] ?? $nepaliDate['formatted'];
        }
    }
    
    private static function getMonthName($monthNumber)
    {
        $months = [
            1 => 'बैसाख', 2 => 'जेठ', 3 => 'असार', 4 => 'श्रावण',
            5 => 'भाद्र', 6 => 'आश्विन', 7 => 'कार्तिक', 8 => 'मंसिर',
            9 => 'पुस', 10 => 'माघ', 11 => 'फाग्गुन', 12 => 'चैत्र'
        ];
        return $months[$monthNumber] ?? 'मंसिर';
    }
}