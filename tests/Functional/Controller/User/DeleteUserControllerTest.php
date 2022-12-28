<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Tests\Functional\Controller\ControllerTestBase;
use App\Tests\Functional\Controller\ExperimentalControllerTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class DeleteUserControllerTest extends ExperimentalControllerTestBase
{
    private const ENDPOINT = '/api/users/%s';

    public function testDeleteUser(): void
    {
        $userId = $this->createUser();

//        self::$authenticatedClient->setServerParameters([
//            'CONTENT_TYPE' => 'application/json',
//            'HTTP_Authorization' => \sprintf('Bearer %s', self::VALID_JWT)
//        ]);

        self::$authenticatedClient->request(Request::METHOD_DELETE, \sprintf(self::ENDPOINT, $userId));

        $response = self::$authenticatedClient->getResponse();

//        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

//    public function testDeleteNonExistingUser(): void
//    {
//        self::$authenticatedClient->request(Request::METHOD_DELETE, \sprintf(self::ENDPOINT, self::NON_EXISTING_USER_ID));
//
//        $response = self::$authenticatedClient->getResponse();
//
////        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
//        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
//    }
}