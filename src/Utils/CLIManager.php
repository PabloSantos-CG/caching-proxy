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
     * @return array{port:int,origin_domain:string} returns validated PORT and URL
     * 
     * @throws \ArgumentCountError|InvalidArgumentException
     */
    public static function extractConfigurationOptions(): mixed
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
        self::$data['origin_domain'] = \filter_var($cliArgs[4], \FILTER_VALIDATE_URL);

        if (!self::$data['port'] || !self::$data['origin_domain']) {
            throw new InvalidArgumentException('invalid type');
        }

        return self::$data;
    }

    /**
     * Return "[port:value / origin_domain:value]"
     */
    public static function getConfigurationOptionsAsString(): string
    {
        if (!self::$data) self::extractConfigurationOptions();

        $result = '[';

        foreach (self::$data as $key => $value) {
            $result .= "$key:$value /";
        }

        return $result .= ']';
    }
}
