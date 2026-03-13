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
                'carbon:sync',
                'MAXLEN', '~', '1000',
                '*',
                'type', $type,
                'requestId', $requestId,
                'entityId', $entityId,
            ]);

        return $requestId;
    }

    private function waitForResults(string $requestId, int $expectedServers = 1): bool {
        $servers = [];
        $timeout = 5;
        $start = microtime(true);

        while (microtime(true) - $start < $timeout) {

            $results = Redis::connection()
                ->client()
                ->executeRaw([
                    'XRANGE',
                    'carbon:sync:results',
                    '-',
                    '+',
                    'COUNT',
                    20
                ]);

            foreach ($results as $entry) {

                $fields = $entry[1];

                if (array_is_list($fields)) {

                    $data = [];

                    for ($i = 0; $i < count($fields) - 1; $i += 2) {
                        $key = $fields[$i];
                        $value = $fields[$i + 1] ?? null;

                        if ($key !== null) {
                            $data[$key] = $value;
                        }
                    }

                } else {
                    $data = $fields;
                }

                if (($data['requestId'] ?? null) !== $requestId) {
                    continue;
                }

                $server = $data['server'] ?? 'unknown';
                $status = $data['status'] ?? 'ERROR';

                $servers[$server] = $status;

                if (count($servers) >= $expectedServers) {

                    foreach ($servers as $status) {
                        if ($status !== 'SUCCESS') {
                            return false;
                        }
                    }

                    return true;
                }
            }

            usleep(200000);
        }

        return false;
    }
}