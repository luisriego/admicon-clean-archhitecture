<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use App\Tests\Functional\Controller\ControllerTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateUserControllerTest extends UserControllerTestBase
{
    private const ENDPOINT = '/api/users/create';

    /**
     * @throws \Exception
     */
    public function testCreateUser(): void
    {
        $payload = [
            'name' => 'Peter',
            'email' => 'peter@api.com',
            'password' => 'fake123',
            'age' => 30,
        ];

        self::$admin->request(Request::METHOD_POST, self::ENDPOINT,[], [], [], \json_encode($payload));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertArrayHasKey('userId', $responseData);
        self::assertEquals(36, \strlen($responseData['userId']));
    }
}