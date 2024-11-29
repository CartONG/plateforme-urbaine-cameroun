<?php

namespace App\Services\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Kpi;
use App\Enum\KpiKey;
use App\Repository\ActorRepository;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;

class GlobalKpiProvider implements ProviderInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ActorRepository $actorRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $kpiKeys = [KpiKey::ACTOR, KpiKey::PROJECT, KpiKey::DATA, KpiKey::MEMBER];
        $kpis = [];
        foreach ($kpiKeys as $kpiKey) {
            $kpi = new Kpi();
            $kpi->setKey($kpiKey);

            switch ($kpiKey) {
                case KpiKey::ACTOR:
                    $kpi->setCount($this->actorRepository->count());
                    break;
                case KpiKey::PROJECT:
                    $kpi->setCount($this->projectRepository->count());
                    break;
                case KpiKey::MEMBER:
                    $kpi->setCount($this->userRepository->count());
                    break;
                case KpiKey::DATA:
                    $kpi->setCount(0); // TODO once data is available
                    break;
            }
            $kpis[] = $kpi;
        }

        return $kpis;
    }
}
