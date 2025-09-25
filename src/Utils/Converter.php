<?php

namespace App\Utils;

final class Converter {
    public static function getStringSizeInMB(string $data): float {
        $bytes = \strlen($data);
        return $bytes / (1024**2);
    }
}