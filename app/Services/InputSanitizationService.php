<?php

namespace App\Services;

class InputSanitizationService
{
    /**
     * Sanitize string input
     */
    public static function sanitizeString(string $input, int $maxLength = 255): string
    {
        $sanitized = trim($input);
        $sanitized = strip_tags($sanitized);
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
        
        return substr($sanitized, 0, $maxLength);
    }

    /**
     * Sanitize email input
     */
    public static function sanitizeEmail(string $email): string
    {
        $email = trim(strtolower($email));
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        return $email ?: '';
    }

    /**
     * Sanitize phone number
     */
    public static function sanitizePhone(string $phone): string
    {
        // Remove all non-numeric characters except + - ( ) and spaces
        $phone = preg_replace('/[^0-9\+\-\(\)\s]/', '', $phone);
        
        return trim($phone);
    }

    /**
     * Sanitize numeric input
     */
    public static function sanitizeNumeric(string $input): int
    {
        return (int) filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Sanitize text area content
     */
    public static function sanitizeTextArea(string $input, int $maxLength = 5000): string
    {
        $sanitized = trim($input);
        $sanitized = strip_tags($sanitized, '<p><br><strong><em><ul><ol><li>');
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
        
        return substr($sanitized, 0, $maxLength);
    }

    /**
     * Sanitize array of strings
     */
    public static function sanitizeArray(array $input, int $maxLength = 255): array
    {
        return array_map(function ($item) use ($maxLength) {
            return is_string($item) ? self::sanitizeString($item, $maxLength) : $item;
        }, $input);
    }

    /**
     * Remove SQL injection patterns
     */
    public static function removeSqlInjectionPatterns(string $input): string
    {
        $patterns = [
            '/(\s|^)(union|select|insert|update|delete|drop|create|alter|exec|execute)(\s|$)/i',
            '/(\s|^)(or|and)(\s|$)(\d+)(\s|$)(=|<|>)(\s|$)(\d+)/i',
            '/(\s|^)(\'|"|\`)/i',
            '/(\s|^)(--|#|\/\*)/i',
        ];
        
        foreach ($patterns as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }
        
        return $input;
    }

    /**
     * Validate and sanitize file upload
     */
    public static function validateFileUpload($file, array $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'], int $maxSize = 10485760): array
    {
        if (!$file || !$file->isValid()) {
            return ['valid' => false, 'error' => 'Invalid file upload'];
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedTypes)) {
            return ['valid' => false, 'error' => 'File type not allowed'];
        }

        if ($file->getSize() > $maxSize) {
            return ['valid' => false, 'error' => 'File size too large'];
        }

        // Check for malicious content
        $filename = $file->getClientOriginalName();
        if (preg_match('/[<>:"\/\\|?*]/', $filename)) {
            return ['valid' => false, 'error' => 'Invalid filename'];
        }

        return ['valid' => true, 'file' => $file];
    }
}