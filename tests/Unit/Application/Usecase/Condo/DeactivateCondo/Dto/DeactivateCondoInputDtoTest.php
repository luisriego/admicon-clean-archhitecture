<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\Condo\DeactivateCondo\Dto;

use App\Application\UseCase\Condo\ActivateCondo\Dto\ActivateCondoInputDto;
use PHPUnit\Framework\TestCase;

class DeactivateCondoInputDtoTest extends TestCase
{
    private const DATA = [
        'id' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae',
        'taxpayer' => '02024517000147',
        'fantasyName' => 'Condomínio do Edifício Fixure',
        'keys' => [],
    ];

    public function testCreateDTO(): void
    {
        $dto = ActivateCondoInputDto::create(
            self::DATA['id'],
        );

        self::assertInstanceOf(ActivateCondoInputDto::class, $dto);
    }
}