<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['module.enabled:CoreDental'])->group(function () {
    Route::get('/module-test', function () {
        return response()->json([
            'message' => 'Hello from CoreDental Module!',
            'tenant' => tenant('id')
        ]);
    });
});
