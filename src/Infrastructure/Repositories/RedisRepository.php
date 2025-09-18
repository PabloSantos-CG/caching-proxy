<?php

namespace App\Infrastructure\Repositories;

use App\Infrastructure\Contracts\CacheRepositoryInterface;
use App\Utils\DatetimeUtil;
use Predis\Client as PredisClient;
use Dotenv\Dotenv;


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
//Adicionar método para salvar arquivos em binário
    private function incrementRateLimit(string $key): int
    {
        return $this->predisClient->hincrby($key, "$key:rate_limit", 1);
    }

    /**
     * @param string $key Server URL
     * @return string[]|boolean
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
     * @param string $data Json data in string format
     * @param int $ttl Expiration time, default is 2h
     * 
     * @return boolean
     */
    public function set(string $key, mixed $data, int $ttl = 7200): bool
    {
        if ($this->predisClient->exists($key)) return false;

        $result = $this->predisClient->hset($key, [
            "data" => $data,
            "last_modified" => DatetimeUtil::now(),
            "$key:rate_limit" => 60,
        ]);

        if (!$result) return false;

        $this->predisClient->hexpire($key, 7200, ['data']);

        return $result;
    }
}
