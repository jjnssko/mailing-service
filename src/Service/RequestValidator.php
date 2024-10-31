<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\Api\InvalidRequestOriginException;
use Symfony\Component\HttpFoundation\Request;

readonly class RequestValidator
{
    public function __construct(
        private string $corsAllowOrigin,
    )
    {
    }

    /** @throws InvalidRequestOriginException */
    public function validateOrigin(Request $request): void
    {
        $origin = $request->headers->get('Origin');
        $referer = $request->headers->get('Referer');
        $regex = $this->corsAllowOrigin;

        if (null === $origin || !preg_match($regex, $origin)) {
            throw new InvalidRequestOriginException('Invalid request origin');
        }

        if (null === $referer || !str_contains($referer, $request->getSchemeAndHttpHost())) {
            throw new InvalidRequestOriginException('Invalid request referer');
        }
    }
}