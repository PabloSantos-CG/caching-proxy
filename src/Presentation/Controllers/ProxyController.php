<?php

namespace App\Presentation\Controllers;

use App\Application\Contracts\ProxyServiceInterface;
use App\Application\Services\ProxyService;
use App\Infrastructure\Logging\LevelEnum;
use App\Infrastructure\Logging\Logger;
use App\Presentation\Contracts\ProxyControllerInterface;
use App\Presentation\Contracts\RequestInterface;
use App\Presentation\Contracts\ResponseInterface;
use Exception;

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
            if ($request->method() !== 'GET') {
                throw new Exception('unauthorized method', 401);
            }

            /**
             * @var array{
             *     headers: array,
             *     body: array,
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

            $response->json(
                $data['headers'],
                [
                    'data' => $data['body'],
                    'last_modified' => $data['last_modified'] ?? null
                ]
            );
        } catch (\Throwable $th) {

            Logger::writeTrace(
                LevelEnum::ERROR,
                $th->getFile(),
                $th->getLine()
            );

            $response->json(
                data: [
                    'status' => 'error',
                    'message' => $th->getMessage(),
                ],
                status_code: $th->getCode()
            );
        }
    }
}
