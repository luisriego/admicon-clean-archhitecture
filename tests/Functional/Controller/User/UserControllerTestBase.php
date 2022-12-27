<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\PasswordHasherInterface;
use App\Domain\ValueObjects\Uuid;
use App\Tests\Functional\Controller\ControllerTestBase;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTestBase extends WebTestCase
{
    protected const CREATE_USER_ENDPOINT = '/api/users/create';
    protected const NON_EXISTING_USER_ID = 'e0a1878f-dd52-4eea-959d-96f589a9f234';
    protected const VALID_JWT = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NzIwNzEzMjcsImV4cCI6MTY3MzM2NzMyNywicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoic2FudGlAYXBpLmNvbSIsImlkIjoiODc2MWFiZmQtZDU2My00YzU0LWI3YzYtNTg3MjBkMGY1M2QzIn0.TacxrjVHRaBmfpqrmoUYPbjmr3xR0ltV5H23gqQcsbIzP6nmKu0yJ8jwQuRbJbDOpFKzPVqm2qGg-GskKYVLi-M1XkAao9W30fw0PADZVkcMjZv1ooIOXRsuuuHxAeTsiZs40G1GhT_T1eLjJHfc0V1SDp6ymmsBJJQYn-SQturCYxfBRXtZ55qnJrZOzvoy8ZJL8ldnBlJGO378spevf5gxeGGj9cabIyTQmQKZ3s4cX_magIzxVJD06tttxIUxDs5afMGB8wXqJZERG_MCcUdeDfwZM1voFO46bcdD_eBzywRyr8NOgjVA6ulYOz4LXUNSdCSRa1r5kixVFIQJGA';

    protected static ?AbstractBrowser $admin = null;

    public function setUp(): void
    {
        if (null === self::$admin) {
            self::$admin = static::createClient();
        }

        $admin = User::create(Uuid::random()->value(), 'admin', 'admin@api.com', 'Password1!', 18);
        $password = static::getContainer()->get(PasswordHasherInterface::class)->hashPasswordForUser($admin, 'Password1!');
        $admin->setPassword($password);

        static::getContainer()->get(UserRepositoryInterface::class)->save($admin);

        $jwt = static::getContainer()->get(JWTTokenManagerInterface::class)->create($admin);

        self::$admin->setServerParameters([
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

        self::$admin->request(Request::METHOD_POST, '/api/users/create', [], [], [], \json_encode($payload));

        $response = self::$admin->getResponse();

        if (Response::HTTP_CREATED !== $response->getStatusCode()) {
            throw new \RuntimeException('Error creating user');
        }

        $responseData = $this->getResponseData($response);

        return $responseData['userId'];
    }
}
