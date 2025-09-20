<?php

namespace App\Infrastructure\Contracts;

interface HttpClientInterface
{
    public function get(mixed $headers): mixed;
}
