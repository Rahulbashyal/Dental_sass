<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class DatabaseOptimization
{
    public function handle(Request $request, Closure $next): Response
    {
        // Enable query caching for read operations
        if ($request->isMethod('GET')) {
            DB::enableQueryLog();
        }
        
        // Set connection timeout for long-running operations using parameterized query
        DB::statement('SET SESSION wait_timeout = ?', [30]);
        
        $response = $next($request);
        
        // Log slow queries for optimization
        if ($request->isMethod('GET')) {
            $queries = DB::getQueryLog();
            foreach ($queries as $query) {
                if ($query['time'] > 1000) { // Queries taking more than 1 second
                    \Log::warning('Slow query detected', [
                        'sql' => $query['query'],
                        'time' => $query['time'],
                        'bindings' => $query['bindings']
                    ]);
                }
            }
        }
        
        return $response;
    }
}