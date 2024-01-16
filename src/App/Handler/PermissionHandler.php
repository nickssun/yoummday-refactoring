<?php

declare(strict_types=1);

namespace App\Handler;

use App\Provider\TokenDataProvider;
use ProgPhil1337\SimpleReactApp\HTTP\Response\JSONResponse;
use ProgPhil1337\SimpleReactApp\HTTP\Response\ResponseInterface;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\Attribute\Route;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\Handler\HandlerInterface;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\HttpMethod;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\RouteParameters;
use Psr\Http\Message\ServerRequestInterface;

#[Route(httpMethod: HttpMethod::GET, uri: '/has_permission/{token}')]
class PermissionHandler implements HandlerInterface
{
    /**
     * Dependency Injection would be available here
     */
    public function __construct()
    {

    }

    public function process(ServerRequestInterface $serverRequest, RouteParameters $parameters): ResponseInterface
    {
        $np = "read";

        $tId = $parameters->get("token", "kein_token");

        if ($tId != "kein_token") {
            $dataProvider = new TokenDataProvider();

            $tokens = $dataProvider->getTokens();
            $token = null;

            foreach ($tokens as $t) {
                if ($t["token"] == $tId) {
                    $token = $t;
                }
            }

            $a = false;
            foreach ($token["permissions"] as $p) {
                if ($p == $np) {
                    $a = true;
                }
            }

            if ($a) {
                return new JSONResponse(array("permission" => true), 400);
            } else {
                return new JSONResponse(array('permission' => false), 400);
            }
        } else {
            return new JSONResponse(array("permission" => false), 400);
        }
    }
}
