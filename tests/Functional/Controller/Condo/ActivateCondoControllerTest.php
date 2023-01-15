<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Condo;

use App\Tests\Functional\Controller\ControllerTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ActivateCondoControllerTest extends ControllerTestBase
{
    public function testUpdateCondo(): void
    {
        // create a Condo
        $condoId = $this->createCondo();

        $payload = [
            'id' => $condoId,
        ];

        // activate a Condo
        self::$admin->request(Request::METHOD_PUT, self::ACTIVATE_CONDO_ENDPOINT, [], [], [], \json_encode($payload));
        // checks
        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());

        self::assertEquals(true, $responseData['isActive']);
        self::assertEquals($condoId, $responseData['id']);
    }

    public function testUpdateCondoWithWrongValue(): void
    {
        $payload = ['fantasyName' => 'A'];

        $userId = $this->createUser();

        self::$admin->request(Request::METHOD_PATCH, \sprintf(self::ENDPOINT_CONDO, $userId), [], [], [], \json_encode($payload));

        $response = self::$admin->getResponse();

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}