<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && !$user->player) {
            $user->update(['onboarding_status' => 1, 'onboarding_step' => 1]);
        }

        if ($user && $user->onboarding_status) {
            if (!$request->routeIs('onboarding.*')) {
                return redirect()->route('onboarding.render');
            }
        }

        return $next($request);
    }
}
