<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\Condo\CreateCondo;

use App\Application\UseCase\Condo\CreateCondo\CreateCondo;
use App\Application\UseCase\Condo\CreateCondo\Dto\CreateCondoInputDto;
use App\Application\UseCase\Condo\CreateCondo\Dto\CreateCondoOutputDto;
use App\Domain\Model\Condo;
use App\Domain\Repository\CondoRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CreateCondoTest extends TestCase
{
    private const DATA = [
        'taxpayer' => '02024517000146',
        'fantasyName' => 'Condomínio Matilda',
    ];

    private readonly CondoRepositoryInterface|MockObject $condoRepository;
    private readonly CreateCondo $useCase;

    public function setUp(): void
    {
        $this->condoRepository = $this->createMock(CondoRepositoryInterface::class);
        $this->useCase = new CreateCondo($this->condoRepository);
    }

    public function testCreateCondo(): void
    {
        $dto = CreateCondoInputDto::create(
            self::DATA['taxpayer'],
            self::DATA['fantasyName'],
        );

        $name = self::DATA['taxpayer'];
        $email = self::DATA['fantasyName'];

        $this->condoRepository
            ->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(function (Condo $condo): bool {
                    return $condo->getTaxpayer() === self::DATA['taxpayer']
                        && $condo->getFantasyName() === self::DATA['fantasyName'];
                })
            );

        $responseDTO = $this->useCase->handle($dto);

        self::assertInstanceOf(CreateCondoOutputDto::class, $responseDTO);
    }
}