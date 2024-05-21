<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    public const EDIT = 'USER_EDIT';
    public const VIEW = 'USER_VIEW';
    public const DELETE = 'USER_DELETE';
    public const CREATE = 'USER_CREATE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE, self::CREATE])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

            return match ($attribute) {
                    self::EDIT => $this->canEdit($subject, $user),
                    self::VIEW => $this->canView($subject, $user),
                    self::CREATE => $this->canCreate($subject, $user),
                    self::DELETE => $this->canDelete($subject, $user),
                    default => false,
            };
    }

    private function canEdit(User $_user, UserInterface $user): bool
    {
        return $_user === $user ||
            in_array('ROLE_ADMIN', $user->getRoles());
    }

    private function canView(User $_user, UserInterface $user): bool
    {
        return $_user === $user ||
            in_array('ROLE_ADMIN', $user->getRoles());
    }

    private function canDelete(User $_user, UserInterface $user): bool
    {
        return $_user === $user ||
            in_array('ROLE_ADMIN', $user->getRoles());
    }

    private function canCreate(User $_user, UserInterface $user): bool
    {
        return $_user === $user ||
            in_array('ROLE_ADMIN', $user->getRoles());
    }
}
