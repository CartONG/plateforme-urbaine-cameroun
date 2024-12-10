<?php

namespace App\Repository\User;

use App\Entity\User\UserLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLike::class);
    }

    public function findContentLikesWithCountAndUserLike(?int $userId): array
    {
        $qb = $this->createQueryBuilder('ul')
            ->select('ul.contentId, COUNT(ul.id) as likeCount, 
                MAX(CASE WHEN ul.userId = :userId THEN ul.id ELSE 0 END) as userLikedId')
            ->setParameter('userId', $userId)
            ->groupBy('ul.contentId');

        return $qb->getQuery()->getResult();
    }
}
