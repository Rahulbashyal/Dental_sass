<?php

namespace App\Helpers;

class LayoutHelper
{
    public static function getLayoutForUser($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return 'layouts.app';
        }
        
        if ($user->hasRole('superadmin')) {
            return 'layouts.superadmin';
        }
        
        if ($user->hasRole('receptionist')) {
            return 'layouts.receptionist';
        }
        
        if ($user->hasRole('accountant')) {
            return 'layouts.accountant';
        }
        
        if ($user->hasRole('dentist')) {
            return 'layouts.dentist';
        }
        
        // Default to app layout for other roles
        return 'layouts.app';
    }
}