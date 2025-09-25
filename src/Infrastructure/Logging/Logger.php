<?php

namespace App\Infrastructure\Logging;

use App\Infrastructure\Contracts\LoggerInterface;
use App\Utils\DatetimeManager;

class Logger implements LoggerInterface
{
    private string $path;
    private ?string $message;

    public function __construct(?string $message = null)
    {
        $this->path = $_SERVER['DOCUMENT_ROOT'] . '/logs';
        $this->message = $message ?? $this->message;
    }

    public function writeTrace(Level $flag): bool
    {
        $data = [];

        $data[] = '[' . DatetimeManager::now() . '] ';
        $data[] = "$flag" . \PHP_EOL;
        $data[] = $_ENV['HOST_PORT'] . ':' . $_ENV['ORIGIN'] . \PHP_EOL;
        $data[] = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);
        $data[] = '[URI::/' . $_GET['url'] . ']';
        $data[] = $this->message ? \PHP_EOL . "$this->message;" : ";";
        $data[] = \PHP_EOL;

        return (bool) \file_put_contents($this->path, $data, \FILE_APPEND);
    }
}
