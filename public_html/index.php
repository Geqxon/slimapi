<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require Dirname(__DIR__) . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/api/sensoren', function(Request $request, Response $response){

    $dsn = "mysql:host=127.0.0.1;dbname=slim-api;charset=utf8";

    $pdo = new PDO($dsn, 'Lars', 'Welkom01',[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->query('SELECT * from sensor');

    $data = $stmt->fetchALL(PDO::FETCH_ASSOC);

    $body = json_encode($data);
    $response->getBody()->write($body);
    return $response-> withHeader('Content-Type', 'application/json');
});

$app->run();