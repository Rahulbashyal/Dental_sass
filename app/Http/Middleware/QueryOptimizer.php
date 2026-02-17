<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryOptimizer
{
    public function handle(Request $request, Closure $next)
    {
        // Enable query caching
        DB::enableQueryLog();
        
        $response = $next($request);
        
        // Log slow queries for optimization
        $queries = DB::getQueryLog();
        foreach ($queries as $query) {
            if ($query['time'] > 100) { // Log queries taking more than 100ms
                \Log::warning('Slow query detected', [
                    'sql' => $query['query'],
                    'time' => $query['time'],
                    'bindings' => $query['bindings']
                ]);
            }
        }
        
        return $response;
    }
}