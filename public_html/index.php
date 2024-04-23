<?php

declare(strict_types=1);

use App\Middleware\ApikeyMiddleware;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
// use DI\Container;
use DI\ContainerBuilder;
//use Slim\Handelers\Strategies\RequestResponseArgs;
use Slim\Handlers\Strategies\RequestResponseArgs as StrategiesRequestResponseArgs;

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$builder = new ContainerBuilder();

$container = $builder->addDefinitions(APP_ROOT.'/config/definitions.php')
                     ->build();

AppFactory:: setContainer($container);

$app = AppFactory::create();

$collector = $app->getRouteCollector();
$collector->setDefaultInvocationStrategy(new StrategiesRequestResponseArgs);

$app->addBodyParsingMiddleware();

$error_middleware = $app->addErrorMiddleware(true, true, true);

$error_handler = $error_middleware->getDefaultErrorhandler();

$error_handler->forceContentType('application/json');

$app->add(new ApikeyMiddleware);

$app->get('/api/sensoren', App\Controlers\sensoren::class . ':showALLSensoren');

$app->post('/api/sensoren', App\Controlers\sensoren::class . ':addSensor');

$app->get('/api/sensoren/{id:[0-9]+}', function(Request $request, Response $response, string $id){
    
    $repository = $this->get (App\Repositories\SensorRepository::class);

    $data = $repository->getSensorById((int) $id);

    if($data === false) {
        throw new \Slim\Exception\HttpNotFoundException($request,
                                                        message:"Sensor bestaat niet");
    }

    $body = json_encode($data);

    $response->getBody()->write($body);

    return $response->withHeader('Content-Type', 'application/json');

});


//metingen
$app->get('/api/metingen', App\Controlers\Metingen::class . ':showAllMetingen');

$app->get('/api/metingen/{id:[0-9]+}', App\Controlers\Metingen::class . ':showSingleMeting');

$app->post('/api/metingen' , App\Controlers\Metingen::class . ':addMeting');

$app->get('/api/metingen/filters', App\Controlers\Metingen::class . ':getFilterdMetingen');

$app->run();