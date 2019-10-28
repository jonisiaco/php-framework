<?php

require_once __DIR__. '/../vendor/autoload.php';

use DI\ContainerBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Zend\Diactoros\Response;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

$container_builder = new ContainerBuilder();
$container_builder->addDefinitions(__DIR__. '/../config.php');
$container = $container_builder->build();

$request = $container->get('request');

$strategy = (new ApplicationStrategy)->setContainer($container);
$router   = (new Router)->setStrategy($strategy);

$router = App\Routes::map($router);

$response = $router->dispatch( $request );
(new SapiEmitter)->emit($response);

