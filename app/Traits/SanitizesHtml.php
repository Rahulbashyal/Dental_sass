<?php

namespace App\Traits;

trait SanitizesHtml
{
    /**
     * Basic HTML sanitization to prevent XSS.
     * In a production environment, use a library like mews/purifier.
     */
    protected function sanitizeHtml(?string $html): ?string
    {
        if (empty($html)) {
            return $html;
        }

        // Allowed tags for dental templates
        $allowedTags = '<p><br><b><strong><i><em><u><ul><ol><li><h1><h2><h3><h4><h5><h6><table><thead><tbody><tr><td><th>';
        
        $sanitized = strip_tags($html, $allowedTags);
        
        // Remove potentially dangerous attributes like onclick, onerror, etc.
        return preg_replace('/(on\w+)\s*=\s*["\'][^"\']*["\']/i', '', $sanitized);
    }
}
