<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RequestQueue
{
    public function handle(Request $request, Closure $next)
    {
        $queueKey = 'request_queue_' . $request->ip();
        $currentQueue = Cache::get($queueKey, 0);
        
        if ($currentQueue > 10) {
            return response()->json([
                'error' => 'Server busy. Please wait and try again.',
                'queue_position' => $currentQueue
            ], 503);
        }
        
        Cache::increment($queueKey, 1, 60);
        
        $response = $next($request);
        
        Cache::decrement($queueKey, 1);
        
        return $response;
    }
}