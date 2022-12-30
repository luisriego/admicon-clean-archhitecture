<?php

declare(strict_types=1);

namespace App\Application\UseCase\Condo\CreateCondo;

use App\Application\UseCase\Condo\CreateCondo\Dto\CreateCondoInputDto;
use App\Application\UseCase\Condo\CreateCondo\Dto\CreateCondoOutputDto;
use App\Domain\Exception\Condo\CondoAlreadyExistsException;
use App\Domain\Model\Condo;
use App\Domain\Repository\CondoRepositoryInterface;

class CreateCondo
{
    public function __construct(
        private readonly CondoRepositoryInterface $repository
    ) {
    }

    public function handle(CreateCondoInputDto $inputDto): CreateCondoOutputDto
    {
        if (null !== $this->repository->findOneByTaxpayer($inputDto->taxpayer)) {
            throw CondoAlreadyExistsException::createFromTaxpayer($inputDto->taxpayer);
        }

        $condo = Condo::create(
            $inputDto->taxpayer,
            $inputDto->fantasyName,
        );

        $this->repository->save($condo, true);

        return new CreateCondoOutputDto($condo->getId());
    }
}
