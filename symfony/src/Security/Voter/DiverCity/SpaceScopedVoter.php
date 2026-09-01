<?php

namespace App\Security\Voter\DiverCity;

use App\Entity\DiverCity\BlockedPeriod;
use App\Entity\DiverCity\Booking;
use App\Entity\DiverCity\Space;
use App\Entity\User\User;
use App\Repository\DiverCity\SpaceAdminRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Autorise la gestion d'un Space (ou d'une Booking / BlockedPeriod rattachée
 * à un Space) si :
 *   - l'utilisateur a ROLE_ADMIN (admin plateforme "PDC", accès total), OU
 *   - l'utilisateur est déclaré SpaceAdmin de l'espace concerné.
 */
class SpaceScopedVoter extends Voter
{
    public const MANAGE_SPACE = 'MANAGE_SPACE';

    public function __construct(
        private SpaceAdminRepository $spaceAdminRepository,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (self::MANAGE_SPACE !== $attribute) {
            return false;
        }

        return null === $subject
                || $subject instanceof Space
                || $subject instanceof Booking
                || $subject instanceof BlockedPeriod;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        // ROLE_ADMIN (PDC) : accès total, toujours autorisé.
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // Pas d'objet précis (ex: GetCollection) : on vérifie juste que
        // l'utilisateur administre au moins un Space.
        if (null === $subject) {
            return $this->spaceAdminRepository->isAdminOfAnySpace($user);
        }


        $space = match (true) {
            $subject instanceof Booking, $subject instanceof BlockedPeriod => $subject->getSpace(),
            default => $subject,
        };

        if (null === $space) {
            return false;
        }

        // SpaceAdmin : autorisé uniquement sur SON espace.
        return $this->spaceAdminRepository->isAdminOf($user, $space);
    }
}
