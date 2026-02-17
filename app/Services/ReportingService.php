<?php

namespace App\Services;

use App\Models\{Patient, Appointment, Invoice};
use Illuminate\Support\Facades\DB;

class ReportingService
{
    public function generateCustomReport($filters): array
    {
        $query = $this->buildQuery($filters);
        
        return [
            'data' => $query->get(),
            'summary' => $this->generateSummary($query),
            'charts' => $this->generateChartData($query),
            'generated_at' => now()->toISOString()
        ];
    }

    private function buildQuery($filters)
    {
        $model = match($filters['type']) {
            'patients' => Patient::query(),
            'appointments' => Appointment::query(),
            'revenue' => Invoice::query(),
            default => Patient::query()
        };

        if (isset($filters['clinic_id'])) {
            $model->where('clinic_id', $filters['clinic_id']);
        }

        if (isset($filters['date_from'])) {
            $model->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $model->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $model;
    }

    private function generateSummary($query): array
    {
        return [
            'total_records' => $query->count(),
            'avg_per_day' => $query->count() / 30,
            'growth_rate' => $this->calculateGrowthRate($query)
        ];
    }

    private function generateChartData($query): array
    {
        return [
            'daily_trend' => $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'monthly_summary' => $query->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->get()
        ];
    }

    private function calculateGrowthRate($query): float
    {
        $thisMonth = $query->whereMonth('created_at', date('m'))->count();
        $lastMonth = $query->whereMonth('created_at', date('m') - 1)->count();
        
        return $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;
    }
}