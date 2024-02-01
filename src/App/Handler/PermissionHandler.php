<?php

declare(strict_types=1);

namespace App\Handler;

use App\Provider\TokenDataProvider;
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
    public function __construct()
    {
    }

    public function __invoke(ServerRequestInterface $serverRequest, RouteParameters $parameters): ResponseInterface
    {
        //TODO what is NP?
        //TODO add constants
        $np = "read";

        //GET requested Token
        $requestToken = $parameters->get("token", Token::MISSING);

        if (Token::MISSING === $requestToken) {
            return new JSONResponse(["message" => "{token} parameter is mandatory"], Response::HTTP_BAD_REQUEST);
        }


            $dataProvider = new TokenDataProvider();

            $tokens = $dataProvider->getTokens();
            $token = null;

            foreach ($tokens as $t) {
                //TODO add strict checks
                if ($t["token"] == $requestToken) {
                    $token = $t;
                }
            }

            foreach ($token["permissions"] as $p) {
                //TODO add strict checks, descriptive properties names
                if ($p == $np) {
                    //TODO initialize $a
                    $a = $a + 1;
                }
            }

            //TODO $a could be undefined here
            if ($a > 0) {

                //TODO add common client response, set proper HTTP status codes, list permissions
                $permission = [];
                return new JSONResponse($permission, Response::HTTP_OK);
            } else {
                //TODO -
                return new JSONResponse([], Response::HTTP_UNAUTHORIZED);
            }

    }
}
