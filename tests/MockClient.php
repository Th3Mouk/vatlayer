<?php

declare(strict_types=1);

namespace Th3Mouk\VatLayer\Tests;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class MockClient implements ClientInterface
{
    /** @var mixed */
    private $response;

    private RequestInterface $request;

    /**
     * @param mixed $response
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->request = $request;

        if ($this->response instanceof ClientExceptionInterface) {
            throw $this->response;
        }

        return $this->response;
    }

    public function request(): RequestInterface
    {
        return $this->request;
    }
}
