<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\Condo\ActivateCondo;

use App\Application\UseCase\Condo\ActivateCondo\ActivateCondo;
use App\Application\UseCase\Condo\ActivateCondo\Dto\ActivateCondoInputDto;
use App\Application\UseCase\Condo\ActivateCondo\Dto\ActivateCondoOutputDto;
use App\Domain\Model\Condo;
use App\Domain\Repository\CondoRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ActivateCondoTest extends TestCase
{
    private const DATA = [
        'id' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae',
        'taxpayer' => '02024517000147',
        'fantasyName' => 'Condominio Sin Acento',
    ];

    private const DATA_FOR_ACTIVATION = [
        'id' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae',
    ];

    private CondoRepositoryInterface|MockObject $condoRepo;
    private ActivateCondoInputDto $inputDto;
    private ActivateCondo $useCase;

    public function setUp(): void
    {
        $this->condoRepo = $this->createMock(CondoRepositoryInterface::class);

        $this->useCase = new ActivateCondo($this->condoRepo);
    }

    public function testActivateCondo(): void
    {
        $condo = Condo::create(
            self::DATA['taxpayer'],
            self::DATA['fantasyName'],
        );

        $this->inputDto = ActivateCondoInputDto::create(
            self::DATA_FOR_ACTIVATION['id'],
        );

        $this->condoRepo
            ->expects($this->once())
            ->method('findOneByIdOrFail')
            ->with($this->inputDto->id)
            ->willReturn($condo);

        $this->condoRepo
            ->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(function (Condo $condo): bool {
                    return $condo->getTaxpayer() === self::DATA['taxpayer']
                        && $condo->getFantasyName() === self::DATA['fantasyName'];
                })
            );

        $responseDto = $this->useCase->handle($this->inputDto);

        self::assertInstanceOf(ActivateCondoOutputDto::class, $responseDto);

        self::assertEquals($responseDto->condoData['isActive'], 1);
    }

}