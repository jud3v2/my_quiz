<?php

namespace App\Security\Voter;

use App\Entity\Categorie;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CategorieVoter extends Voter
{
        public const EDIT = 'QUIZ_EDIT';
        public const VIEW = 'QUIZ_VIEW';
        public const DELETE = 'QUIZ_DELETE';
        public const CREATE = 'QUIZ_CREATE';

        protected function supports(string $attribute, mixed $subject): bool
        {
                return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE, self::CREATE])
                    && $subject instanceof Categorie;
        }

        protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
        {
                $user = $token->getUser();

                // if the user is anonymous, do not grant access
                if (!$user instanceof UserInterface) {
                        return false;
                }

                // ... (check conditions and return true to grant permission) ...
                return match ($attribute) {
                        self::EDIT => $this->canEdit($subject, $user),
                        self::VIEW => $this->canView($subject, $user),
                        self::DELETE => $this->canDelete($subject, $user),
                        self::CREATE => $this->canCreate($subject, $user),
                        default => false,
                };

        }

        private function canView(Categorie $categorie, UserInterface $user): bool
        {
                return $user === $categorie->getOwner() ||
                    in_array('ROLE_ADMIN', $user->getRoles()) ||
                    in_array('ROLE_USER', $user->getRoles()) && $user->isVerified();
        }

        private function canDelete(Categorie $categorie, UserInterface $user): bool
        {
                return $user === $categorie->getOwner() ||
                    in_array('ROLE_ADMIN', $user->getRoles());
        }

        private function canCreate(Categorie $categorie, UserInterface $user): bool
        {
                return ($user->isVerified() && in_array('ROLE_USER', $user->getRoles())) ||
                    in_array('ROLE_ADMIN', $user->getRoles());
        }


        private function canEdit(Categorie $categorie, UserInterface $user): bool
        {
                return $user->getUserIdentifier() === $categorie->getOwner()->getUserIdentifier() ||
                    in_array('ROLE_ADMIN', $user->getRoles());
        }
}
