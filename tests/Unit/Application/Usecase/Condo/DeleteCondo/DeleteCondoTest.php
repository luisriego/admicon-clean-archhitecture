<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\Condo\DeleteCondo;


use App\Application\UseCase\Condo\DeleteCondo\DeleteCondo;
use App\Application\UseCase\Condo\DeleteCondo\Dto\DeleteCondoInputDto;
use App\Domain\Model\Condo;
use App\Domain\Repository\CondoRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteCondoTest extends TestCase
{
    private CondoRepositoryInterface|MockObject $condoRepository;
    private DeleteCondo $useCase;

    public function setUp(): void
    {
        $this->condoRepository = $this->createMock(CondoRepositoryInterface::class);

        $this->useCase = new DeleteCondo($this->condoRepository);
    }

    public function testDeleteCondo(): void
    {
        $condo = Condo::create(
            '02024517000146',
            'condominium Fake',
        );

        $inputDTO = DeleteCondoInputDto::create($condo->getId());

        $this->condoRepository
            ->expects($this->once())
            ->method('findOneByIdOrFail')
            ->with($condo->getId())
            ->willReturn($condo);

        $this->condoRepository
            ->expects($this->once())
            ->method('remove')
            ->with($condo);

        $this->useCase->handle($inputDTO);
    }
}