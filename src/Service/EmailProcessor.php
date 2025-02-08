<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\EmailLogData;
use App\Entity\EmailProcessLog;
use App\Entity\UserToken;
use App\Factory\DsnFactory;
use App\Repository\EmailProcessLogRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

final class EmailProcessor
{
    public function __construct(
        private EmailProcessLogRepository $emailProcessLogRepository,
        private DsnFactory $dsnFactory,
    )
    {
    }

    /** @throws TransportExceptionInterface */
    public function sendEmail(Email $email, EmailLogData $logData, UserToken $userToken): void
    {
        $transport = Transport::fromDsn($this->dsnFactory->createDsn($userToken->getEmailSender()));
        $mailer = new Mailer($transport);

        $mailer->send($email);

        $this->storeEmailProcessLog(
            $logData,
            $userToken,
            Response::HTTP_OK,
        );
    }

    public function storeEmailProcessLog(EmailLogData $logData, UserToken $userToken, int $responseStatus, ?string $errorMessage = null): void
    {
        $emailProcessLog = (new EmailProcessLog())
            ->setUserToken($userToken)
            ->setSenderName($logData->senderName)
            ->setSenderEmail($logData->senderEmail)
            ->setSubject($logData->subject)
            ->setBody($logData->body)
            ->setResponseCode($responseStatus)
            ->setErrorMessage($errorMessage);

        $this->emailProcessLogRepository->save($emailProcessLog);
    }
}