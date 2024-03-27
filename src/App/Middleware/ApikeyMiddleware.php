<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as Requisthandler;
use Slim\Psr7\Response as Psr7Response;

Class ApikeyMiddleware{

private $apikey = "jaaaalaaaa";

public function __invoke(Request $request, Requisthandler $handler): Response
{
    $apiHeader = $request ->getHeaderLine('Authorization');

    if ($apiHeader !== $this->apikey)
    {
        $response = new Psr7Response();
        $error = ['message'=> '401 Unauthorized',
                  'exception' => (['type' => get_class($this),
                                   'code' => 401,
                                   'message' => 'geen toegang',
                                   'file' => __FILE__,
                                   'line' => __LINE__,])];

        $response->getBody()->write(json_encode($error));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }

    return $handler->handle($request);
}
}