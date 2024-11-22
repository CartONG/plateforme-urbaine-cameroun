<?php

namespace App\Services\State\Processor\Common;

use App\Model\Enums\UserRoles;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ValidatorProcessor implements ProcessorInterface
{
    
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security
    )
    {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!isset($data->isValidated) || $data->getIsValidated() == false) {
            if ($this->security->isGranted(UserRoles::ROLE_ADMIN)) {
                $data->setIsValidated(true);
            } else {
                $data->setIsValidated(false);
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}

