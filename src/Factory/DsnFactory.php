<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\EmailSubmitDto;
use App\Entity\EmailSender;
use App\Enum\RequiredFormFields;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

final readonly class DsnFactory
{
    public function createDsn(EmailSender $emailSender): string
    {
        return sprintf(
            '%s://%s:%s@%s:%s',
            $emailSender->getMailProtocol(),
            $emailSender->getEmail(),
            $emailSender->getPassword(), // TODO decrypt password by ENV key
            $emailSender->getMailServer(),
            $emailSender->getMailServerPort(),
        );
    }
}