<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\UserToken;
use App\Exception\ValidationException;
use App\Repository\UserTokenRepository;

final readonly class UserTokenValidator
{
    public function __construct(
        private UserTokenRepository $userTokenRepository,
    )
    {
    }

    public function getValidatedUserAccessToken(string $accessToken, string $relatedUrl): UserToken
    {
        $userToken = $this->userTokenRepository->findUserTokenByAccessToken($accessToken, $relatedUrl);
        if (null === $userToken) {
            throw new ValidationException('Invalid access token');
        }
        $this->userTokenRepository->updateLastTokenUsage($userToken);

        return $userToken;
    }
}