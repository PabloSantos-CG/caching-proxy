<?php

namespace App\Infrastructure\Repositories;

use App\Infrastructure\Contracts\CacheRepositoryInterface;
use App\Infrastructure\Contracts\LoggerInterface;
use App\Infrastructure\Logging\LevelEnum;
use App\Infrastructure\Logging\Logger;
use App\Utils\Converter;
use App\Utils\DatetimeManager;
use Predis\Client as PredisClient;
use Exception;


class RedisRepository implements CacheRepositoryInterface
{
    private PredisClient $predisClient;
    private LoggerInterface $logger;

    public function __construct()
    {
        $this->predisClient = new PredisClient([
            'host' => $_ENV['REDIS_HOST'],
            'port' => $_ENV['REDIS_PORT'],
            'password' => $_ENV['REDIS_PASSWORD'],
        ]);

        $this->logger = new Logger();
    }

    private function incrementRateLimit(string $key): int
    {
        return $this->predisClient->hincrby($key, "rate_limit", 1);
    }

    public function checkExists(string $key): bool
    {
        return $this->predisClient->exists($key);
    }

    private function checkIfRateLimitReached(string $key): bool
    {
        $rateLimit = $this->predisClient->hget($key, 'rate_limit');

        return $rateLimit >= 60 ? true : false;
    }

    /**
     * @param string $key Server URL
     * @return string[]|false
     */
    public function get(string $key): mixed
    {
        if ($this->checkIfRateLimitReached($key)) {
            throw new Exception('rate limit reached', 400);
        }

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
        mixed $data,
        mixed $headers = '[]',
        int $ttl = 7200
    ): bool {
        if ($this->predisClient->exists($key)) {
            // $this->logger->writeTrace(LevelEnum::ERROR, 'Key exists');

            throw new Exception('key exists', 400);
        }

        if (Converter::getStringSizeInMB($data) > 2) {
            // $this->logger->writeTrace(LevelEnum::ERROR, 'exceeded size');

            throw new Exception('exceeded size', 400);
        }

        $result = (bool) $this->predisClient->hset(
            $key,
            "headers",
            $headers,
            "body",
            $data,
            "last_modified",
            DatetimeManager::now(),
            "rate_limit",
            0,
        );

        if (!$result) {
            // $this->logger->writeTrace(LevelEnum::ERROR, 'bad request');

            throw new Exception('bad request', 500);
        }

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
        if ($this->checkIfRateLimitReached($key)) {
            throw new Exception('rate limit reached', 400);
        }

        if (!$this->predisClient->exists($key)) {
            // $this->logger->writeTrace(LevelEnum::ERROR, 'key does not exist');

            throw new Exception('key does not exist', 400);
        }

        $this->predisClient->hset(
            $key,
            "body",
            $newBody,
            "last_modified",
            DatetimeManager::now(),
        );

        $this->incrementRateLimit($key);

        return $this->predisClient->hgetall($key);
    }

    public function clearAll(): void
    {
        $this->predisClient->flushall();
    }
}
