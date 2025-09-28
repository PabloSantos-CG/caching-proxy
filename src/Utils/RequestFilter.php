<?php

namespace App\Utils;

class RequestFilter
{
    public static function filter(): void
    {
        if ($_SERVER['REQUEST_URI'] === '/favicon.ico') {
            http_response_code(204);
            exit();
        }
    }
}
