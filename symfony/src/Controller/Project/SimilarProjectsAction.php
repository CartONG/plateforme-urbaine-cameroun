<?php

namespace App\Controller\Project;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class SimilarProjectsAction extends AbstractController
{
    public function __construct(
        private ProjectRepository $projectRepository,
    ) {
    }

    public function __invoke(Project $project): array
    {
        $similarProjects = $this->projectRepository->findTwoSimilarProjectsByThematics($project);

        return $similarProjects;
    }
}
