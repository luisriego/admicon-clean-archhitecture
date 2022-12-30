<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\User\DeleteUser;

use App\Application\UseCase\User\DeleteUser\DeleteUser;
use App\Application\UseCase\User\DeleteUser\Dto\DeleteUserInputDto;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteUserTest extends TestCase
{
    private UserRepositoryInterface|MockObject $userRepository;
    private DeleteUser $useCase;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);

        $this->useCase = new DeleteUser($this->userRepository);
    }

    public function testDeleteUser(): void
    {
        $userId = '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae';

        $user = User::create(
            $userId,
            'Juan',
            'peter@api.com',
            'Fake street 123',
            30,
            '37fb348b-891a-4b1c-a4e4-a4a68a3c6111',
        );

        $inputDTO = DeleteUserInputDto::create($userId);

        $this->userRepository
            ->expects($this->once())
            ->method('findOneByIdOrFail')
            ->with($userId)
            ->willReturn($user);

        $this->userRepository
            ->expects($this->once())
            ->method('remove')
            ->with($user);

        $this->useCase->handle($inputDTO);
    }
}