<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\EmailProcessLog;
use App\Entity\UserToken;
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
            ->setSenderName($data['name'])
            ->setSenderEmail($data['email'])
            ->setSubject($data['subject'])
            ->setBody($data['body'])
            ->setResponseCode($responseStatus)
            ->setErrorMessage($errorMessage);

        $this->emailProcessLogRepository->save($emailProcessLog);
    }
}