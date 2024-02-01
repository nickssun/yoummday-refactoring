<?php

declare(strict_types=1);

namespace App\Provider;

class TokenDataProvider
{
    private const TOKENS = [
        ['token' => 'token1234', 'permissions' => ['read', 'write']]
    ];

    /**
     * @return array<array{token: string, permissions: string[]}>
     */
    public function getTokens(): array
    {
        return self::TOKENS;
    }
}
