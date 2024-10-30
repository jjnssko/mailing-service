<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController extends AbstractController
{
    /** @param mixed[] $data */
    public function successResponse(array $data = [], int $status = Response::HTTP_OK): JsonResponse
    {
        $response = array_merge(
            [
                'status' => 'ok',
                'message' => 'Success',
            ],
            $data
        );

        return $this->json($response, $status);
    }

    /** @param array<string, mixed> $data */
    public function errorResponse(array $data, int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = array_merge(
            [
                'status' => 'error'
            ],
            $data
        );

        return $this->json($response, $statusCode);
    }
}
