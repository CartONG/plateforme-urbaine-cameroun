<?php

namespace App\Services\State\Processor\Comments;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\AppContentComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class BulkUpdateCommentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ?Response
    {
        if (!$data instanceof BulkUpdateCommentDTO) {
            return null;
        }

        $comments = $this->entityManager
            ->getRepository(AppContentComment::class)
            ->findBy(['id' => $data->commentIds]);

        foreach ($comments as $comment) {
            $comment->setReadByAdmin($data->readByAdmin);
        }

        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
