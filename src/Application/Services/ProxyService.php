<?php

namespace App\Application\Services;

use App\Application\Contracts\ProxyServiceInterface;
use App\Infrastructure\Contracts\LoggerInterface;
use App\Infrastructure\HttpClient\HttpClient;
use App\Infrastructure\Logging\LevelEnum;
use App\Infrastructure\Logging\Logger;
use App\Infrastructure\Repositories\RedisRepository;
use Exception;


class ProxyService implements ProxyServiceInterface
{
    private RedisRepository $cacheRepository;
    private LoggerInterface $logger;

    public function __construct()
    {
        $this->cacheRepository = new RedisRepository();
        $this->logger = new Logger();
    }

    private function createIfNotExists(string $url, mixed $headers): mixed
    {
        $response = HttpClient::get($url, $headers);

        $this->cacheRepository->set($url, $headers, $response['data']);

        return $this->cacheRepository->get($url);
    }

    private function findOrUpdate(string $url, mixed $headers): mixed
    {
        if (!$headers || !$headers['last_modified']) {
            $message = 'key \"last_modified\" not found';

            $this->logger->writeTrace($message, LevelEnum::ERROR);
            
            throw new Exception($message, 400);
        }

        $data = $this->cacheRepository->get($url);

        if ($headers['last_modified'] !== $data['last_modified']) {
            $response = HttpClient::get($url, $headers);
            $data = $this->cacheRepository->update($url, $response['data']);
        }

        return $data;
    }

    public function index(string $url, mixed $headers): mixed
    {
        if (!$this->cacheRepository->checkExists($url)) {
            $this->logger->writeTrace(flag: LevelEnum::REQUEST);

            return $this->createIfNotExists($url, $headers);
        }

        $this->logger->writeTrace(flag: LevelEnum::REQUEST);

        return $this->findOrUpdate($url, $headers);
    }
}
