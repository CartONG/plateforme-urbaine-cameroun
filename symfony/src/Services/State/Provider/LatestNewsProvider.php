<?php

namespace App\Services\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ActorRepository;
use App\Repository\ProjectRepository;

class LatestNewsProvider implements ProviderInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ActorRepository $actorRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $latestProjects = $this->projectRepository->findLatest();
        $latestActors = $this->actorRepository->findLatest();
        $latestNews = array_merge($latestProjects, $latestActors);
        usort($latestNews, function ($a, $b) {
            return $a['updatedAt'] < $b['updatedAt'];
        });
        $threeLatestNews = array_slice($latestNews, 0, 3);

        return $threeLatestNews;
    }
}
