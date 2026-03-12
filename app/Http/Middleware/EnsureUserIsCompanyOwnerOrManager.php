<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsCompanyOwnerOrManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) return redirect()->route('login');

        $companyId = $request->route('companyId');
        if (!$companyId) abort(400, 'Company ID is required');

        $user = auth()->user();
        $company = Company::find($companyId);
        if (!$company) abort(404, 'Company not found');

        if (
            $user->player->uuid !== $company->owner_uuid &&
            !$company->employees()->where('player_uuid', $user->player->uuid)->where('role', 'manager')->exists() &&
            !$user->hasRole('Superadmin')
        ) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
