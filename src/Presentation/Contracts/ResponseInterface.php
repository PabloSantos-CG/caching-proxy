<?php

namespace App\Presentation\Contracts;

interface ResponseInterface
{
    public function header(?string $key): mixed;
    public function method(): string;
    public function body(): mixed;
}
