<?php

namespace App\Services\PluginAPI;

use Illuminate\Support\Facades\Http;

class ApiService
{
    public function post(string $endpoint, array $data = []): array
    {
        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . $endpoint, $data);

        if ($response->failed()) return [$response->status(), false];

        $requestId = $response->json('requestId');
        if (!$requestId) return [$response->status(), false];
        
        $success = app(InvalidationService::class)
                ->waitForInvalidationResult($requestId);

        return [$response->status(), $success];
    }
}