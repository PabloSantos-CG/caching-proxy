<?php

namespace App\Presentation\Contracts;

interface ProxyControllerInterface
{
    public function index(
        RequestInterface $request,
        ResponseInterface $response
    ): void;
}
