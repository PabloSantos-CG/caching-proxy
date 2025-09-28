<?php

namespace App\Presentation\Contracts;

interface ResponseInterface
{
    public function json(
        array $headers = [],
        array $data = [],
        int $status_code = 200
    ): void;
}
