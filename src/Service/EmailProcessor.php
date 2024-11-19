<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\EmailProcessLog;
use App\Entity\UserToken;
use App\Enum\OptionalFormFields;
use App\Enum\RequiredFormFields;
use App\Repository\EmailProcessLogRepository;

final readonly class EmailProcessor
{
    public function __construct(
        private EmailProcessLogRepository $emailProcessLogRepository,
    )
    {
    }

    public function storeEmailProcessLog(array $data, UserToken $userToken, int $responseStatus, ?string $errorMessage = null): void
    {
        $emailProcessLog = (new EmailProcessLog())
            ->setUserToken($userToken)
            ->setSenderName($data[RequiredFormFields::NAME])
            ->setSenderEmail($data[RequiredFormFields::EMAIL])
            ->setSubject($data[OptionalFormFields::SUBJECT])
            ->setBody($data[RequiredFormFields::MESSAGE])
            ->setResponseCode($responseStatus)
            ->setErrorMessage($errorMessage);

        $this->emailProcessLogRepository->save($emailProcessLog);
    }
}