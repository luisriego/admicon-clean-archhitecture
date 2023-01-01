<?php

declare(strict_types=1);

namespace App\Application\UseCase\Condo\CreateCondo;

use App\Application\UseCase\Condo\CreateCondo\Dto\CreateCondoInputDto;
use App\Application\UseCase\Condo\CreateCondo\Dto\CreateCondoOutputDto;
use App\Domain\Exception\Condo\CondoAlreadyExistsException;
use App\Domain\Model\Condo;
use App\Domain\Model\User;
use App\Domain\Repository\CondoRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CreateCondo
{
    public function __construct(
        private readonly CondoRepositoryInterface $repository,
        private readonly AuthorizationCheckerInterface $checker
    ) {
    }

    public function handle(CreateCondoInputDto $inputDto, ?User $authenticatedUser): CreateCondoOutputDto
    {
//        if (!$authenticatedUser) {
//            throw new AuthenticationException("Need a authorized User to create a Condo");
//        }

        if (null !== $this->repository->findOneByTaxpayer($inputDto->taxpayer)) {
            throw CondoAlreadyExistsException::createFromTaxpayer($inputDto->taxpayer);
        }

        $condo = Condo::create(
            $inputDto->taxpayer,
            $inputDto->fantasyName,
        );

        if (!$this->checker->isGranted('ROLE_SYNDIC') && $authenticatedUser) {
            $condo->addUser($authenticatedUser);
        }

        $this->repository->save($condo, true);

        return new CreateCondoOutputDto($condo->getId());
    }
}
