<?php
namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class RedisService {
    public function invalidate(string $type, string $entityId): bool {
        $requestId = $this->sendInvalidateRequest($type, $entityId);
        $success = $this->waitForResults($requestId, config('app.cities_servers'));
        if (!$success) {
            $this->sendInvalidateRequest($type, $entityId);
            return false;
        }

        return true;
    }

    private function sendInvalidateRequest(string $type, string $entityId): string {
        $requestId = (string) Str::uuid();

        Redis::connection()
            ->client()
            ->executeRaw([
                'XADD',
                config('database.redis.default.stream', 'carbon-network'),
                'MAXLEN', '~', '10000',
                '*',
                'type', $type,
                'requestId', $requestId,
                'server', 'web',
                'timestamp', now()->toIso8601String(),
                'entityId', $entityId,
            ]);

        return $requestId;
    }

    private function waitForResults(
        string $requestId,
        int $expectedServers = 1
    ): bool {
        $servers = [];
        $timeout = 5;
        $start = microtime(true);

        while (microtime(true) - $start < $timeout) {
            $results = Redis::connection()
                ->client()
                ->executeRaw([
                    'XRANGE',
                    config('database.redis.default.stream', 'carbon-network'),
                    '-',
                    '+',
                    'COUNT',
                    100,
                ]);

            foreach ($results as $entry) {
                $fields = $entry[1];

                $data = [];

                if (array_is_list($fields)) {
                    for ($i = 0; $i < count($fields) - 1; $i += 2) {
                        $data[$fields[$i]] = $fields[$i + 1] ?? null;
                    }
                } else {
                    $data = $fields;
                }

                if (($data['type'] ?? null) !== 'RESULT') {
                    continue;
                }

                if (($data['correlationId'] ?? null) !== $requestId) {
                    continue;
                }

                $server = $data['server'] ?? 'unknown';
                $status = $data['status'] ?? 'ERROR';

                $servers[$server] = $status;

                if (count($servers) >= $expectedServers) {
                    return !in_array('ERROR', $servers, true);
                }
            }

            usleep(200000);
        }

        return false;
    }
}