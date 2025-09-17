<?php

namespace App\Infrastructure\Repositories;

use App\Infrastructure\Contracts\CacheRepositoryInterface;
use Predis\Client as PredisClient;

class RedisRepository implements CacheRepositoryInterface
{
    private PredisClient $predisClient;

    public function __construct(
    ) {
        $this->predisClient = new PredisClient();
    }

    private function incrementRateLimit(string $key): int
    {
        return 0;
    }

    public function get(string $key): mixed
    {
        return [];
    }

    public function set(string $key, mixed $data): bool
    {
        return true;
    }
}
