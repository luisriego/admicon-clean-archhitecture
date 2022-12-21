<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Usecase\User\UpdateUser\Dto;

use App\Application\UseCase\User\UpdateUSer\Dto\UpdateUserInputDto;
use App\Domain\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UpdateUserInputDtoTest extends TestCase
{
    private const DATA = [
        'id' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae',
        'name' => 'Brian',
        'email' => 'peter@api.com',
        'password' => 'password123',
        'age' => 20,
        'keys' => [],
    ];

    public function testCreateDTO(): void
    {
        $dto = UpdateUserInputDto::create(
            self::DATA['id'],
            self::DATA['name'],
            self::DATA['email'],
            self::DATA['password'],
            self::DATA['age'],
            self::DATA['keys']
        );

        self::assertInstanceOf(UpdateUserInputDto::class, $dto);
    }

    public function testCreateWithNullId(): void
    {
        self::expectException(InvalidArgumentException::class);

        UpdateUserInputDto::create(
            null,
            self::DATA['name'],
            self::DATA['email'],
            self::DATA['password'],
            self::DATA['age'],
            self::DATA['keys']
        );
    }

    public function testCreateWithInvalidAge(): void
    {
        self::expectException(InvalidArgumentException::class);

        UpdateUserInputDto::create(
            self::DATA['id'],
            self::DATA['name'],
            self::DATA['email'],
            self::DATA['password'],
            10,
            self::DATA['keys']
        );
    }
}