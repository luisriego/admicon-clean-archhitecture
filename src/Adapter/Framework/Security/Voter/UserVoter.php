<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Security\Voter;

use App\Domain\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

final class UserVoter extends Voter
{
    public const GET_USERS_CONDO = 'GET_USERS_CONDO';
    public const UPDATE_USER = 'UPDATE_USER';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array(
            $attribute, $this->allowedAttributes(), true)
            && User::class === get_class($subject);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // ROLE_SUPER_ADMIN can do anything! The power!
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            return true;
        }

        /** @var User $tokenUser */
        $tokenUser = $token->getUser();

        if (self::GET_USERS_CONDO === $attribute) {
            foreach ($subject as $user) {
                if ($tokenUser->getId() === $user->getId()) {
                    return true;
                }
            }
        }

        // ROLE_SYNDIC can update another users in his own Condominium and itself
        if (\in_array($attribute, $this->allowedAttributes(), true)) {
            if ($this->security->isGranted('ROLE_ADMIN')) {
                return true;
            }

            if ($this->security->isGranted('ROLE_SYNDIC')) {
                return $tokenUser->getCondo() === $subject->getCondo();
            }

            return $tokenUser->getId() === $subject->getId();
        }

        return false;
    }

    private function allowedAttributes(): array
    {
        return [
            self::GET_USERS_CONDO,
            self::UPDATE_USER,
        ];
    }
}
