<?php

namespace App\Security\Voter\DiverCity;

use App\Entity\DiverCity\Space;
use App\Entity\User\User;
use App\Model\Enums\UserRoles;
use App\Repository\DiverCity\SpaceAdminRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class KpiVoter extends Voter
{
    public const VIEW = 'KPI_VIEW';

    public function __construct(
        private Security $security,
        private SpaceAdminRepository $spaceAdminRepository,
    ) {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return self::VIEW === $attribute && $subject instanceof Space;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted(UserRoles::ROLE_ADMIN)) {
            return true;
        }

        return $this->spaceAdminRepository->isAdminOf($user, $subject);
    }
}