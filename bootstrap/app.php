<?php

use App\Console\Commands\CleanupExpiredAccountLinkTokens;
use App\Console\Commands\CleanupOldCompanyNotifications;
use App\Http\Middleware\EnsureOnboardingComplete;
use App\Http\Middleware\EnsureUserHasCompanies;
use App\Http\Middleware\EnsureUserIsCompanyOwnerOrManager;
use App\Http\Middleware\EnsureUserIsEmployeeOrOwner;
use App\Http\Middleware\RedirectIfOnboarded;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'company_owner_or_manager' => EnsureUserIsCompanyOwnerOrManager::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'has_companies' => EnsureUserHasCompanies::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command(new CleanupExpiredAccountLinkTokens)->daily();
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command(new CleanupOldCompanyNotifications())->daily();
    })->create();
