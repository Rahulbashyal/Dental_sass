<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DataPurgingService
{
    public function secureDelete($modelClass, $id)
    {
        DB::transaction(function () use ($modelClass, $id) {
            $model = $modelClass::findOrFail($id);
            
            // Log deletion
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'secure_delete',
                'model_type' => $modelClass,
                'model_id' => $id,
                'old_values' => $model->toArray(),
                'ip_address' => request()->ip(),
            ]);

            // Overwrite sensitive data before deletion
            if (method_exists($model, 'getEncryptedAttributes')) {
                foreach ($model->getEncryptedAttributes() as $field) {
                    $model->$field = str_repeat('X', 50);
                }
                $model->save();
            }

            $model->forceDelete();
        });
    }

    public function purgeOldAuditLogs($days = 2555) // 7 years
    {
        $cutoff = now()->subDays($days);
        
        AuditLog::where('created_at', '<', $cutoff)
               ->chunk(1000, function ($logs) {
                   foreach ($logs as $log) {
                       $log->delete();
                   }
               });
    }

    public function anonymizePatientData($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        
        $patient->update([
            'first_name' => 'ANONYMIZED',
            'last_name' => 'PATIENT',
            'email' => 'anonymized@example.com',
            'phone' => '000-000-0000',
            'address' => 'ANONYMIZED ADDRESS',
            'medical_history' => 'ANONYMIZED',
            'allergies' => 'ANONYMIZED',
            'insurance_number' => 'ANONYMIZED'
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'anonymize_patient',
            'model_type' => Patient::class,
            'model_id' => $patientId,
            'ip_address' => request()->ip(),
        ]);
    }
}