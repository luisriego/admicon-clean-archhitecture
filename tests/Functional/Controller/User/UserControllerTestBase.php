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
    protected const VALID_JWT = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NzIyNDgxODksImV4cCI6MTY3MzU0NDE4OSwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiYWRtaW5AYXBpLmNvbSIsImlkIjoiYmRhY2ExZDQtYjUyMC00ZGRlLWI3NWEtMDQ1NTlkYzgxNTMwIn0.NbCfY34xBLsZ5VlHPApxvoGY30-WBiucIzcELaZ7vfx8CsDa-huGlftrnloALQ7Nzuikyf4dlWvqtUydaayvkY8NVuIVIOZDIP8IBARPE9ePuXTLReSvFco2NA8zro-fXwWO1nHaMyRIns9qBOroeE3PXsVkbYqLgtowoXPrgifhdhSWID-G57bTyKFS30Md7D6n5RieItIIWpTsvJu_tutlxmFQdecHS4RVNon7p9PlfP-30p-BJyJX4C6_9HIl--u8YMHN0SKN30Evupy881bS4Q90sI9eJwaQxenS_aRs90gNOn_51BOz3lOjJZNoA7TbzxVSeOkPDdplxySHlw';

    protected static ?AbstractBrowser $admin = null;

    public function setUp(): void
    {
        parent::setUp();

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
            'HTTP_ACCEPT' => 'application/json',
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
