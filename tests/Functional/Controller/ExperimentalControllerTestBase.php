<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Domain\Repository\UserRepositoryInterface;
use Doctrine\DBAL\Connection;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExperimentalControllerTestBase extends WebTestCase
{
    protected const NON_EXISTING_USER_ID = 'e0a1878f-dd52-4eea-959d-96f589a9f234';

    private static ?KernelBrowser $client = null;
    protected static ?KernelBrowser $baseClient = null;
    protected static ?KernelBrowser $authenticatedClient = null;
    protected static ?KernelBrowser $anotherAuthenticatedClient = null;

    public function setUp(): void
    {
        parent::setUp();

        if (null === self::$client) {
            self::$client = static::createClient();
        }

        if (null === self::$baseClient) {
            self::$baseClient = clone self::$client;
            self::$baseClient->setServerParameters([
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]);
        }

        if (null === self::$authenticatedClient) {
            self::$authenticatedClient = clone self::$client;

            self::$client->setServerParameters([
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]);
            $admin = $this->createAdmin();
//            static::getContainer()->get(UserRepositoryInterface::class)->save($admin);
            $user = static::getContainer()->get(UserRepositoryInterface::class)->findOneByIdOrFail($admin);
            $token = static::getContainer()->get(JWTTokenManagerInterface::class)->create($user);

            self::$authenticatedClient->setServerParameters([
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_Authorization' => \sprintf('Bearer %s', $token),
            ]);
        }

//        if (null === self::$anotherAuthenticatedClient) {
//            self::$anotherAuthenticatedClient = clone self::$client;
//
//            $user = static::getContainer()->get(UserRepositoryInterface::class)->findOneByEmailOrFail('another@api.com');
//            $token = static::getContainer()->get(JWTTokenManagerInterface::class)->create($user);
//
//            self::$anotherAuthenticatedClient->setServerParameters([
//                'CONTENT_TYPE' => 'application/json',
//                'HTTP_ACCEPT' => 'application/json',
//                'HTTP_Authorization' => \sprintf('Bearer %s', $token),
//            ]);
//        }
    }

    protected static function initDBConnection(): Connection
    {
        if (null === static::$kernel) {
            static ::bootKernel();
        }

        return static::$kernel->getContainer()->get('doctrine')->getConnection();
    }

    protected function getLuisId()
    {
        return self::initDBConnection()->executeQuery('SELECT id FROM user WHERE email = "luis@api.com"')->fetchOne();
    }

    protected function getAnotherId()
    {
        return self::initDBConnection()->executeQuery('SELECT id FROM user WHERE email = "another@api.com"')->fetchOne();
    }

    protected function createUser(): string
    {
        $payload = [
            'name' => 'Peter',
            'email' => 'peter@api.com',
            'password' => 'Fake123',
            'age' => 30,
        ];

        self::$client->request(Request::METHOD_POST, '/api/users/create', [], [], [], \json_encode($payload));

        $response = self::$client->getResponse();

        if (Response::HTTP_CREATED !== $response->getStatusCode()) {
            throw new \RuntimeException('Error creating user');
        }

        $responseData = $this->getResponseData($response);

        return $responseData['userId'];
    }

    protected function createAdmin(): string
    {
        $payload = [
            'name' => 'Luis',
            'email' => 'luis@api.com',
            'password' => 'Fake123',
            'age' => 30,
        ];

        self::$client->request(Request::METHOD_POST, '/api/users/create', [], [], [], \json_encode($payload));

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
}