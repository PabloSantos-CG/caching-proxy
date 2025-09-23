<?php

namespace App\Presentation\Http;

use App\Presentation\Contracts\RequestInterface;

class Request implements RequestInterface
{
    public function header(?string $key = null): mixed
    {
        $headers = \getallheaders();

        if ($key) {
            return \array_filter(
                $headers,
                fn($k) => $k === $key,
                \ARRAY_FILTER_USE_KEY
            );
        }

        return $headers;
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getQueryStr(?string $key = null): mixed
    {
        if ($key) return $_GET[$key];

        return $_GET;
    }
}
