<?php

declare(strict_types=1);

namespace App\Handler;

use App\Provider\TokenDataProvider;
use App\Service\PermissionService;
use App\Utils\Token;
use ProgPhil1337\SimpleReactApp\HTTP\Response\JSONResponse;
use ProgPhil1337\SimpleReactApp\HTTP\Response\ResponseInterface;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\Attribute\Route;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\Handler\HandlerInterface;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\HttpMethod;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\RouteParameters;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Response;

#[Route(httpMethod: HttpMethod::GET, uri: '/has_permission/{token}')]
class PermissionHandler implements HandlerInterface
{
    /**
     * Dependency Injection would be available here
     */
    public function __construct(
        private readonly PermissionService $permissionService
    ) {
    }

    public function __invoke(ServerRequestInterface $serverRequest, RouteParameters $parameters): ResponseInterface
    {
        $requestToken = $parameters->get("token", Token::MISSING);

        if (Token::MISSING === $requestToken) {
            return new JSONResponse(["message" => "{token} parameter is mandatory"], Response::HTTP_BAD_REQUEST);
        }

        $permissions = $this->permissionService->getTokenPermissions($requestToken);

        if ($this->permissionService->hasReadAccess($permissions)) {
            return new JSONResponse($permissions, Response::HTTP_OK);
        }

        return new JSONResponse([], Response::HTTP_UNAUTHORIZED);
    }
}
