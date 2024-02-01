<?php

namespace App\Service;

use App\Provider\TokenDataProvider;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PermissionService
{
    public function __construct(private readonly HttpClientInterface $httpClient, private readonly LoggerInterface $logger)
    {
    }

    /**
     * @param string $token
     * @return array|string[]
     */
    public function getTokenPermissions(string $token): array
    {
        $tokensData = $this->getTokensData();
        $tokensNames = $this->getTokenNames($tokensData);

        $this->checkTokenExists($token, $tokensNames);

        foreach ($tokensData as $validToken) {
            if (isset($validToken['token']) && $validToken['token'] === $token) {
                return $this->extractTokenPermissions($validToken);
            }
        }

        return [];
    }

    /**
     * @return array<array{token: string, permissions: string[]}>
     */
    public function getTokensData(): array
    {
        $dataProvider = new TokenDataProvider();
        return $dataProvider->getTokens();
    }

    /**
     * @param array $tokenData
     * @return string
     */
    public function extractTokenName(array $tokenData): string
    {
        return $tokenData['token'] ?? '';
    }

    /**
     * @param array $tokensData
     * @return array
     */
    public function getTokenNames(array $tokensData): array
    {
        $names = [];
        foreach ($tokensData as $singleTokenData) {
            $names[] = $this->extractTokenName($singleTokenData);
        }

        return $names;
    }

    /**
     * @param array $tokenData
     * @return array
     */
    public function extractTokenPermissions(array $tokenData): array
    {
        return $tokenData['permissions'] ?? [];
    }

    /**
     * @param string $token
     * @param array $validTokens
     * @return void
     */
    public function checkTokenExists(string $token, array $validTokens): void
    {
        if (false === in_array($token, $validTokens, true)) {
            throw new \RuntimeException(sprintf('%s is an unknown token!', $token));
        }
    }

}
