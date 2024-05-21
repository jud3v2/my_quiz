<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminVoter extends Voter
{
        public const EDIT = 'ADMIN_CAN_EDIT';
        public const VIEW = 'ADMIN_CAN_VIEW';
        public const DELETE = 'ADMIN_CAN_DELETE';
        public const CREATE = 'ADMIN_CAN_CREATE';

        protected function supports(string $attribute, mixed $subject): bool
        {
                // replace with your own logic
                // https://symfony.com/doc/current/security/voters.html
                return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE, self::CREATE]);
        }

        protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
        {
                $user = $token->getUser();

                if (!$user instanceof UserInterface) {
                        return false;
                }

                return match ($attribute) {
                        self::EDIT, self::VIEW, self::DELETE, self::CREATE => $this->isAdmin($user),
                        default => false,
                };
        }

        private function isAdmin(UserInterface $user): bool
        {
                return in_array('ROLE_ADMIN', $user->getRoles());
        }
}
