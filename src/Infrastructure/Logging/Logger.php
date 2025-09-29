<?php

namespace App\Infrastructure\Logging;

use App\Infrastructure\Contracts\LoggerInterface;
use App\Utils\DatetimeManager;

class Logger implements LoggerInterface
{
    public static function writeTrace(
        LevelEnum $flag,
        string $filePath,
        int $line,
    ): bool {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/logs/history.log';
        $data = [];

        $data[] = '[' . DatetimeManager::now() . '] ';
        $data[] = $flag->name . \PHP_EOL;
        $data[] = 'PORT:' . $_ENV['HOST_PORT'] . '/';
        $data[] = 'ORIGIN:' . $_ENV['ORIGIN'] . \PHP_EOL;
        $data[] = "[file:$filePath; line:$line]" . \PHP_EOL;
        $data[] = '[URI::/' . $_GET['url'] . ']' . \PHP_EOL;

        $result = (bool) \file_put_contents($path, $data, \FILE_APPEND);
        return $result;
    }
}
