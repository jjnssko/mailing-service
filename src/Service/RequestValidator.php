<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\Api\InvalidRequestOriginException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class RequestValidator
{
    public function __construct(
        private string $corsAllowOrigin,
    )
    {
    }

    /** @throws InvalidRequestOriginException */
    public function validateOriginHeader(string $originHeader): void
    {
        if (!preg_match($this->corsAllowOrigin . '^', $originHeader)) {
            throw new InvalidRequestOriginException('Forbidden', Response::HTTP_FORBIDDEN);
        }
    }

    public function validateHoneypot(Request $request, string $token): void
    {
        if ($request->getPayload()->get($token) !== null) {
            throw new \RuntimeException('Something went wrong');
        }
    }
}