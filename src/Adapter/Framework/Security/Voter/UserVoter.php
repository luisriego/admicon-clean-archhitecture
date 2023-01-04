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

    private function searchForId($search_value, $array, $id_path)
    {
        // Iterating over main array
        foreach ($array as $key1 => $val1) {
            $temp_path = $id_path;

            // Adding current key to search path
            array_push($temp_path, $key1);

            // Check if this value is an array
            // with atleast one element
            if (is_array($val1) and count($val1)) {
                // Iterating over the nested array
                foreach ($val1 as $key2 => $val2) {
                    if ($val2 == $search_value) {
                        // Adding current key to search path
                        array_push($temp_path, $key2);

                        return join(' --> ', $temp_path);
                    }
                }
            } elseif ($val1 == $search_value) {
                return join(' --> ', $temp_path);
            }
        }

        return null;
    }
}
