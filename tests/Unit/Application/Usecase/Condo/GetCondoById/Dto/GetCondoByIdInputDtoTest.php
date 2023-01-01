<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\Condo\GetCondoById\Dto;

use App\Application\UseCase\Condo\GetCondoById\Dto\GetCondoByIdInputDto;
use App\Domain\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class GetCondoByIdInputDtoTest extends TestCase
{
    private const CONDO_ID = '9b5c0b1f-09bf-4fed-acc9-fcaafc933a19';

    public function testGetCondoByIdInputDTO(): void
    {
        $dto = GetCondoByIdInputDto::create(self::CONDO_ID);

        self::assertInstanceOf(GetCondoByIdInputDto::class, $dto);
        self::assertEquals(self::CONDO_ID, $dto->id);
    }

    public function testCreateGetCondoByIdInputDTOWithNullValue(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Invalid arguments [id]');

        GetCondoByIdInputDTO::create(null);
    }
}