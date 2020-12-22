<?php

declare(strict_types=1);

namespace Th3Mouk\VatLayer\Exception;

use Psr\Http\Message\ResponseInterface;

final class BadResponse extends VatLayer
{
    public ResponseInterface $response;

    private function __construct(string $message, int $status_code, ResponseInterface $response, ?\Throwable $throwable = null)
    {
        $this->response = $response;
        parent::__construct($message, $status_code, $throwable);
    }

    public static function dueToStatusCode(ResponseInterface $response): self
    {
        return new self('Unexpected status code received', $response->getStatusCode(), $response);
    }

    public static function dueToInvalidResponseFormat(ResponseInterface $response, \Throwable $throwable): self
    {
        return new self(
            'Invalid response received: ' . $throwable->getMessage(),
            (int) $throwable->getCode(),
            $response,
            $throwable,
        );
    }
}
