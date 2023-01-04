<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Security\Voter;

use App\Domain\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

final class CondoVoter extends Voter
{
    public const ACTIVATE_CONDO = 'ACTIVATE_CONDO';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, $this->allowedAttributes(), true) && is_string($subject);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $tokenUser */
        $tokenUser = $token->getUser();

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if (\in_array($attribute, $this->allowedAttributes(), true)) {
            return $tokenUser->getId() === $subject;
        }

        return false;
    }

    private function allowedAttributes(): array
    {
        return [
            self::ACTIVATE_CONDO,
        ];
    }
}
