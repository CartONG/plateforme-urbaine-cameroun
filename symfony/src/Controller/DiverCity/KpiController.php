<?php

namespace App\Controller\DiverCity;

use App\Repository\DiverCity\SpaceRepository;
use App\Services\Service\DiverCity\KpiCalculator;
use App\Security\Voter\DiverCity\KpiVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class KpiController extends AbstractController
{
    public function __construct(
        private KpiCalculator $kpiCalculator,
        private SpaceRepository $spaceRepository,
    ) {
    }

    #[Route('/api/divercity/kpis/{spaceId}', name: 'divercity_kpis', methods: ['GET'])]
    public function __invoke(string $spaceId, Request $request): JsonResponse
    {
        $space = $this->spaceRepository->find($spaceId) ?? throw $this->createNotFoundException();
        $this->denyAccessUnlessGranted(KpiVoter::VIEW, $space);

        $from = $request->query->has('from')
            ? new \DateTimeImmutable($request->query->get('from'))
            : new \DateTimeImmutable('-30 days');
        $to = $request->query->has('to')
            ? new \DateTimeImmutable($request->query->get('to'))
            : new \DateTimeImmutable();

        return $this->json($this->kpiCalculator->computeForSpace($space, $from, $to));
    }
}