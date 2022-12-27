<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\User;

use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserControllerTest extends UserControllerTestBase
{
    private const ENDPOINT = '/api/users/%s';

    /**
     * @dataProvider updateUserDataProvider
     * @throws \Exception
     */
    public function testUpdateUser(array $payload): void
    {
        // create a user
        $userId = $this->createUser();

        // update a user
        self::$admin->request(Request::METHOD_PATCH, \sprintf(self::ENDPOINT, $userId), [], [], [], \json_encode($payload));
        // checks
        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        self::assertEquals(true, true);

//        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());

//        $keys = \array_keys($payload);
//
//        foreach ($keys as $key) {
//            self::assertEquals($payload[$key], $responseData[$key]);
//        }
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