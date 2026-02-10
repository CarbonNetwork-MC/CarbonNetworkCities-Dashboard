<?php

use Illuminate\Foundation\Application;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Middleware\RedirectIfOnboarded;

use App\Http\Middleware\EnsureOnboardingComplete;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureUserIsEmployeeOrOwner;
use App\Console\Commands\CleanupExpiredAccountLinkTokens;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['onboarding' => EnsureOnboardingComplete::class]);
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['redirect.onboarded' => RedirectIfOnboarded::class]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'employee_or_owner' => EnsureUserIsEmployeeOrOwner::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command(new CleanupExpiredAccountLinkTokens)->daily();
    })->create();
