<?php

namespace App\Repository\DiverCity;

use App\Entity\DiverCity\BookingAttachment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookingAttachment>
 */
class BookingAttachmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookingAttachment::class);
    }
}
