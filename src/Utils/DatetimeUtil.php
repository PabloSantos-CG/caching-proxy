<?php

namespace App\Utils;

use DateTimeImmutable;


final class DatetimeUtil
{
    private function __construct() {}

    static public function now(
        \DateTimeZone $timezone = new \DateTimeZone('America/Sao_Paulo')
    ) {
        $dateNow = new \DateTimeImmutable(timezone: $timezone);

        return $dateNow->format('Y-m-d H:i:s P');
    }
}

