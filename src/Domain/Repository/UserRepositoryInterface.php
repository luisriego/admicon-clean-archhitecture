<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface UserRepositoryInterface
{
    public function add(User $user, bool $flush): void;

    public function save(User $user): void;

    public function remove(User $user, bool $flush): void;

    public function findOneByIdOrFail(string $id): User;

    public function findOneByEmail(string $id): ?User;

    public function findOneByEmailOrFail(string $id): User;

//    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void;
}
