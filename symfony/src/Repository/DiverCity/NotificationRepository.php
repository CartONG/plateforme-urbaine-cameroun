<?php

namespace App\Repository\DiverCity;

use App\Entity\DiverCity\Notification;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * Notifications d'un utilisateur, les plus récentes en premier
     * (étude fonctionnelle §4 « Notifications et suivi »).
     *
     * @return Notification[]
     */
    public function findForUser(User $user): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.user = :user')
            ->setParameter('user', $user)
            ->orderBy('n.sentAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}