<?php

declare(strict_types=1);

namespace App\EventListener\Api;

use App\Service\RequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

readonly class RequestListener
{
    public function __construct(
        private RequestValidator $requestValidator
    )
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route') ?? '';
        if (false === str_starts_with($routeName, 'api_')) {
            return;
        }

        try {
            $this->requestValidator->validateOriginHeader($request->headers->get('Origin') ?? '');
        } catch (HttpExceptionInterface $e) {
            $this->setJsonResponse($event, $e->getMessage(), $e->getStatusCode());
        }
    }

    private function setJsonResponse(RequestEvent $event, string $message, int $statusCode): void
    {
        $response = new JsonResponse(
            ['message' => $message],
            $statusCode
        );

        $event->setResponse($response);
    }
}
