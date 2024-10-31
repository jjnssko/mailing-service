<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\EmailSubmitDto;
use App\Enum\RequiredFormFields;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

final readonly class EmailFactory
{
    public function __construct(
        private string $mailSenderName,
        private string $mailSenderAddress,
    )
    {
    }

    public function createSubmitEmail(EmailSubmitDto $emailSubmitDto): Email
    {
        $senderAddress = sprintf('%s <%s>', $emailSubmitDto->getFullName(), $emailSubmitDto->getEmailAddress());
        $receiverAddress = sprintf('%s <%s>', $this->mailSenderName, $this->mailSenderAddress);

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