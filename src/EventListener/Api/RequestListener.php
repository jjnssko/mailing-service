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

        $requestedRoute = $request->attributes->get('_route');
        if ($requestedRoute !== null && str_contains($requestedRoute, 'api_')) {
            try {
                $this->requestValidator->validateOrigin($request);
            } catch (HttpExceptionInterface $e) {
                $this->setJsonResponse($event, $e->getMessage(), $e->getStatusCode());
            }
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
