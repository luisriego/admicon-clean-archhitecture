<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\Condo\GetCondoById;

use App\Application\UseCase\Condo\GetCondoById\Dto\GetCondoByIdInputDto;
use App\Application\UseCase\Condo\GetCondoById\Dto\GetCondoByIdOutputDto;
use App\Application\UseCase\Condo\GetCondoById\GetCondoById;
use App\Domain\Exception\ResourceNotFoundException;
use App\Domain\Model\Condo;
use App\Domain\Repository\CondoRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetCondoByIdTest extends TestCase
{
    private const CONDO_DATA = [
        'id' => '9b5c0b1f-09bf-4fed-acc9-fcaafc933a19',
        'taxpayer' => '02024517000150',
        'fantasyName' => 'Condomínio Fake',
    ];

    private CondoRepositoryInterface|MockObject $condoRepository;
    private GetCondoById $useCase;

    public function setUp(): void
    {
        $this->condoRepository = $this->createMock(CondoRepositoryInterface::class);
        $this->useCase = new GetCondoById($this->condoRepository);
    }

    public function testGetCondoById(): void
    {
        $condo = Condo::create(
            self::CONDO_DATA['taxpayer'],
            self::CONDO_DATA['fantasyName'],
        );

        $inputDto = GetCondoByIdInputDto::create($condo->getId());

        $this->condoRepository
            ->expects($this->once())
            ->method('findOneByIdOrFail')
            ->with($inputDto->id)
            ->willReturn($condo);

        $responseDTO = $this->useCase->handle($inputDto);

        self::assertInstanceOf(GetCondoByIdOutputDto::class, $responseDTO);

//        self::assertEquals(self::CONDO_DATA['id'], $responseDTO->id);
        self::assertEquals(self::CONDO_DATA['taxpayer'], $responseDTO->taxpayer);
        self::assertEquals(self::CONDO_DATA['fantasyName'], $responseDTO->fantasyName);
    }

    public function testGetCondoByIdException(): void
    {
        $inputDto = GetCondoByIdInputDto::create(self::CONDO_DATA['id']);

        $this->condoRepository
            ->expects($this->once())
            ->method('findOneByIdOrFail')
            ->with($inputDto->id)
            ->willThrowException(ResourceNotFoundException::createFromClassAndId(Condo::class, $inputDto->id));

        self::expectException(ResourceNotFoundException::class);

        $this->useCase->handle($inputDto);
    }
}