<?php

namespace App\Infrastructure\Logging;

use App\Infrastructure\Contracts\LoggerInterface;
use App\Utils\DatetimeManager;

class Logger implements LoggerInterface
{
    private string $path;

    public function __construct()
    {
        $this->path = $_SERVER['DOCUMENT_ROOT'] . '/logs';
    }

    public function writeTrace(?string $message = null, LevelEnum $flag): bool
    {
        $data = [];

        $data[] = '[' . DatetimeManager::now() . '] ';
        $data[] = "$flag" . \PHP_EOL;
        $data[] = $_ENV['HOST_PORT'] . ':' . $_ENV['ORIGIN'] . \PHP_EOL;
        $data[] = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);
        $data[] = '[URI::/' . $_GET['url'] . ']';
        $data[] = $message ? \PHP_EOL . "$message;" : ";";
        $data[] = \PHP_EOL;

        return (bool) \file_put_contents($this->path, $data, \FILE_APPEND);
    }
}
