<?php

namespace App\Infrastructure\Logging;

use App\Infrastructure\Contracts\LoggerInterface;
use App\Utils\DatetimeManager;

class Logger implements LoggerInterface
{
    private string $path;

    public function __construct()
    {
        $this->path = $_SERVER['DOCUMENT_ROOT'] . '/logs/history.log';
    }

    public function writeTrace(LevelEnum $flag, ?string $message = null): bool
    {
        $data = [];

        $data[] = '[' . DatetimeManager::now() . '] ';
        $data[] = $flag->name . \PHP_EOL;
        $data[] = 'PORT:' . $_ENV['HOST_PORT'] . '/';
        $data[] = 'ORIGIN:' . $_ENV['ORIGIN'] . \PHP_EOL;
        $data[] = \json_encode(
            \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS)[0]
        );
        $data[] = \PHP_EOL;
        $data[] = '[URI::/' . $_GET['url'] . ']';
        $data[] = $message ? \PHP_EOL . "error-message: $message;" : ";";
        $data[] = \PHP_EOL;

        $result = (bool) \file_put_contents($this->path, $data, \FILE_APPEND);
        return $result;
    }
}
