<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\UserToken;
use App\Enum\RequiredFormFields;
use App\Exception\ValidationException;
use App\Repository\UserTokenRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final readonly class UserTokenValidator
{
    public function __construct(
        private UserTokenRepository $userTokenRepository,
    )
    {
    }

    public function validatePayload(array $payload): void
    {
        if (false === array_key_exists(RequiredFormFields::ACCESS_KEY, $payload)) {
            throw new ValidationException(
                sprintf('%s "%s"', 'Submitted data missing', RequiredFormFields::ACCESS_KEY)
            );
        }
    }

    public function getValidatedUserAccessToken(string $accessToken, string $relatedUrl): UserToken
    {
        $userToken = $this->userTokenRepository->findUserTokenByAccessToken($accessToken, $relatedUrl);

        if (null === $userToken) {
            throw new ValidationException('Invalid access token');
        }

        if (null === $userToken->getEmailSender()) {
            throw new UserNotFoundException('User email sender is missing.');
        }

        $this->userTokenRepository->updateLastTokenUsage($userToken);

        return $userToken;
    }
}