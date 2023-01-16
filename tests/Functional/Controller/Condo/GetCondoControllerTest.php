<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Condo;

use App\Tests\Functional\Controller\ControllerTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetCondoControllerTest extends ControllerTestBase
{
    public function testDeleteCondo(): void
    {
        $CondoId = $this->createCondo();

        self::$admin->request(Request::METHOD_GET, \sprintf(self::ENDPOINT_CONDO, $CondoId));

        $response = self::$admin->getResponse();

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteNonExistingCondo(): void
    {
        self::$admin->request(Request::METHOD_DELETE, \sprintf(self::ENDPOINT_CONDO, self::NON_EXISTING_CONDO_ID));

        $response = self::$admin->getResponse();

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}