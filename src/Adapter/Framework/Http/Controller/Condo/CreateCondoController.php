<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Http\Controller\Condo;

use App\Adapter\Framework\Http\Dto\Condo\CreateCondoRequestDto;
use App\Application\UseCase\Condo\CreateCondo\CreateCondo;
use App\Application\UseCase\Condo\CreateCondo\Dto\CreateCondoInputDto;
use App\Domain\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateCondoController extends AbstractController
{
    public function __construct(
        private readonly CreateCondo $createCondo)
    {
    }

    #[Route('/create', name: 'condo_create', methods: ['POST'])]
    public function __invoke(CreateCondoRequestDto $request): Response
    {
        // returns your User object, or null if the user is not authenticated
        /** @var User $authenticatedUser */
        $authenticatedUser = $this->getUser();

        $responseDto = $this->createCondo->handle(
            CreateCondoInputDto::create(
                $request->taxpayer,
                $request->fantasyName),
            $authenticatedUser
        );

        return $this->json(['condoId' => $responseDto->id], Response::HTTP_CREATED);
    }
}
