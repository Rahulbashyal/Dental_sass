<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) < 12) {
            $fail('Password must be at least 12 characters long.');
        }
        
        if (!preg_match('/[A-Z]/', $value)) {
            $fail('Password must contain at least one uppercase letter.');
        }
        
        if (!preg_match('/[a-z]/', $value)) {
            $fail('Password must contain at least one lowercase letter.');
        }
        
        if (!preg_match('/[0-9]/', $value)) {
            $fail('Password must contain at least one number.');
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $value)) {
            $fail('Password must contain at least one special character.');
        }
    }
}
