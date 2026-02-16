<?php

namespace App\Http\Controllers;

use App\Models\LandingPageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClinicAdminController extends Controller
{
    public function dashboard(\App\Services\AiInsightService $aiService)
    {
        $aiInsights = $aiService->generateClinicInsights();
        return view('clinic-admin.dashboard', compact('aiInsights'));
    }

    public function landingPage()
    {
        return redirect()->route('clinic.system-settings.index', ['tab' => 'landing-page']);
    }

    public function updateLandingPage(Request $request)
    {
        return (new SystemSettingsController())->updateLandingPage($request);
    }
}