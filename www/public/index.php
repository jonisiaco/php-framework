<?php

use DI\ContainerBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use League\Route\Router;
use Zend\Diactoros\Response;

require_once __DIR__. '/../vendor/autoload.php';

$container_builder = new ContainerBuilder();
$container_builder->addDefinitions(__DIR__. '/../config.php');
$container = $container_builder->build();

$request = $container->get('request');

$router = new Router;

$router->map('GET', '/', function (ServerRequestInterface $request) : ResponseInterface {
    $response = new Response;
    $response->getBody()->write('<h1>Good!</h1>');
    return $response;
});

$router->map('GET', '/signin', 'App\Controller\AuthController::index');

$response = $router->dispatch( $request );

$container->get('emitter')->emit($response);

