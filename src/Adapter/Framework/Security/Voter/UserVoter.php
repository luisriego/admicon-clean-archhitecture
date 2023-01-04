<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Security\Voter;

use App\Domain\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class UserVoter extends Voter
{
    public const GET_USERS_CONDO = 'GET_USERS_CONDO';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, $this->allowedAttributes(), true) && is_array($subject);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $tokenUser */
        $tokenUser = $token->getUser();

        if (self::GET_USERS_CONDO === $attribute) {
            foreach ($subject as $user) {
                if ($tokenUser->getId() === $user->getId()) {
                    return true;
                }
            }
        }

        return false;
    }

    private function allowedAttributes(): array
    {
        return [
            self::GET_USERS_CONDO,
        ];
    }
}