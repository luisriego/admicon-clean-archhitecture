<?php

declare(strict_types=1);

namespace Tests\Functional\Controller\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserControllerTest extends WebTestCase
{
    private const CREATE_USER_ENDPOINT = '/api/users/create';
    private const NON_EXISTING_USER_ID = 'e0a1878f-dd52-4eea-959d-96f589a9f234';

    private static ?AbstractBrowser $client = null;

    public function setUp(): void
    {
        if (null === self::$client) {
            self::$client = static::createClient();
            self::$client->setServerParameter('CONTENT_TYPE', 'application/json');
        }
    }

    private const ENDPOINT = '/api/users/%s';

    /**
     * @dataProvider updateUserDataProvider
     */
    public function testUpdateUser(array $payload): void
    {
        // create a user
        $userId = $this->createUser();
        // update a user
        self::$client->request(Request::METHOD_PATCH, \sprintf(self::ENDPOINT, $userId), [], [], [], \json_encode($payload));
        // checks
        $response = self::$client->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $keys = \array_keys($payload);

        foreach ($keys as $key) {
            self::assertEquals($payload[$key], $responseData[$key]);
        }
    }

    private function createUser(): string
    {
        $payload = [
            'name' => 'Peter',
            'email' => 'peter@api.com',
            'password' => 'Fake123',
            'age' => 30,
        ];

        self::$client->request(Request::METHOD_POST, self::CREATE_USER_ENDPOINT, [], [], [], \json_encode($payload));

        $response = self::$client->getResponse();

        if (Response::HTTP_CREATED !== $response->getStatusCode()) {
            throw new \RuntimeException('Error creating user');
        }

        $responseData = $this->getResponseData($response);

        return $responseData['userId'];
    }

    protected function getResponseData(Response $response): array
    {
        try {
            return \json_decode($response->getContent(), true);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateUserDataProvider(): iterable
    {
        yield 'Update name payload' => [
            [
                'name' => 'Brian',
            ],
        ];

        yield 'Update password payload' => [
            [
                'password' => 'Fake111',
            ],
        ];

        yield 'Update name and password payload' => [
            [
                'name' => 'Peter',
                'password' => 'Fake222',
            ],
        ];

        yield 'Update age payload' => [
            [
                'age' => 33,
            ],
        ];
    }
}