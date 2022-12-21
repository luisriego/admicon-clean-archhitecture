<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Usecase\User\CreateUser;

use App\Application\UseCase\User\CreateUser\CreateUser;
use App\Application\UseCase\User\CreateUser\Dto\CreateUserInputDto;
use App\Application\UseCase\User\CreateUser\Dto\CreateUserOutputDto;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserTest extends TestCase
{
    private const VALUES = [
        'name' => 'Peter',
        'email' => 'peter@api.com',
        'password' => 'fake123',
        'age' => 30,
    ];

    /**
     * @var UserRepositoryInterface|MockObject $userRepository
     */
    private UserRepositoryInterface $userRepository;
    private CreateUser $useCase;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->useCase = new CreateUser($this->userRepository);
    }

    public function testCreateCustomer(): void
    {
        $dto = CreateUserInputDto::create(
            self::VALUES['name'],
            self::VALUES['email'],
            self::VALUES['password'],
            self::VALUES['age'],
        );

        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(function (User $user): bool {
                    return $user->getName() === self::VALUES['name']
                        && $user->getEmail() === self::VALUES['email']
                        && $user->getPassword() === self::VALUES['password']
                        && $user->getAge() === self::VALUES['age']
                        && $user->isActive() === false;
                })
            );

        $responseDTO = $this->useCase->handle($dto);

        self::assertInstanceOf(CreateUserOutputDto::class, $responseDTO);
    }
}