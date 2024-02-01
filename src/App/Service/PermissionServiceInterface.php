<?php

namespace App\Service;

interface PermissionServiceInterface
{
    public function checkTokenExists(string $token, array $validTokens): void;

    public function extractTokenName(array $tokenData): string;

    public function extractTokenPermissions(array $tokenData): array;

    public function getTokenPermissions(string $token): array;

    public function getTokensData(): array;

    public function getTokenNames(array $tokensData): array;

    public function hasAccess(array $permissions, string $checkType): bool;

    public function hasReadAccess(array $permissions): bool;

}
