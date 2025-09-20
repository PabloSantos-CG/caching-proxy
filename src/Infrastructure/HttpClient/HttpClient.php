<?php

namespace App\Infrastructure\HttpClient;

use App\Infrastructure\Contracts\HttpClientInterface;
use App\Utils\CLIManager;
use Exception;

class HttpClient implements HttpClientInterface
{
    private string $url;

    public function __construct()
    {
        $this->url = CLIManager::run()['url'];
    }

    /**
     * @param string[] $headers 
     * 
     * @return array{data:string,status_code:int}
     * 
     * @throws \Exception
     */
    public function get(mixed $headers = []): mixed
    {
        $cURL = \curl_init();

        \curl_setopt($cURL, \CURLOPT_URL, $this->url);
        \curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($cURL, \CURLOPT_HTTPHEADER, $headers);

        $response = \curl_exec($cURL);
        $httpCode = \curl_getinfo($cURL, \CURLINFO_HTTP_CODE);

        if (!$response) throw new Exception(\curl_error($cURL), $httpCode);

        \curl_close($cURL);

        return [
            'data' => $response,
            'status_code' => $httpCode,
        ];
    }
}
