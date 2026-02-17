<?php

namespace App\Traits;

trait AnonymizesData
{
    /**
     * Anonymize sensitive data for logging and exports
     */
    public function anonymizeForLogging(array $data): array
    {
        $sensitiveFields = [
            'email', 'phone', 'address', 'medical_history', 'allergies',
            'insurance_number', 'emergency_contact', 'notes', 'password'
        ];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = $this->maskValue($data[$field]);
            }
        }

        return $data;
    }

    /**
     * Mask sensitive values
     */
    private function maskValue($value): string
    {
        if (empty($value)) return '';
        
        $length = strlen($value);
        if ($length <= 4) return str_repeat('*', $length);
        
        return substr($value, 0, 2) . str_repeat('*', $length - 4) . substr($value, -2);
    }

    /**
     * Remove PII completely for exports
     */
    public function removePiiForExport(array $data): array
    {
        $piiFields = [
            'email', 'phone', 'address', 'medical_history', 'allergies',
            'insurance_number', 'emergency_contact', 'notes'
        ];

        return array_diff_key($data, array_flip($piiFields));
    }
}