<?php

namespace App\Presentation\Http;

use App\Presentation\Contracts\ResponseInterface;

class Response implements ResponseInterface
{
    public function json(
        array $headers = [],
        array $data = [],
        int $status_code = 200
    ): void {
        http_response_code($status_code);

        if (!\in_array('Content-type', $headers)) {
            \header('Content-type: application/json');
        }

        foreach ($headers as $header => $value) {
            \header("$header: $value");
        }

        echo json_encode($data);
    }
}
