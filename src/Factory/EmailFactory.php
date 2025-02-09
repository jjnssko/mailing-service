<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\EmailSubmitDto;
use App\Entity\EmailSender;
use App\Enum\RequiredFormFields;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

final readonly class EmailFactory
{
    public function __construct(
        private string $mailSenderNameFallback,
        private string $mailSenderEmailFallback,
    )
    {
    }

    public function createSubmitEmail(EmailSubmitDto $emailSubmitDto, EmailSender $emailSender): Email
    {
        $senderAddress = sprintf('%s <%s>', $emailSubmitDto->getFullName(), $emailSubmitDto->getEmailAddress());

        $senderFullName = $emailSender->getFullName() ?: $this->mailSenderNameFallback;
        $senderEmail = $emailSender->getEmail() ?: $this->mailSenderEmailFallback;

        $receiverAddress = sprintf('%s <%s>', $senderFullName, $senderEmail);

        $email = (new Email())
            ->from(Address::create($receiverAddress))
            ->replyTo(Address::create($senderAddress))
            ->subject($emailSubmitDto->getMessageSubject())
            ->text($emailSubmitDto->getMessageBody());

        $this->addReceiverAddresses($email, $emailSubmitDto->getReceivers());

        return $email;
    }

    private function addReceiverAddresses(Email $email, array $receivers): void
    {
        if (count($receivers) === 0) {
            throw new \RuntimeException('There is no e-mail receivers for posted token');
        }

        foreach ($receivers as $receiver) {
            $receiverAddress = sprintf('%s <%s>', $receiver[RequiredFormFields::NAME], $receiver[RequiredFormFields::EMAIL]);
            $email->addTo(Address::create($receiverAddress));
        }
    }
}