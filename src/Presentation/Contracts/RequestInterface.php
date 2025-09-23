<?php

namespace App\Presentation\Contracts;

interface RequestInterface {
    public function header(?string $key): mixed;
    public function method(): string;
    public function getQueryStr(?string $key): mixed;
    public function getUrl(): string;
}