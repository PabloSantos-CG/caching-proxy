<?php

namespace App\Utils;

use ArgumentCountError;
use InvalidArgumentException;

class CLIManager
{
    /**
     * @throws \ArgumentCountError|InvalidArgumentException
     * 
     * @return array{port:int,url:string} returns validated PORT and URL
     */
    public static function run(): mixed
    {
        $cliArgs = \array_shift($_SERVER['argv']);

        if (
            !$cliArgs || \count($cliArgs) < 4 ||
            !\in_array(['--port', '--origin'], $cliArgs)
        ) {
            throw new ArgumentCountError(
                'necessário informar: --port <number> --origin <url>'
            );
        }

        $port = \filter_var($cliArgs[2], \FILTER_VALIDATE_INT);
        $url = \filter_var($cliArgs[4], \FILTER_VALIDATE_URL);

        if (!$port || !$url) throw new InvalidArgumentException('invalid type');

        return ['port' => $port, 'url' => $url];
    }
}
