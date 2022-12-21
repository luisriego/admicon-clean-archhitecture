<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\CreateUser;

use App\Application\UseCase\User\CreateUser\Dto\CreateUserInputDto;
use App\Application\UseCase\User\CreateUser\Dto\CreateUserOutputDto;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObjects\Uuid;

class CreateUser
{
    public function __construct(private readonly UserRepositoryInterface $repository)
    {
    }

    public function handle(CreateUserInputDto $inputDto): CreateUserOutputDto
    {
        $user = User::create(
            Uuid::random()->value(),
            $inputDto->name,
            $inputDto->email,
            $inputDto->password,
            $inputDto->age
        );

        $this->repository->save($user, true);

        return new CreateUserOutputDto((string) $user->getId());
    }
}
