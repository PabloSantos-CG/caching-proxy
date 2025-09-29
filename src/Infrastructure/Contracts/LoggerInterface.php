<?php

namespace App\Infrastructure\Contracts;

use App\Infrastructure\Logging\Level;
use App\Infrastructure\Logging\LevelEnum;

interface LoggerInterface
{
    public static function writeTrace(LevelEnum $flag, string $filePath, int $line): bool;
}
