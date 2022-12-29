<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ActivateUserControllerTest extends UserControllerTestBase
{
    private const ENDPOINT = '/api/users/activate';

    public function testActivateUser(): void
    {
        // create a user
        $userId = $this->createUser();

        $userRepository = static::getContainer()->get(UserRepositoryInterface::class);
        $user = $userRepository->findOneByIdOrFail($userId);

        $payload = [
            'id' => $userId,
            'token' => $user->getToken(),
        ];

        // activate a user
        self::$admin->request(Request::METHOD_PUT, \sprintf(self::ENDPOINT, $userId), [], [], [], \json_encode($payload));

        // checks
        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals($userId, $responseData['id']);
        self::assertEquals('', $responseData['token']);
    }

}