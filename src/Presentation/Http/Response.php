<?php

namespace App\Presentation\Http;

use App\Presentation\Contracts\ResponseInterface;

class Response implements ResponseInterface
{
    public function header(?string $key): mixed
    {
        //
    }

    public function method(): string
    {
        //
    }

    public function body(): mixed
    {
        //
    }
}
