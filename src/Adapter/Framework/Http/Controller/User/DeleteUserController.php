<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Http\Controller\User;

use App\Adapter\Framework\Http\Dto\User\DeleteUserRequestDto;
use App\Application\UseCase\User\DeleteUser\DeleteUser;
use App\Application\UseCase\User\DeleteUser\Dto\DeleteUserInputDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteUserController extends AbstractController
{
    public function __construct(private readonly DeleteUser $useCase)
    {
    }

    #[Route('/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function __invoke(DeleteUserRequestDto $requestDto): Response
    {
        $this->useCase->handle(DeleteUserInputDto::create($requestDto->id));

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
