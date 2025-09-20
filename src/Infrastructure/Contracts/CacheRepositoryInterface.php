<?php

namespace App\Infrastructure\Contracts;


interface CacheRepositoryInterface
{
    public function get(string $key): mixed;
    public function set(string $key, mixed $data): bool;
}
