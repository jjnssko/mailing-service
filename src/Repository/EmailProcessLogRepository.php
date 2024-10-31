<?php

namespace App\Repository;

use App\Entity\EmailProcessLog;
use Doctrine\Persistence\ManagerRegistry;

class EmailProcessLogRepository extends AbstractBaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailProcessLog::class);
    }
}
