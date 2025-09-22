<?php

namespace App\Presentation\Contracts;

interface RequestInterface {
    public function header(?string $key): mixed;
    public function method(): string;
}