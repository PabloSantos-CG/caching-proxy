<?php

namespace App\Infrastructure\Repositories;

use App\Infrastructure\Contracts\CacheRepositoryInterface;
use App\Utils\DatetimeManager;
use Predis\Client as PredisClient;
use Dotenv\Dotenv;
use Exception;

class RedisRepository implements CacheRepositoryInterface
{
    private static Dotenv $dotenv;
    private PredisClient $predisClient;

    public function __construct()
    {
        self::$dotenv = Dotenv::createImmutable(__DIR__);
        self::$dotenv->load();

        $this->predisClient = new PredisClient([
            'host' => $_ENV['REDIS_HOST'],
            'port' => $_ENV['REDIS_PORT'],
            'password' => $_ENV['REDIS_PASSWORD'],
        ]);
    }

    private function incrementRateLimit(string $key): int
    {
        return $this->predisClient->hincrby($key, "$key:rate_limit", 1);
    }

    /**
     * @param string $key Server URL
     * @return string[]|false
     */
    public function get(string $key): mixed
    {
        $result = $this->predisClient->hgetall($key);

        if (!$result) return false;

        $this->incrementRateLimit($key);

        return $result;
    }

    /**
     * @param string $key Server URL
     * @param array<string,string>|null $headers headers or null
     * @param string $data Json data in string format
     * @param int $ttl Expiration time, default is 2h
     * 
     * @throws \Exception if operation fail or key exists
     * 
     * @return true
     */
    public function set(
        string $key,
        mixed $headers = null,
        mixed $data,
        int $ttl = 7200
    ): bool {
        if ($this->predisClient->exists($key)) {
            throw new Exception('key exists', 400);
        }

        $result = (bool) $this->predisClient->hset($key, [
            "headers" => $headers ?? [],
            "body" => $data,
            "last_modified" => DatetimeManager::now(),
            "$key:rate_limit" => 60,
        ]);

        if (!$result) throw new Exception('bad request', 500);

        $this->predisClient->expire($key, $ttl);

        return $result;
    }

    /**
     * @param string $key Server URL
     * 
     * @throws \Exception if operation fail or key exists
     * 
     * @return array returns updated data
     */
    public function update(
        string $key,
        mixed $newBody,
    ): mixed {
        if (!$this->predisClient->exists($key)) {
            throw new Exception('key does not exist', 400);
        }

        $result = (bool) $this->predisClient->hset($key, [
            "body" => $newBody,
            "last_modified" => DatetimeManager::now(),
        ]);

        if (!$result) throw new Exception('bad request', 500);

        $this->incrementRateLimit($key);

        return $this->predisClient->hgetall($key);
    }
}
