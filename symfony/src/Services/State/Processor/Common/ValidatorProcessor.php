<?php

namespace App\Services\State\Processor\Common;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\State\ProcessorInterface;
use App\Model\Enums\UserRoles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ValidatorProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!isset($data->isValidated) || false == $data->getIsValidated()) {
            if ($this->security->isGranted(UserRoles::ROLE_ADMIN)) {
                $data->setIsValidated(true);
            } else {
                if ($operation instanceof Patch) {
                    return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
                }
                $data->setIsValidated(false);
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
