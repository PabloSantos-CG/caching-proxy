<?php

namespace App\Infrastructure\Contracts;

use App\Infrastructure\Logging\Level;

interface LoggerInterface
{
    public function writeTrace(Level $flag): bool;
}
