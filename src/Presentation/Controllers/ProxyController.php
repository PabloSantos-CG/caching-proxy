<?php

namespace App\Presentation\Controllers;

use App\Application\Contracts\ProxyServiceInterface;
use App\Application\Services\ProxyService;
use App\Presentation\Contracts\ProxyControllerInterface;
use App\Presentation\Contracts\RequestInterface;
use App\Presentation\Contracts\ResponseInterface;

class ProxyController implements ProxyControllerInterface
{
    private ProxyServiceInterface $proxyService;

    public function __construct()
    {
        $this->proxyService = new ProxyService();
    }

    public function index(
        RequestInterface $request,
        ResponseInterface $response
    ): void {
        try {
            /**
             * @var array{
             *     headers: string,
             *     body: string,
             *     last_modified: \DateTimeInterface,
             *     "url:rate_limit": int
             * }
             * 
             * Body is JSON encoded
             */
            $data = $this->proxyService->index(
                $request->getUrl(),
                $request->header()
            );

            $body = \json_decode($data['body']);

            $response->json(
                \json_decode($data['headers']),
                $body
            );
        } catch (\Throwable $th) {
            $response->json(
                data: [
                    'status' => 'error',
                    'message' => $th->getMessage()
                ],
                status_code: $th->getCode()
            );
        }
    }
}
