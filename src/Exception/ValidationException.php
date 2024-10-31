<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ValidationException extends \InvalidArgumentException implements HttpExceptionInterface
{
    private int $statusCode;
    private array $headers;

    private string $logMessage;

    public function __construct(
        string $publicMessage = '',
        string $logMessage = '',
        int $statusCode = Response::HTTP_BAD_REQUEST,
        ?\Throwable $previous = null,
        array $headers = [],
        int $code = 0
    )
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $logMessage === '' ? $this->logMessage = $publicMessage : $this->logMessage = $logMessage;

        parent::__construct($publicMessage, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getLogMessage(): string
    {
        return $this->logMessage;
    }
}