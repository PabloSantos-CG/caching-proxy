<?php

namespace App\Application\Services;

use App\Application\Contracts\ProxyServiceInterface;
use App\Infrastructure\HttpClient\HttpClient;
use App\Infrastructure\Repositories\RedisRepository;
use Exception;

class ProxyService implements ProxyServiceInterface
{
    private RedisRepository $cacheRepository;

    public function __construct()
    {
        $this->cacheRepository = new RedisRepository();
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
            throw new Exception('key \"last_modified\" not found', 400);
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
            return $this->createIfNotExists($url, $headers);
        }

        return $this->findOrUpdate($url, $headers);
    }
}
