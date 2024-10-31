<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\EmailReceiverRepository;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class EmailReceiverService
{
    public function __construct(
        private EmailReceiverRepository $emailReceiversRepository,
        private NormalizerInterface $normalizer,
    )
    {
    }

    /** @throws ExceptionInterface */
    public function getUserReceiversAsArray(int $userTokenId): array
    {
        $emailReceivers = $this->emailReceiversRepository->findByUserTokenId($userTokenId);
        if (count($emailReceivers) === 0) {
            throw new \RuntimeException('There is no e-mail receivers for posted token');
        }

        $emailAddresses = [];
        foreach ($emailReceivers as $emailReceiver) {
            $emailAddresses[] = $this->normalizer->normalize($emailReceiver, 'array');
        }

        return $emailAddresses;
    }
}