<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\User\GetUserById;

use App\Application\UseCase\User\GetUserById\Dto\GetUserByIdInputDto;
use App\Application\UseCase\User\GetUserById\Dto\GetUserByIdOutputDto;
use App\Application\UseCase\User\GetUserById\GetUserById;
use App\Domain\Exception\ResourceNotFoundException;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetUserByIdTest extends TestCase
{
    private const USER_DATA = [
        'id' => '9b5c0b1f-09bf-4fed-acc9-fcaafc933a19',
        'name' => 'Peter',
        'email' => 'peter@api.com',
        'password' => 'Fake123',
        'age' => 30,
    ];

    private UserRepositoryInterface|MockObject $userRepository;

    private GetUserById $useCase;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);

        $this->useCase = new GetUserById($this->userRepository);
    }

    public function testGetUserById(): void
    {
        $inputDto = GetUserByIdInputDto::create(self::USER_DATA['id']);

        $user = User::create(
            self::USER_DATA['id'],
            self::USER_DATA['name'],
            self::USER_DATA['email'],
            self::USER_DATA['password'],
            self::USER_DATA['age'],
        );

        $this->userRepository
            ->expects($this->once())
            ->method('findOneByIdOrFail')
            ->with($inputDto->id)
            ->willReturn($user);

        $responseDTO = $this->useCase->handle($inputDto);

        self::assertInstanceOf(GetUserByIdOutputDto::class, $responseDTO);

        self::assertEquals(self::USER_DATA['id'], $responseDTO->id);
        self::assertEquals(self::USER_DATA['name'], $responseDTO->name);
        self::assertEquals(self::USER_DATA['email'], $responseDTO->email);
        self::assertEquals(self::USER_DATA['password'], $responseDTO->password);
        self::assertEquals(self::USER_DATA['age'], $responseDTO->age);
    }

    public function testGetUserByIdException(): void
    {
        $inputDto = GetUserByIdInputDto::create(self::USER_DATA['id']);

        $this->userRepository
            ->expects($this->once())
            ->method('findOneByIdOrFail')
            ->with($inputDto->id)
            ->willThrowException(ResourceNotFoundException::createFromClassAndId(User::class, $inputDto->id));

        self::expectException(ResourceNotFoundException::class);

        $this->useCase->handle($inputDto);
    }
}