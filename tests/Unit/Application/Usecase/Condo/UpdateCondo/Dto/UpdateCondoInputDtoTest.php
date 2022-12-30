<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\Condo\UpdateCondo\Dto;

use App\Application\UseCase\Condo\UpdateCondo\Dto\UpdateCondoInputDto;
use App\Domain\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UpdateCondoInputDtoTest extends TestCase
{
    private const DATA = [
        'id' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae',
        'taxpayer' => '02024517000147',
        'fantasyName' => 'Condomínio do Edifício Fixure',
        'keys' => [],
    ];

    public function testCreateDTO(): void
    {
        $dto = UpdateCondoInputDto::create(
            self::DATA['id'],
            self::DATA['taxpayer'],
            self::DATA['fantasyName'],
            self::DATA['keys']
        );

        self::assertInstanceOf(UpdateCondoInputDto::class, $dto);
    }

    public function testCreateWithNullId(): void
    {
        self::expectException(InvalidArgumentException::class);

        UpdateCondoInputDto::create(
            null,
            self::DATA['taxpayer'],
            self::DATA['fantasyName'],
            self::DATA['keys']
        );
    }

    public function testCreateWithTooShortTaxpayerLength(): void
    {
        self::expectException(InvalidArgumentException::class);

        UpdateCondoInputDto::create(
            self::DATA['id'],
            '0202451700014',
            self::DATA['fantasyName'],
            self::DATA['keys']
        );
    }

    public function testCreateWithTooLongTaxpayerLength(): void
    {
        self::expectException(InvalidArgumentException::class);

        UpdateCondoInputDto::create(
            self::DATA['id'],
            '020245170001478',
            self::DATA['fantasyName'],
            self::DATA['keys']
        );
    }

    public function testCreateWithInvalidFantasyNameLength(): void
    {
        self::expectException(InvalidArgumentException::class);

        UpdateCondoInputDto::create(
            self::DATA['id'],
            self::DATA['taxpayer'],
            'cond',
            self::DATA['keys']
        );
    }
}