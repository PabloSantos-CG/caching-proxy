<?php

namespace App\Infrastructure\Logging;

use App\Utils\CLIManager;
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
        $data[] = CLIManager::getConfigurationOptionsAsString() . \PHP_EOL;
        $data[] = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);
        $data[] = '[REQUEST_URI::' . $_SERVER['REQUEST_URI'] . '] ';
        $data[] = "$flag;" . \PHP_EOL;

        return \file_put_contents($this->path, $data, \FILE_APPEND);
    }
}
