<?php
namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Actor;
use App\Model\Enums\UserRoles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ActorVoter extends Voter
{
    const EDIT = 'edit';
    const CREATE = 'create';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::CREATE]) && $subject instanceof Actor;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Actor $actor */
        $actor = $subject;

        if ($attribute === self::EDIT) {
            return $this->canEdit($user, $actor);
        }

        return false;
    }

    private function canEdit(User $user, Actor $actor): bool
    {
        if ($this->security->isGranted(UserRoles::ROLE_ADMIN)) {
            return true;
        }

        if ($this->security->isGranted(UserRoles::ROLE_EDITOR_ACTORS)) {
            return $actor->getCreatedBy() === $user;
        }

        return false;
    }
}
