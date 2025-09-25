<?php

namespace App\Utils;

use ArgumentCountError;
use InvalidArgumentException;

class CLIManager
{
    /**
     * @var array{port:int,url:string}|null $data
     */
    private static mixed $data = null;

    /**
     * @return array{port:int,url:string} returns validated PORT and URL
     * 
     * @throws \ArgumentCountError|InvalidArgumentException
     */
    public static function serve(): mixed
    {
        if (self::$data) return self::$data;

        $cliArgs = \array_shift($_SERVER['argv']);

        if (
            !$cliArgs || \count($cliArgs) < 4 ||
            !\in_array(['--port', '--origin'], $cliArgs)
        ) {
            throw new ArgumentCountError(
                'necessário informar: --port <number> --origin <url>'
            );
        }

        self::$data['port'] = \filter_var($cliArgs[2], \FILTER_VALIDATE_INT);
        self::$data['url'] = \filter_var($cliArgs[4], \FILTER_VALIDATE_URL);

        if (!self::$data['port'] || !self::$data['url']) {
            throw new InvalidArgumentException('invalid type');
        }

        return self::$data;
    }

    public static function str()
    {
        if (!self::$data) self::run();

        $result = '[';

        foreach (self::$data as $key => $value) {
            $result .= "$key/$value  ";
        }

        return $result .= ']';
    }
}
