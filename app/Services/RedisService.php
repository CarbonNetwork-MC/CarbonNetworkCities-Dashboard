<?php
namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class RedisService {
    public function invalidate(string $type, string $entityId): string {
        $requestId = (string) Str::uuid();

        Redis::connection()
            ->client()
            ->executeRaw([
                'XADD',
                'carbon:sync',
                '*',
                'type', $type,
                'requestId', $requestId,
                'entityId', $entityId,
            ]);

        return $requestId;
    }
}