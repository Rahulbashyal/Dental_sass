<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'clinic.access' => \App\Http\Middleware\EnsureClinicAccess::class,
            'clinic.config' => \App\Http\Middleware\EnforceClinicConfiguration::class,
            'secure.rate' => \App\Http\Middleware\SecureRateLimit::class,
            'advanced.rate' => \App\Http\Middleware\AdvancedRateLimit::class,
            'request.queue' => \App\Http\Middleware\RequestQueue::class,
            'query.optimizer' => \App\Http\Middleware\QueryOptimizer::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,  // Use Spatie's role middleware
            'role.strict' => \App\Http\Middleware\EnsureRoleAccess::class,
            'security.validation' => \App\Http\Middleware\SecurityValidation::class,
            'csrf' => \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            'force.https' => \App\Http\Middleware\ForceHttps::class,
            'verified' => \App\Http\Middleware\EnsureEmailVerified::class,
            'resource.owner' => \App\Http\Middleware\ValidateResourceOwnership::class,
            '2fa' => \App\Http\Middleware\TwoFactorAuth::class,
            'api.rate' => \App\Http\Middleware\ApiRateLimit::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'auto.verify.admins' => \App\Http\Middleware\AutoVerifyAdmins::class,
        ]);
        
        $middleware->web([
            // \App\Http\Middleware\ForceHttps::class,  // Disabled for local development
            \App\Http\Middleware\SecurityHeaders::class,
            // \App\Http\Middleware\SecurityValidation::class,  // Disabled for local development
            \Illuminate\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();