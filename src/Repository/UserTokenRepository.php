<?php

namespace App\Repository;

use App\Entity\UserToken;
use App\Enum\TokenTypes;
use Doctrine\Persistence\ManagerRegistry;

class UserTokenRepository extends AbstractBaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserToken::class);
    }

    public function findUserTokenByAccessToken(string $accessToken, string $relatedUrl): ?UserToken
    {
        return $this->findOneBy([
            'type' => TokenTypes::ACCESS_TOKEN,
            'relatedUrl' => $relatedUrl,
            'token' => $accessToken,
        ]);
    }

    public function updateLastTokenUsage(UserToken $userToken): void
    {
        $userToken->setLastUsage(new \DateTimeImmutable('now'));
        $this->save($userToken);
    }
}
