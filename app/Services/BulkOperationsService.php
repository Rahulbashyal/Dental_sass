<?php

namespace App\Services;

use App\Models\{Patient, Appointment, Invoice};
use Illuminate\Support\Facades\DB;

class BulkOperationsService
{
    public function bulkUpdatePatients($patientIds, $updates): int
    {
        return DB::transaction(function () use ($patientIds, $updates) {
            $updated = Patient::whereIn('id', $patientIds)->update($updates);
            
            foreach ($patientIds as $id) {
                \App\Models\AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'bulk_update',
                    'model_type' => Patient::class,
                    'model_id' => $id,
                    'new_values' => $updates,
                    'ip_address' => request()->ip(),
                ]);
            }
            
            return $updated;
        });
    }

    public function bulkDeleteAppointments($appointmentIds): int
    {
        return DB::transaction(function () use ($appointmentIds) {
            $appointments = Appointment::whereIn('id', $appointmentIds)->get();
            
            foreach ($appointments as $appointment) {
                \App\Models\AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'bulk_delete',
                    'model_type' => Appointment::class,
                    'model_id' => $appointment->id,
                    'old_values' => $appointment->toArray(),
                    'ip_address' => request()->ip(),
                ]);
            }
            
            return Appointment::whereIn('id', $appointmentIds)->delete();
        });
    }

    public function bulkInvoiceActions($invoiceIds, $action): int
    {
        return DB::transaction(function () use ($invoiceIds, $action) {
            $updates = match($action) {
                'mark_paid' => ['status' => 'paid', 'paid_at' => now()],
                'mark_overdue' => ['status' => 'overdue'],
                'send_reminders' => ['reminder_sent_at' => now()],
                default => []
            };
            
            if (empty($updates)) return 0;
            
            $updated = Invoice::whereIn('id', $invoiceIds)->update($updates);
            
            foreach ($invoiceIds as $id) {
                \App\Models\AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => "bulk_{$action}",
                    'model_type' => Invoice::class,
                    'model_id' => $id,
                    'new_values' => $updates,
                    'ip_address' => request()->ip(),
                ]);
            }
            
            return $updated;
        });
    }

    public function bulkExport($model, $ids, $format = 'csv'): string
    {
        $data = $model::whereIn('id', $ids)->get();
        
        return match($format) {
            'csv' => $this->exportToCsv($data),
            'json' => $data->toJson(),
            'xml' => $this->exportToXml($data),
            default => $data->toJson()
        };
    }

    private function exportToCsv($data): string
    {
        if ($data->isEmpty()) return '';
        
        $csv = implode(',', array_keys($data->first()->toArray())) . "\n";
        
        foreach ($data as $row) {
            $csv .= implode(',', array_values($row->toArray())) . "\n";
        }
        
        return $csv;
    }

    private function exportToXml($data): string
    {
        $xml = new \SimpleXMLElement('<data/>');
        
        foreach ($data as $item) {
            $record = $xml->addChild('record');
            foreach ($item->toArray() as $key => $value) {
                $record->addChild($key, htmlspecialchars($value));
            }
        }
        
        return $xml->asXML();
    }
}