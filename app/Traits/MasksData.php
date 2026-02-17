<?php

namespace App\Traits;

trait MasksData
{
    /**
     * Mask sensitive data based on user role
     */
    public function maskSensitiveData(array $data, string $userRole): array
    {
        $restrictedRoles = ['receptionist', 'accountant'];
        
        if (!in_array($userRole, $restrictedRoles)) {
            return $data; // Full access for dentist, clinic_admin, superadmin
        }

        $sensitiveFields = [
            'medical_history' => 'Medical history restricted',
            'allergies' => 'Allergy info restricted',
            'insurance_number' => $this->maskInsurance($data['insurance_number'] ?? ''),
            'emergency_contact' => $this->maskPhone($data['emergency_contact'] ?? '')
        ];

        foreach ($sensitiveFields as $field => $maskedValue) {
            if (isset($data[$field])) {
                $data[$field] = $maskedValue;
            }
        }

        return $data;
    }

    private function maskInsurance($value): string
    {
        if (empty($value)) return '';
        return '****-****-' . substr($value, -4);
    }

    private function maskPhone($value): string
    {
        if (empty($value)) return '';
        return '***-***-' . substr($value, -4);
    }
}