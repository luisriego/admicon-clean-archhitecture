<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Usecase\User\GetUserById\Dto;

use App\Application\UseCase\User\GetUserById\Dto\GetUserByIdInputDto;
use App\Domain\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class GetUserByIdInputDtoTest extends TestCase
{
    private const USER_ID = '9b5c0b1f-09bf-4fed-acc9-fcaafc933a19';

    public function testGetUserByIdInputDTO(): void
    {
        $dto = GetUserByIdInputDto::create(self::USER_ID);

        self::assertInstanceOf(GetUserByIdInputDto::class, $dto);
        self::assertEquals(self::USER_ID, $dto->id);
    }

    public function testCreateGetUserByIdInputDTOWithNullValue(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Invalid arguments [id]');

        GetUserByIdInputDTO::create(null);
    }
}