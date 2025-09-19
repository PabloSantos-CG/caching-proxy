<?php

namespace App\Utils;

function randomStrGenerate(int $length = 8)
{
    $bytes = \random_bytes($length);

    return \substr(\bin2hex($bytes), 0, $length);
}
