<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use App\Tests\Functional\Controller\ControllerTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HealthCheckControllerTest extends ControllerTestBase
{
    private const ENDPOINT = '/api/users/health-check';

    public function testUserHealthCheck(): void
    {
        self::$client->request(Request::METHOD_GET, self::ENDPOINT);

        $response = self::$client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals('Module User up and running!', $responseData['message']);
    }
}
