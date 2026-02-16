<?php

namespace Modules\MultiBranch\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Appointment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExecutiveController extends Controller
{
    public function dashboard()
    {
        $branches = Branch::all();
        
        $stats = [
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
            'total_appointments' => Appointment::count(),
            'branch_performance' => $branches->map(function ($branch) {
                return [
                    'name' => $branch->name,
                    'revenue' => Invoice::whereHas('appointment', function($q) use ($branch) {
                        $q->where('branch_id', $branch->id);
                    })->where('status', 'paid')->sum('total_amount'),
                    'appointments' => Appointment::where('branch_id', $branch->id)->count(),
                ];
            }),
        ];

        return view('multibranch::analytics.dashboard', compact('stats', 'branches'));
    }

    public function comparison()
    {
        $branches = Branch::withCount(['users'])->get();
        
        // Complex aggregation for comparison
        $branchComparison = $branches->map(function ($branch) {
            return [
                'branch' => $branch,
                'revenue' => Invoice::whereHas('appointment', function($q) use ($branch) {
                    $q->where('branch_id', $branch->id);
                })->where('status', 'paid')->sum('total_amount'),
                'avg_ticket' => Invoice::whereHas('appointment', function($q) use ($branch) {
                    $q->where('branch_id', $branch->id);
                })->where('status', 'paid')->avg('total_amount') ?? 0,
                'cancel_rate' => Appointment::where('branch_id', $branch->id)->count() > 0 
                    ? (Appointment::where('branch_id', $branch->id)->where('status', 'cancelled')->count() / Appointment::where('branch_id', $branch->id)->count()) * 100 
                    : 0,
            ];
        });

        return view('multibranch::analytics.comparison', compact('branchComparison'));
    }
}
