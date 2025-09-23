<?php

namespace App\Infrastructure\Contracts;

interface HttpClientInterface
{
    public static function get(string $url, mixed $headers): mixed;
}
