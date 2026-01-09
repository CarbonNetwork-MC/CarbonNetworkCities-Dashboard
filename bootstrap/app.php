<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Console\Commands\CleanupExpiredAccountLinkTokens;
use App\Http\Middleware\EnsureOnboardingComplete;
use App\Http\Middleware\RedirectIfOnboarded;
use Illuminate\Console\Scheduling\Schedule;

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
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command(new CleanupExpiredAccountLinkTokens)->daily();
    })->create();
