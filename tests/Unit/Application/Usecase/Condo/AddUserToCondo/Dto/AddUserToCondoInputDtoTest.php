<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\Condo\AddUserToCondo\Dto;

use App\Application\UseCase\Condo\AddUserToCondo\Dto\AddUserToCondoInputDto;
use App\Application\UseCase\Condo\UpdateCondo\Dto\UpdateCondoInputDto;
use App\Domain\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AddUserToCondoInputDtoTest extends TestCase
{
    private const DATA = [
        'id' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae',
        'userId' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6b10',
    ];

    public function testCreateDTO(): void
    {
        $dto = AddUserToCondoInputDto::create(
            self::DATA['id'],
            self::DATA['userId'],
        );

        self::assertInstanceOf(AddUserToCondoInputDto::class, $dto);
    }

    public function testCreateWithNullId(): void
    {
        self::expectException(InvalidArgumentException::class);

        AddUserToCondoInputDto::create(
            null,
            self::DATA['userId'],
        );
    }

    public function testCreateWithNullUserId(): void
    {
        self::expectException(InvalidArgumentException::class);

        AddUserToCondoInputDto::create(
            self::DATA['id'],
            null
        );
    }
}