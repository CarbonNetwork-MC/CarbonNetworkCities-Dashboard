<?php

namespace App\Services\PluginAPI;

use Illuminate\Support\Facades\Http;

class InvalidationService
{
    public function waitForInvalidationResult(string $requestId): bool {
        $statusUrl = config('services.plugin-api.url')
            . "api/invalidate/status/{$requestId}";

        $timeoutSeconds = 3;
        $pollIntervalMs = 300;

        $start = microtime(true);

        while ((microtime(true) - $start) < $timeoutSeconds) {
            $response = Http::withToken(config('services.plugin-api.key'))
                ->get($statusUrl);

            if ($response->failed()) {
                return false;
            }

            $state = $response->json('state');
            $results = $response->json('responses', []);

            if ($state === 'COMPLETED') {
                // Success if at least one server reloaded the plot
                foreach ($results as $server => $status) {
                    if ($status === 'RELOADED' || $status === 'REMOVED') {
                        return true;
                    }
                }

                // All responded, none succeeded
                return false;
            }

            usleep($pollIntervalMs * 1000);
        }

        // Timeout
        return false;
    }
}
