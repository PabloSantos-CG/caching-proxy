<?php

namespace App\Core;

use App\Presentation\Contracts\ProxyControllerInterface;
use App\Presentation\Controllers\ProxyController;
use App\Presentation\Http\Request;
use App\Presentation\Http\Response;

class Core
{
    private ProxyControllerInterface $proxyController;

    public function __construct()
    {
        $this->proxyController = new ProxyController();
    }

    public function run(): void
    {
        $this->proxyController->index(
            new Request(),
            new Response(),
        );
    }
}
