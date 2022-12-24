<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;


use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\PasswordHasherInterface;
use App\Domain\ValueObjects\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;
use App\Application\UseCase\User\CreateUser;
use App\Domain\Model\User;

class ControllerTestBase extends WebTestCase
{
    private User $user;
    protected static ?AbstractBrowser $client = null;
    protected static ?AbstractBrowser $authenticatedClient = null;

    public function setUp(): void
    {
        parent::setUp();

        if (null === self::$client) {
            self::$client = static::createClient();
            self::$client->setServerParameter('CONTENT_TYPE', 'application/json');
        }

//        if (null === self::$authenticatedClient) {
//            self::$authenticatedClient = static::createClient();
//
//            $authenticatedClient = User::create(Uuid::random()->value(), 'admin', 'admin@api.com', 'Password1!', 18);
//            $password = static::getContainer()->get(PasswordHasherInterface::class)->hashPasswordForUser($authenticatedClient, 'Password1!');
//            $authenticatedClient->setPassword($password);
//
//            static::getContainer()->get(UserRepositoryInterface::class)->save($authenticatedClient);
//
//            $jwt = static::getContainer()->get(JWTTokenManagerInterface::class)->create($authenticatedClient);
//
//            self::$authenticatedClient->setServerParameters([
//                'CONTENT_TYPE' => 'application/json',
//                'HTTP_Authorization' => \sprintf('Bearer %s', $jwt)
//            ]);
//        }
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