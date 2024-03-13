<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use DI\Container;

require dirname(__DIR__) . '/vendor/autoload.php';

$container = new Container;

AppFactory:: setContainer($container);

$app = AppFactory::create();

$app->get('/api/sensoren', function(Request $request, Response $response) {

    $repository = $this->get (App\Repositories\SensorRepository::class);

    $data = $repository->getAllSensoren();

    $body = json_encode($data);

    $response->getBody()->write($body);

    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();