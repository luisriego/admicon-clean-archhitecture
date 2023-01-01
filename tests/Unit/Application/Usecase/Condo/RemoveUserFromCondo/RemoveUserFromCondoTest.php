<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Usecase\Condo\RemoveUserFromCondo;

use App\Application\UseCase\Condo\AddUserToCondo\AddUserToCondo;
use App\Application\UseCase\Condo\AddUserToCondo\Dto\AddUserToCondoInputDto;
use App\Application\UseCase\Condo\AddUserToCondo\Dto\AddUserToCondoOutputDto;
use App\Application\UseCase\Condo\RemoveUserFromCondo\Dto\RemoveUserFromCondoInputDto;
use App\Application\UseCase\Condo\RemoveUserFromCondo\Dto\RemoveUserFromCondoOutputDto;
use App\Application\UseCase\Condo\RemoveUserFromCondo\RemoveUserFromCondo;
use App\Domain\Model\Condo;
use App\Domain\Repository\CondoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class RemoveUserFromCondoTest extends WebTestCase
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
    private RemoveUserFromCondoInputDto $inputDto;
    private readonly RemoveUserFromCondo $useCase;

    public function setUp(): void
    {
        $this->condoRepo = $this->createMock(CondoRepositoryInterface::class);
        $this->userRepo = $this->createMock(UserRepositoryInterface::class);
        $this->security = $this->createMock(Security::class);
        $this->checker = $this->createMock(AuthorizationCheckerInterface::class);

        $this->inputDto = RemoveUserFromCondoInputDto::create(
            self::DATA_TO_UPDATE['id'],
            self::DATA_TO_UPDATE['userId'],
        );

        $this->useCase = new RemoveUserFromCondo($this->condoRepo, $this->userRepo, $this->security, $this->checker);
    }

    public function testRemoveUserFromCondo(): void
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
                    return $condo->getUsers()->count() === 0;
                })
            );

        $responseDto = $this->useCase->handle($this->inputDto);

        self::assertInstanceOf(RemoveUserFromCondoOutputDto::class, $responseDto);
    }
}