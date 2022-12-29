<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\User\ActivateUser;

use App\Application\UseCase\User\ActivateUser\ActivateUser;
use App\Application\UseCase\User\ActivateUser\Dto\ActivateUserInputDto;
use App\Application\UseCase\User\ActivateUser\Dto\ActivateUserOutputDto;
use App\Application\UseCase\User\UpdateUSer\Dto\UpdateUserInputDto;
use App\Application\UseCase\User\UpdateUSer\Dto\UpdateUserOutputDto;
use App\Application\UseCase\User\UpdateUSer\UpdateUser;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ActivateUserTest extends TestCase
{
    private const DATA = [
        'id' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae',
        'name' => 'Peter',
        'email' => 'peter@api.com',
        'password' => 'fake123',
        'age' => 30,
    ];

    private const DATA_FOR_ACTIVATION = [
        'id' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae',
        'token' => '9a71ff5bb2a902f084a2976e58fd994e8833b9ca',
    ];

    private UserRepositoryInterface|MockObject $userRepository;
    private ActivateUserInputDto $inputDto;
    private ActivateUser $useCase;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);

        $this->useCase = new ActivateUser($this->userRepository);
    }

    public function testActivateUser(): void
    {
        $user = User::create(
            self::DATA['id'],
            self::DATA['name'],
            self::DATA['email'],
            self::DATA['password'],
            self::DATA['age'],
        );

        $this->inputDto = ActivateUserInputDto::create(
            self::DATA_FOR_ACTIVATION['id'],
            $user->getToken(),
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
                    return $user->getToken() === '';
                })
            );

        $responseDto = $this->useCase->handle($this->inputDto);

        self::assertInstanceOf(ActivateUserOutputDto::class, $responseDto);

        self::assertEquals($responseDto->userData['token'], '');
    }

}