<?php

namespace App\Application\Contracts;

interface ProxyServiceInterface
{
    public function index(string $url, mixed $headers): mixed;
}
