<?php

namespace App\Repository\User;

use App\Entity\User\UserPasswordToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserPasswordToken>
 */
class UserPasswordTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPasswordToken::class);
    }

    /**
     * @return UserPasswordToken[]
     */
    public function findExpiredTokens(): array
    {
        $qb = $this->createQueryBuilder('upt')
            ->where('upt.expiresAt < :now')
            ->setParameter('now', new \DateTime());

        return $qb->getQuery()->getResult();
    }
}
