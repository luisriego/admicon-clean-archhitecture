<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;


use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\PasswordHasherInterface;
use App\Domain\ValueObjects\Uuid;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Application\UseCase\User\CreateUser;
use App\Domain\Model\User;

class ControllerTestBase extends WebTestCase
{
    protected const NON_EXISTING_USER_ID = 'e0a1878f-dd52-4eea-959d-96f589a9f234';
//    private User $user;
    protected static ?AbstractBrowser $client = null;
//    protected static ?AbstractBrowser $authenticatedClient = null;

    public function setUp(): void
    {
        if (null === self::$client) {
            self::$client = static::createClient();
        }

        $client = User::create(Uuid::random()->value(), 'admin', 'admin@api.com', 'Password1!', 18);
        $password = static::getContainer()->get(PasswordHasherInterface::class)->hashPasswordForUser($client, 'Password1!');
        $client->setPassword($password);

        static::getContainer()->get(UserRepositoryInterface::class)->save($client);

        $jwt = static::getContainer()->get(JWTTokenManagerInterface::class)->create($client);

        self::$client->setServerParameters([
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => \sprintf('Bearer %s', $jwt)
        ]);
    }

    protected function getResponseData(Response $response): array
    {
        try {
            return \json_decode($response->getContent(), true);
        } catch (\Exception $e) {
            throw $e;
        }
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
}