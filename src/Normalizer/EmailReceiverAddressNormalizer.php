<?php

declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\EmailReceiver;
use App\Enum\RequiredFormFields;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EmailReceiverAddressNormalizer implements NormalizerAwareInterface, NormalizerInterface
{
    use NormalizerAwareTrait;
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof EmailReceiver;
    }

    /**
     * @param EmailReceiver $object
     * @param array<string, string[]> $context
     * @return array<string, string>
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        return [
            RequiredFormFields::NAME => $object->getFullName(),
            RequiredFormFields::EMAIL => $object->getEmail(),
        ];
    }

    /**
     * @return array<class-string, bool>
     */
    public function getSupportedTypes(?string $format = null): array
    {
        return [
            EmailReceiver::class => true,
        ];
    }
}