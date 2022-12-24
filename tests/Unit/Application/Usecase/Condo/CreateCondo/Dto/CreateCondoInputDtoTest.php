<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\Condo\CreateCondo\Dto;

use App\Application\UseCase\Condo\CreateCondo\Dto\CreateCondoInputDto;
use App\Domain\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CreateCondoInputDtoTest extends TestCase
{
    private const DATA = [
        'taxpayer' => '02024517000146',
        'fantasyName' => 'Condomínio Matilda',
    ];

    public function testCreate(): void
    {
        $dto = CreateCondoInputDto::create(
            self::DATA['taxpayer'],
            self::DATA['fantasyName'],
        );

        self::assertInstanceOf(CreateCondoInputDto::class, $dto);

        self::assertEquals(self::DATA['taxpayer'], $dto->taxpayer);
        self::assertEquals(self::DATA['fantasyName'], $dto->fantasyName);
    }

//    public function testTaxpayerHasToBeExact14char(): void
//    {
//        self::expectException(InvalidArgumentException::class);
//        self::expectExceptionMessage('Taxpayer number has to be exactly 14 characters');
//
//        CreateCondoInputDto::create(
//            '020245170001467',
//            self::DATA['fantasyName'],
//        );
//    }
}