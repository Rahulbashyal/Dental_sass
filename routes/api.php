<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppointmentApiController;

// Public API routes (no auth required)
Route::get('/convert-date', function (Request $request) {
    $adDate = $request->get('ad');
    
    if (!$adDate) {
        return response()->json(['error' => 'AD date required'], 400);
    }
    
    // Validate date format (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $adDate)) {
        return response()->json(['error' => 'Invalid date format. Use YYYY-MM-DD'], 400);
    }
    
    // Validate date is real
    if (!strtotime($adDate)) {
        return response()->json(['error' => 'Invalid date'], 400);
    }
    
    try {
        $bsDate = \App\Helpers\NepaliDateHelper::convertADtoBS($adDate);
        $formatted = \App\Services\NepaliCalendarService::formatNepaliDate(
            $bsDate['year'],
            $bsDate['month'],
            $bsDate['day']
        );
        
        return response()->json([
            'bs_year' => $bsDate['year'],
            'bs_month' => $bsDate['month'],
            'bs_day' => $bsDate['day'],
            'bs_formatted' => $formatted
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Conversion failed'], 500);
    }
});

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::apiResource('appointments', AppointmentApiController::class)->names([
        'index' => 'api.appointments.index',
        'store' => 'api.appointments.store',
        'show' => 'api.appointments.show',
        'update' => 'api.appointments.update',
        'destroy' => 'api.appointments.destroy',
    ]);
    
    Route::get('/clinic/stats', function (Request $request) {
        $clinic = $request->user()->clinic;
        return response()->json([
            'total_patients' => $clinic->patients()->count(),
            'total_appointments' => $clinic->appointments()->count(),
            'todays_appointments' => $clinic->appointments()->whereDate('appointment_date', today())->count(),
        ]);
    });
});