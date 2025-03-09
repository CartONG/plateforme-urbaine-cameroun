<?php

namespace App\Controller\Comment;

use App\Entity\AppContentComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BulkDeleteCommentsController
{
    public function __invoke(Request $request, EntityManagerInterface $em)
    {
        $data = $request->toArray();
        $ids = $data['ids'] ?? null;

        if (!is_array($ids) || empty($ids)) {
            return new JsonResponse(['error' => 'Empty ids'], 400);
        }

        $qb = $em->createQueryBuilder();
        $qb->delete(AppContentComment::class, 'c')
           ->where($qb->expr()->in('c.id', ':ids'))
           ->setParameter('ids', $ids)
           ->getQuery()
           ->execute();

        return new JsonResponse(['success' => true]);
    }
}
