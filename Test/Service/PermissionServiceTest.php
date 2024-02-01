<?php

declare(strict_types=1);

namespace Test\Service;

use App\Service\PermissionService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class PermissionServiceTest extends TestCase
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

    public function testExtractTokenName()
    {
        $tokenData = [
            'token' => 'TestToken',
            'permissions' => ['read', 'write', 'execute']
        ];
        $service = $this->createPermissionService([]);

        $this->assertSame('TestToken', $service->extractTokenName($tokenData));
    }

    public function testExtractTokenPermissions()
    {
        $tokenData = [
            'token' => 'TestToken',
            'permissions' => ['read', 'write', 'execute']
        ];
        $service = $this->createPermissionService([]);

        $this->assertEquals(['read', 'write', 'execute'], $service->extractTokenPermissions($tokenData));
    }

    public function testGetTokenPermissions()
    {
        $service = $this->createPermissionService([]);

        $this->assertEquals(['read', 'write'], $service->getTokenPermissions('token1234'));
    }

    public function testGetTokenNames()
    {
        $tokenData = [
            [
                'token' => 'TestToken1',
                'permissions' => ['read', 'write', 'execute']
            ],
            [
                'token' => 'TestToken2',
                'permissions' => ['read', 'write', 'execute']
            ]
        ];
        $service = $this->createPermissionService([]);

        $this->assertEquals(['TestToken1', 'TestToken2'], $service->getTokenNames($tokenData));
    }


    private function createPermissionService(array $responseData): PermissionService
    {
        $this->mockResponse = new MockResponse(json_encode($responseData));

        $this->mockHttpClient->setResponseFactory($this->mockResponse);

        return new PermissionService($this->mockHttpClient, $this->mockLogger);
    }
}
