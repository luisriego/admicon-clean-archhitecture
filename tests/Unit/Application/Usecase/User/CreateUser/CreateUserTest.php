<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Usecase\User\CreateUser;

use App\Application\UseCase\User\CreateUser\CreateUser;
use App\Application\UseCase\User\CreateUser\Dto\CreateUserInputDto;
use App\Application\UseCase\User\CreateUser\Dto\CreateUserOutputDto;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\PasswordHasherInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CreateUserTest extends TestCase
{
    private const VALUES = [
        'name' => 'Peter',
        'email' => 'peter@api.com',
        'password' => 'fake123',
        'age' => 30,
    ];

    private readonly UserRepositoryInterface|MockObject $userRepository;
    private readonly PasswordHasherInterface $passwordHasher;
    private readonly CreateUser $useCase;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->passwordHasher = $this->createMock(PasswordHasherInterface::class);
        $this->useCase = new CreateUser($this->userRepository, $this->passwordHasher);
    }

    public function testCreateUser(): void
    {
        $dto = CreateUserInputDto::create(
            self::VALUES['name'],
            self::VALUES['email'],
            self::VALUES['password'],
            self::VALUES['age'],
        );

        $name = self::VALUES['name'];
        $email = self::VALUES['email'];
        $password = self::VALUES['password'];

        $this->passwordHasher
            ->expects($this->once())
            ->method('hashPasswordForUser')
            ->with(
                $this->callback(function (User $user) use ($name, $email): bool {
                    return $user->getName() === $name
                        && $user->getEmail() === $email;
                }),
                $this->callback(function (string $plainPassword) use ($password): bool {
                    return $plainPassword === $password;
                })
            )
            ->willReturn('super-encrypted-password');

        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(function (User $user): bool {
                    return $user->getName() === self::VALUES['name']
                        && $user->getEmail() === self::VALUES['email']
                        && $user->getPassword() === 'super-encrypted-password'
                        && $user->getAge() === self::VALUES['age']
                        && $user->isActive() === false;
                })
            );

        $responseDTO = $this->useCase->handle($dto);

        self::assertInstanceOf(CreateUserOutputDto::class, $responseDTO);
    }
}