<?php

require_once __DIR__. '/../vendor/autoload.php';

use DI\ContainerBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Zend\Diactoros\Response;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
//use App\Bootstrap;

$container_builder = new ContainerBuilder();
$container_builder->addDefinitions(__DIR__. '/../config.php');
$container = $container_builder->build();

$request = $container->get('request');

/*
$bootstrap = new Bootstrap($container);
$entity_manager = $bootstrap->entityManager();
$query = $entity_manager->getRepository('App\Entity\User');
$users = $query->findAll();

foreach ($users as $value) {
	var_dump($value->getName());
	echo "<br >";
}
*/


$strategy = (new ApplicationStrategy)->setContainer($container);
$router   = (new Router)->setStrategy($strategy);

$router = App\Routes::map($router);

$response = $router->dispatch( $request );
(new SapiEmitter)->emit($response);

