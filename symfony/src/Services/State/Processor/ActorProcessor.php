<?php

namespace App\Services\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Actor;
use App\Services\State\Processor\Common\ValidatorProcessor;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ActorProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private ValidatorProcessor $validatorProcessor,
    ) {
    }

    /**
     * @param Actor $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $this->validatorProcessor->process($data, $operation, $uriVariables, $context);

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
