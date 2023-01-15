<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Condo;

use App\Tests\Functional\Controller\ControllerTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveUserFromCondoControllerTest extends ControllerTestBase
{
    public function testDeleteCondo(): void
    {
        // create a Condo
        $condoId = $this->createCondo();
        $userId = $this->createAnotherUser();

        $payload = [
            'id' => $condoId,
            'userId' => $userId
        ];

        // Add user to Condo
        self::$admin->request(Request::METHOD_PUT, self::ADD_USER_TO_CONDO_ENDPOINT, [], [], [], \json_encode($payload));

        // then, remove
        self::$admin->request(Request::METHOD_PUT, self::REMOVE_USER_FROM_CONDO_ENDPOINT, [], [], [], \json_encode($payload));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(true, empty($responseData['users']));
    }

    public function testDeleteNonExistingCondo(): void
    {
        self::$admin->request(Request::METHOD_DELETE, \sprintf(self::ENDPOINT_CONDO, self::NON_EXISTING_CONDO_ID));

        $response = self::$admin->getResponse();

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}