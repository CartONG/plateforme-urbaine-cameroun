<?php
namespace App\Security\Voter;

use App\Entity\User;
use App\Model\Enums\UserRoles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{
    const EDIT = 'edit';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT]) && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var User $userToEdit */
        $userToEdit = $subject;

        if ($attribute === self::EDIT) {
            return $this->canEdit($user, $userToEdit);
        }

        return false;
    }

    private function canEdit(User $user, User $userToEdit): bool
    {
        if ($this->security->isGranted(UserRoles::ROLE_ADMIN)) {
            return true;
        }

        if ($userToEdit === $user) {
            return true;
        }

        return false;
    }
}
