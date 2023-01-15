<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Condo;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateCondoControllerTest extends CondoControllerTestBase
{
    public function testUpdateCondo(): void
    {
        $payload = [
            'fantasyName' => 'Condomínio Matilda X',
        ];

        // create a Condo
        $condoId = $this->createCondo();
        // update a Condo
        self::$admin->request(Request::METHOD_PATCH, \sprintf(self::ENDPOINT, $condoId), [], [], [], \json_encode($payload));
        // checks
        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());

        self::assertEquals($payload['fantasyName'], $responseData['fantasyName']);
    }

    public function testUpdateCondoWithWrongValue(): void
    {
        $payload = ['fantasyName' => 'A'];

        $userId = $this->createUser();

        self::$admin->request(Request::METHOD_PATCH, \sprintf(self::ENDPOINT, $userId), [], [], [], \json_encode($payload));

        $response = self::$admin->getResponse();

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}