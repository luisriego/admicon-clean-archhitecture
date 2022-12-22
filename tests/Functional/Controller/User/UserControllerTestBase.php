<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\ControllerTestBase;

class UserControllerTestBase extends ControllerTestBase
{
    private const CREATE_USER_ENDPOINT = '/api/users/create';
    private const NON_EXISTING_USER_ID = 'e0a1878f-dd52-4eea-959d-96f589a9f234';

    protected function createUser(): string
    {
        $payload = [
            'name' => 'Peter',
            'email' => 'peter@api.com',
            'password' => 'Fake123',
            'age' => 30,
        ];

        self::$client->request(Request::METHOD_POST, self::CREATE_USER_ENDPOINT, [], [], [], \json_encode($payload));

        $response = self::$client->getResponse();

        if (Response::HTTP_CREATED !== $response->getStatusCode()) {
            throw new \RuntimeException('Error creating user');
        }

        $responseData = $this->getResponseData($response);

        return $responseData['userId'];
    }
}
