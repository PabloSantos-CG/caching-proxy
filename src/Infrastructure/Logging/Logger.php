<?php

namespace App\Infrastructure\Logging;

use App\Utils\DatetimeManager;

class Logger
{
    private string $path;

    public function __construct()
    {
        $this->path = $_SERVER['DOCUMENT_ROOT'] . '/logs';
    }

    public function writeTrace(Level $flag)
    {
        $data = [];

        $data[] = '[' . DatetimeManager::now() . '] ';
        $data[] = $_ENV['HOST_PORT'] . ':' . $_ENV['ORIGIN'] . \PHP_EOL;
        $data[] = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);
        $data[] = '[URI::/' . $_GET['url'] . '] ';
        $data[] = "$flag;" . \PHP_EOL;

        return \file_put_contents($this->path, $data, \FILE_APPEND);
    }
}
