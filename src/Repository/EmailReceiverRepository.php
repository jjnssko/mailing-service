<?php

namespace App\Repository;

use App\Entity\EmailReceiver;
use Doctrine\Persistence\ManagerRegistry;

class EmailReceiverRepository extends AbstractBaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailReceiver::class);
    }

    public function findByUserTokenId(int $userTokenId): array
    {
        return $this->findBy(['userToken' => $userTokenId]);
    }
}
