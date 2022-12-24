<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\User\UpdateUser;

use App\Application\UseCase\User\UpdateUSer\Dto\UpdateUserInputDto;
use App\Application\UseCase\User\UpdateUSer\Dto\UpdateUserOutputDto;
use App\Application\UseCase\User\UpdateUSer\UpdateUser;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateUserTest extends TestCase
{
    private const DATA = [
        'id' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae',
        'name' => 'Peter',
        'email' => 'peter@api.com',
        'password' => 'fake123',
        'age' => 30,
    ];

    private const DATA_TO_UPDATE = [
        'id' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae',
        'name' => 'Brian',
        'email' => 'brian@api.com',
        'password' => 'anotherfake123',
        'age' => 40,
        'keys' => [
            'name',
            'password',
            'age'
        ],
    ];

    private UserRepositoryInterface|MockObject $userRepository;
    private UpdateUserInputDto $inputDto;
    private UpdateUser $useCase;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);

        $this->inputDto = UpdateUserInputDto::create(
            self::DATA_TO_UPDATE['id'],
            self::DATA_TO_UPDATE['name'],
            self::DATA_TO_UPDATE['email'],
            self::DATA_TO_UPDATE['password'],
            self::DATA_TO_UPDATE['age'],
            self::DATA_TO_UPDATE['keys']
        );

        $this->useCase = new UpdateUser($this->userRepository);
    }

    public function testUpdateUser(): void
    {
        $user = User::create(
            self::DATA['id'],
            self::DATA['name'],
            self::DATA['email'],
            self::DATA['password'],
            self::DATA['age'],
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findOneByIdOrFail')
            ->with($this->inputDto->id)
            ->willReturn($user);

        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(function (User $user): bool {
                    return $user->getName() === $this->inputDto->name
                        && $user->getPassword() === $this->inputDto->password
                        && $user->getAge() === $this->inputDto->age;
                })
            );

        $responseDto = $this->useCase->handle($this->inputDto);

        self::assertInstanceOf(UpdateUserOutputDto::class, $responseDto);

        self::assertEquals($this->inputDto->name, $responseDto->userData['name']);
        self::assertEquals($this->inputDto->password, $responseDto->userData['password']);
        self::assertEquals($this->inputDto->age, $responseDto->userData['age']);
    }

}