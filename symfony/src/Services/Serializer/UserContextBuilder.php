<?php
namespace App\Services\Serializer;

use App\Entity\User;
use App\Model\Enums\UserRoles;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class UserContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;

    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;

        if ($resourceClass === User::class && isset($context['groups']) && $this->authorizationChecker->isGranted(UserRoles::ROLE_ADMIN) && false === $normalization) {
            $context['groups'][] = User::GROUP_ADMIN;
        }

        return $context;
    }
}