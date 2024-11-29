<?php

namespace App\Services\State\Processor\Common;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Services\Service\NominatimService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class GeoDataProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private NominatimService $nominatimService,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (isset($data->getOsmData()['osmId']) && isset($data->getOsmData()['osmType'])) {
            $geoData = $this->nominatimService->getOsmPlaceByIdandType(
                $data->getOsmData()['osmId'],
                $data->getOsmData()['osmType'],
            );

            if (null !== $geoData) {
                $data->setGeoData($geoData);
            } else {
                throw new \Exception('Place not found');
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
