<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Condo;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateCondoControllerTest extends CondoControllerTestBase
{
    private const ENDPOINT = '/api/condos/create';

    public function testCreateCondo(): void
    {
        $userId = $this->createUser();

        $payload = [
            'taxpayer' => '02024517000146',
            'fantasyName' => 'Condomínio Matilda',
            'userId' => $userId,
        ];
        
        self::$client->request(Request::METHOD_POST, self::ENDPOINT,[], [], [], \json_encode($payload));

        $response = self::$client->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertArrayHasKey('condoId', $responseData);
        self::assertEquals(36, \strlen($responseData['condoId']));
    }
}