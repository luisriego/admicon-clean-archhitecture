<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserControllerTest extends UserControllerTestBase
{
    private const ENDPOINT = '/api/users/%s';

    public function testDeleteUser(): void
    {
        $userId = $this->createUser();

        self::$client->request(Request::METHOD_DELETE, \sprintf(self::ENDPOINT, $userId));

        $response = self::$client->getResponse();

        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNonExistingUser(): void
    {
        self::$client->request(Request::METHOD_DELETE, \sprintf(self::ENDPOINT, self::NON_EXISTING_USER_ID));

        $response = self::$client->getResponse();

        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}