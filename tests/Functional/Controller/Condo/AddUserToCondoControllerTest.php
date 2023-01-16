<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Condo;

use App\Tests\Functional\Controller\ControllerTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddUserToCondoControllerTest extends ControllerTestBase
{
    public function testAddUserToCondo(): void
    {
        // create a Condo
        $condoId = $this->createCondo();
        $userId = $this->createAnotherUser();

        $payload = [
            'id' => $condoId,
            'userId' => $userId
        ];

        // activate a Condo
        self::$admin->request(Request::METHOD_PUT, self::ADD_USER_TO_CONDO_ENDPOINT, [], [], [], \json_encode($payload));
        // checks
        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());

        self::assertEquals(1, $this->count($responseData['users']));
        self::assertEquals($condoId, $responseData['id']);
    }
}