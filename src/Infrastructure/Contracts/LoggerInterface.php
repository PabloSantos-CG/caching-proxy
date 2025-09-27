<?php

namespace App\Infrastructure\Contracts;

use App\Infrastructure\Logging\Level;
use App\Infrastructure\Logging\LevelEnum;

interface LoggerInterface
{
    public function writeTrace(LevelEnum $flag, ?string $message = null): bool;
}
