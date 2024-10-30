<?php

namespace App\Exception\Api;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class InvalidRequestOriginException extends Exception implements HttpExceptionInterface
{
    private int $statusCode;
    private array $headers;

    public function __construct(
        string $message,
        int $statusCode = Response::HTTP_BAD_REQUEST,
        ?\Throwable $previous = null,
        array $headers = [],
        int $code = 0
    )
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;

        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }
}