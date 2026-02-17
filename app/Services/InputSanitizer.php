<?php

namespace App\Services;

class InputSanitizer
{
    public static function sanitizeHtml($input): string
    {
        if (empty($input)) return '';
        
        // Remove dangerous tags and attributes
        $input = strip_tags($input, '<p><br><strong><em><ul><ol><li>');
        
        // Remove javascript and other dangerous content
        $input = preg_replace('/javascript:/i', '', $input);
        $input = preg_replace('/on\w+\s*=/i', '', $input);
        
        return trim($input);
    }

    public static function sanitizeMedicalData($input): string
    {
        if (empty($input)) return '';
        
        // Allow medical terminology but remove dangerous content
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        $input = preg_replace('/[<>]/', '', $input);
        
        return trim($input);
    }

    public static function sanitizePhoneNumber($phone): string
    {
        if (empty($phone)) return '';
        
        // Remove all non-numeric characters except + and -
        $phone = preg_replace('/[^\d\+\-\(\)\s]/', '', $phone);
        
        return trim($phone);
    }

    public static function sanitizeEmail($email): string
    {
        if (empty($email)) return '';
        
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL) ?: '';
    }

    public static function sanitizeArray(array $data, array $rules = []): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (isset($rules[$key])) {
                $sanitized[$key] = self::applySanitizationRule($value, $rules[$key]);
            } else {
                $sanitized[$key] = is_string($value) ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : $value;
            }
        }
        
        return $sanitized;
    }

    private static function applySanitizationRule($value, string $rule)
    {
        return match($rule) {
            'html' => self::sanitizeHtml($value),
            'medical' => self::sanitizeMedicalData($value),
            'phone' => self::sanitizePhoneNumber($value),
            'email' => self::sanitizeEmail($value),
            default => htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
        };
    }
}