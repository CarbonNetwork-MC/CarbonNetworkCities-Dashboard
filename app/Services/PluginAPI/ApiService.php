<?php

namespace App\Services\PluginAPI;

use Illuminate\Support\Facades\Http;

class ApiService
{
    public function post(string $endpoint): array
    {
        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . $endpoint);

        $requestId = $response->json('requestId');
        
        $success = app(InvalidationService::class)
                ->waitForInvalidationResult($requestId);

        return [$response->status(), $success];
    }
}