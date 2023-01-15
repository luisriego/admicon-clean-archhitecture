<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Condo;

use App\Tests\Functional\Controller\ControllerTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CondoControllerTestBase extends ControllerTestBase
{
    protected const ENDPOINT = '/api/condos/%s';
    protected const CREATE_CONDO_ENDPOINT = '/api/condos/create';

    protected function createCondo(): string
    {
        $userId = $this->createUser();

        $payload = [
            'taxpayer' => '02024517000146',
            'fantasyName' => 'Condomínio Matilda',
            'userId' => $userId,
        ];

        self::$admin->request(Request::METHOD_POST, self::CREATE_CONDO_ENDPOINT, [], [], [], \json_encode($payload));

        $response = self::$admin->getResponse();

        if (Response::HTTP_CREATED !== $response->getStatusCode()) {
            throw new \RuntimeException('Error creating user');
        }

        $responseData = $this->getResponseData($response);

        return $responseData['condoId'];
    }
}