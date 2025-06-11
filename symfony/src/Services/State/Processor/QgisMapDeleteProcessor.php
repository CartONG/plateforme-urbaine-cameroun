<?php

namespace App\Services\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\QgisMap;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class QgisMapDeleteProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data instanceof QgisMap) {
            return;
        }

        if (!$data->getAtlases()->isEmpty()) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Cannot delete QgisMap: it is associated with one or more Atlas');
        }

        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
