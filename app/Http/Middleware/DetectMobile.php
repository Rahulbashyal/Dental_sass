<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DetectMobile
{
    public function handle(Request $request, Closure $next)
    {
        $userAgent = $request->header('User-Agent');
        
        $mobileKeywords = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone',
            'BlackBerry', 'webOS', 'Opera Mini', 'IEMobile'
        ];
        
        $isMobile = false;
        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                $isMobile = true;
                break;
            }
        }
        
        $request->attributes->set('is_mobile', $isMobile);
        
        return $next($request);
    }
}