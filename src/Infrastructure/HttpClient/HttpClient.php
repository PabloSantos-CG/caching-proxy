<?php

namespace App\Infrastructure\HttpClient;

use App\Infrastructure\Contracts\HttpClientInterface;
use App\Infrastructure\Logging\LevelEnum;
use App\Infrastructure\Logging\Logger;
use Exception;

class HttpClient implements HttpClientInterface
{
    /**
     * @param string[] $headers 
     * 
     * @return array{data:string,status_code:int}
     * 
     * @throws \Exception
     */
    public static function get(string $url, mixed $headers = []): mixed
    {
        $cURL = \curl_init();

        \curl_setopt($cURL, \CURLOPT_URL, $url);
        \curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($cURL, \CURLOPT_HTTPHEADER, $headers);

        $response = \curl_exec($cURL);
        $httpCode = \curl_getinfo($cURL, \CURLINFO_HTTP_CODE);

        if (!$response) {
            $errorMessage = \curl_error($cURL);

            \curl_close($cURL);

            throw new Exception($errorMessage, $httpCode);
        }

        \curl_close($cURL);

        return [
            'data' => $response,
            'status_code' => $httpCode,
        ];
    }
}
