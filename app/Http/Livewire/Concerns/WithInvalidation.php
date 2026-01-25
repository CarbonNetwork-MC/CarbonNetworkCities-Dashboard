<?php

namespace App\Http\Livewire\Concerns;

use App\Services\PluginAPI\InvalidationService;

trait WithInvalidation
{
    protected function waitForInvalidationResult(string $requestId): bool
    {
        return app(InvalidationService::class)
            ->waitForInvalidationResult($requestId);
    }
}