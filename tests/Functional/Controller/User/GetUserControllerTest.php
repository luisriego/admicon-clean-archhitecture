<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use App\Tests\Functional\Controller\ControllerTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetUserControllerTest extends ControllerTestBase
{
    public function testGetUser(): void
    {
        $CondoId = $this->createCondo();

        self::$admin->request(Request::METHOD_GET, \sprintf(self::ENDPOINT_CONDO, $CondoId));

        $response = self::$admin->getResponse();
        
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteNonExistingUser(): void
    {
        self::$admin->request(Request::METHOD_DELETE, \sprintf(self::ENDPOINT_USER, self::NON_EXISTING_USER_ID));

        $response = self::$admin->getResponse();

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}