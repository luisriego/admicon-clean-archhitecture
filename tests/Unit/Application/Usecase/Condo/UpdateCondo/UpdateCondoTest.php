<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\Condo\UpdateCondo;

use App\Application\UseCase\Condo\UpdateCondo\Dto\UpdateCondoInputDto;
use App\Application\UseCase\Condo\UpdateCondo\Dto\UpdateCondoOutputDto;
use App\Application\UseCase\Condo\UpdateCondo\UpdateCondo;
use App\Domain\Model\Condo;
use App\Domain\Repository\CondoRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateCondoTest extends TestCase
{
    private const DATA = [
        'id' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae',
        'taxpayer' => '02024517000147',
        'fantasyName' => 'Condominio Sin Acento',
    ];

    private const DATA_TO_UPDATE = [
        'id' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae',
        'taxpayer' => '02024517000146',
        'fantasyName' => 'Condomínio con acento',
        'keys' => [
            'taxpayer',
            'fantasyName'
        ],
    ];

    private CondoRepositoryInterface|MockObject $condoRepo;
    private UpdateCondoInputDto $inputDto;
    private UpdateCondo $useCase;

    public function setUp(): void
    {
        $this->condoRepo = $this->createMock(CondoRepositoryInterface::class);

        $this->inputDto = UpdateCondoInputDto::create(
            self::DATA_TO_UPDATE['id'],
            self::DATA_TO_UPDATE['taxpayer'],
            self::DATA_TO_UPDATE['fantasyName'],
            self::DATA_TO_UPDATE['keys']
        );

        $this->useCase = new UpdateCondo($this->condoRepo);
    }

    public function testUpdateCondo(): void
    {
        $condo = Condo::create(
            self::DATA['taxpayer'],
            self::DATA['fantasyName'],
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
                    return $condo->getTaxpayer() === $this->inputDto->taxpayer
                        && $condo->getFantasyName() === $this->inputDto->fantasyName;
                })
            );

        $responseDto = $this->useCase->handle($this->inputDto);

        self::assertInstanceOf(UpdateCondoOutputDto::class, $responseDto);

        self::assertEquals($this->inputDto->taxpayer, $responseDto->condoData['taxpayer']);
        self::assertEquals($this->inputDto->fantasyName, $responseDto->condoData['fantasyName']);
    }

}