<?php

namespace App\Presentation\Http;

use App\Presentation\Contracts\RequestInterface;

class Request implements RequestInterface
{
    private string $url;

    public function __construct()
    {
        $this->url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }

    public function header(?string $key): mixed
    {
        if ($key) {
            $header = \get_headers($this->url, true);

            return "$key:" . $header[$key];
        }

        return get_headers($this->url);
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
