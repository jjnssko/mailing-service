<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Dto\EmailSubmitDto;
use App\Enum\RequiredFormFields;
use App\Exception\ValidationException;
use App\Factory\EmailFactory;
use App\Service\EmailProcessor;
use App\Service\EmailReceiverService;
use App\Service\UserTokenValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;

final class EmailController extends BaseApiController
{
    public function __construct(
        private readonly EmailProcessor $emailProcessor,
        private readonly EmailFactory $emailFactory,
        private readonly MailerInterface $mailer,
        private readonly UserTokenValidator $userTokenValidator,
        private readonly EmailReceiverService $emailReceiverService,
    )
    {
    }

    public function submit(Request $request): JsonResponse
    {
        $payload = iterator_to_array($request->getPayload());
        $headers = $request->headers;

        if (false === array_key_exists(RequiredFormFields::ACCESS_KEY, $payload)) {
            return $this->errorResponse(['message' => 'Submitted data missing "access_key"']);
        }

        $userToken = $this->userTokenValidator->getValidatedUserAccessToken($payload[RequiredFormFields::ACCESS_KEY], $headers->get('host'));

        try {
            $dto = EmailSubmitDto::fromArray($payload);
            $emailReceivers = $this->emailReceiverService->getUserReceiversAsArray($userToken->getId());
            $dto->setReceivers($emailReceivers);
            $email = $this->emailFactory->createSubmitEmail($dto);
            $this->mailer->send($email);
            $this->emailProcessor->storeEmailProcessLog(
                $payload,
                $userToken,
                Response::HTTP_OK,
            );

            return $this->successResponse();
        } catch (\Throwable $e) {
            $this->emailProcessor->storeEmailProcessLog(
                $payload,
                $userToken,
                Response::HTTP_BAD_REQUEST,
                $e instanceof ValidationException ? $e->getLogMessage() : $e->getMessage(),
            );
            return $this->errorResponse(['message' => $e->getMessage()]);
        }
    }
}