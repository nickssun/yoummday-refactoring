<?php

declare(strict_types=1);

namespace Test\Handler;

use App\Service\PermissionService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class PermissionHandlerTest extends TestCase
{
    private LoggerInterface $mockLogger;
    private MockHttpClient $mockHttpClient;
    private MockResponse $mockResponse;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockLogger = $this->createMock(LoggerInterface::class);
        $this->mockHttpClient = new MockHttpClient();
    }

    /**
     * @return void
     */
    public function testGetTokenPermissions(): void
    {
        $tokenData =
            ['token' => 'token1234', 'permissions' => ['read', 'write']];

        $service = $this->createPermissionService([
            ['token' => 'token1234', 'permissions' => ['read', 'write']]
        ]);

        self::assertEquals($tokenData['permissions'], $service->getTokenPermissions($tokenData['token']));
        self::assertSame(0, $this->mockHttpClient->getRequestsCount());
    }

    public function testExceptionThrownWithUnknownToken(): void
    {
        $service = $this->createPermissionService(
            [
                'token' => 'tokenNotKnown',
                'permissions' => [],
            ]
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('RandomToken is an unknown token!');

        $service->checkTokenExists('RandomToken', []);
    }

    public function tokenDataProvider(): \Generator
    {
        yield 'testToken' => ['token' => 'token1234', 'permissions' => ['read', 'write']];
    }

    private function createPermissionService(array $responseData): PermissionService
    {
        $this->mockResponse = new MockResponse(json_encode($responseData));

        $this->mockHttpClient->setResponseFactory($this->mockResponse);

        return new PermissionService($this->mockHttpClient, $this->mockLogger);
    }
}
