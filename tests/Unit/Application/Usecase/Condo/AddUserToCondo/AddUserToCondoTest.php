<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\Condo\AddUserToCondo;

use App\Application\UseCase\Condo\AddUserToCondo\AddUserToCondo;
use App\Application\UseCase\Condo\AddUserToCondo\Dto\AddUserToCondoInputDto;
use App\Application\UseCase\Condo\AddUserToCondo\Dto\AddUserToCondoOutputDto;
use App\Domain\Model\Condo;
use App\Domain\Repository\CondoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class AddUserToCondoTest extends WebTestCase
{
    private const DATA = [
        'id' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae',
        'taxpayer' => '02024517000147',
        'fantasyName' => 'Condominio Sin Acento',
    ];

    private const DATA_TO_UPDATE = [
        'id' => '37fb348b-891a-4b1c-a4e4-a4a68a3c6bae',
        'userId' => 'd2f585f6-dd6d-4ca8-b338-b85c768a20c1',
    ];

    private readonly CondoRepositoryInterface|MockObject $condoRepository;
    private readonly UserRepositoryInterface|MockObject $userRepository;
    private readonly Security|MockObject $security;
    private readonly AuthorizationCheckerInterface $checker;
    private AddUserToCondoInputDto $inputDto;
    private readonly AddUserToCondo $useCase;

    public function setUp(): void
    {
        $this->condoRepo = $this->createMock(CondoRepositoryInterface::class);
        $this->userRepo = $this->createMock(UserRepositoryInterface::class);
        $this->security = $this->createMock(Security::class);
        $this->checker = $this->createMock(AuthorizationCheckerInterface::class);

        $this->inputDto = AddUserToCondoInputDto::create(
            self::DATA_TO_UPDATE['id'],
            self::DATA_TO_UPDATE['userId'],
        );

        $this->useCase = new AddUserToCondo($this->condoRepo, $this->userRepo, $this->security, $this->checker);
    }

    public function testAddUserToCondo(): void
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
                    return $condo->getUsers()->count() === 1;
                })
            );

        $responseDto = $this->useCase->handle($this->inputDto);

        self::assertInstanceOf(AddUserToCondoOutputDto::class, $responseDto);
    }
}