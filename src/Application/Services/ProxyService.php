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

        $this->cacheRepository->set($url, $response['data'], $headers);

        return $this->cacheRepository->get($url);
    }

    private function findOrUpdate(string $url, mixed $headers): mixed
    {
        $data = $this->cacheRepository->get($url);

        if ($data && !$headers['last_modified']) {
            $message = 'key \"last_modified\" not found';

            $this->logger->writeTrace(LevelEnum::ERROR, $message);
            
            throw new Exception($message, 400);
        }

        if ($headers['last_modified'] > $data['last_modified']) {
            $response = HttpClient::get($url, $headers);
            $data = $this->cacheRepository->update($url, $response['data']);
        }

        return $data;
    }

    public function index(string $url, mixed $headers): mixed
    {
        if (!$this->cacheRepository->checkExists($url)) {
            $this->logger->writeTrace(LevelEnum::ERROR);

            return $this->createIfNotExists($url, $headers);
        }

        $this->logger->writeTrace(LevelEnum::REQUEST);

        return $this->findOrUpdate($url, $headers);
    }
}
