<?php

declare(strict_types=1);

namespace Tests\Functional\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Functional\Controller\ControllerTestBase;

class UserControllerTestBase extends ControllerTestBase
{
    protected const CREATE_USER_ENDPOINT = '/api/users';

    protected function createReservation(): string
    {
        $payload = [];

        self::$client->request(Request::METHOD_POST, self::CREATE_USER_ENDPOINT, [], [], [], \json_encode($payload));

        $response = self::$client->getResponse();

        if (Response::HTTP_CREATED !== $response->getStatusCode()) {
            throw new \RuntimeException('Error creating user');
        }

        $responseData = $this->getResponseData($response);

        return $responseData['userId'];
    }
}
