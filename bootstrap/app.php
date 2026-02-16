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
        $middleware->trustProxies(at: '*');
        
        $middleware->alias([
            'clinic.access' => \App\Http\Middleware\EnsureClinicAccess::class,
            'clinic.config' => \App\Http\Middleware\EnforceClinicConfiguration::class,
            'secure.rate' => \App\Http\Middleware\SecureRateLimit::class,
            'advanced.rate' => \App\Http\Middleware\AdvancedRateLimit::class,
            'request.queue' => \App\Http\Middleware\RequestQueue::class,
            'query.optimizer' => \App\Http\Middleware\QueryOptimizer::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'role.strict' => \App\Http\Middleware\EnsureRoleAccess::class,
            'security.validation' => \App\Http\Middleware\SecurityValidation::class,
            'force.https' => \App\Http\Middleware\ForceHttps::class,
            'verified' => \App\Http\Middleware\EnsureEmailVerified::class,
            'resource.owner' => \App\Http\Middleware\ValidateResourceOwnership::class,
            '2fa' => \App\Http\Middleware\TwoFactorAuth::class,
            'api.rate' => \App\Http\Middleware\ApiRateLimit::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'auto.verify.admins' => \App\Http\Middleware\AutoVerifyAdmins::class,
            'module.enabled' => \App\Http\Middleware\CheckModuleEnabled::class,
        ]);
        
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();