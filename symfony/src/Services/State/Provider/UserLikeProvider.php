<?php

namespace App\Services\State\Provider;

use App\Repository\UserRepository;
use ApiPlatform\Metadata\Operation;
use App\Repository\UserLikeRepository;
use ApiPlatform\State\ProviderInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserLikeProvider implements ProviderInterface
{
    private UserLikeRepository $userLikeRepository;
    private Security $security;

    public function __construct(UserLikeRepository $userLikeRepository, Security $security)
    {
        $this->userLikeRepository = $userLikeRepository;
        $this->security = $security;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();

        // Get User ID
        $userId = null;
        if ($user) {
            /** @var User $user */
            $userId = $user->getId();
        }

        // Get Likes list with ID of the like if user has liked the entity
        $results = $this->userLikeRepository->findContentLikesWithCountAndUserLike($userId);

        $output = [];
        foreach ($results as $result) {
            $output[$result['contentId']->toRfc4122()] = [
                'count' => $result['likeCount'],
                'likeId' => $result['userLikedId'],
            ];
        }

        return new JsonResponse($output);
    }
}
