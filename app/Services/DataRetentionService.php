<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Patient;
use Carbon\Carbon;

class DataRetentionService
{
    public function cleanupOldData()
    {
        $this->cleanupAuditLogs();
        $this->cleanupInactivePatients();
        $this->cleanupTempFiles();
    }

    private function cleanupAuditLogs()
    {
        // Keep audit logs for 7 years (HIPAA requirement)
        $cutoffDate = Carbon::now()->subYears(7);
        
        AuditLog::where('created_at', '<', $cutoffDate)->delete();
    }

    private function cleanupInactivePatients()
    {
        // Archive patients with no activity for 10 years
        $cutoffDate = Carbon::now()->subYears(10);
        
        Patient::whereDoesntHave('appointments', function($query) use ($cutoffDate) {
            $query->where('created_at', '>', $cutoffDate);
        })->update(['is_active' => false]);
    }

    private function cleanupTempFiles()
    {
        $tempPath = storage_path('app/temp');
        if (!is_dir($tempPath)) return;

        $files = glob($tempPath . '/*');
        $cutoffTime = time() - (24 * 60 * 60); // 24 hours

        foreach ($files as $file) {
            if (filemtime($file) < $cutoffTime) {
                unlink($file);
            }
        }
    }

    public function exportPatientData($patientId): array
    {
        $patient = Patient::with(['appointments', 'treatmentPlans', 'invoices'])
                          ->findOrFail($patientId);

        return [
            'patient_info' => $patient->toArray(),
            'appointments' => $patient->appointments->toArray(),
            'treatment_plans' => $patient->treatmentPlans->toArray(),
            'invoices' => $patient->invoices->toArray(),
            'exported_at' => now()->toISOString()
        ];
    }
}