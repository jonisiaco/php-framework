<?php

namespace App;

use League\Route\RouteCollectionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class Routes 
{
	public static function map(RouteCollectionInterface $router): RouteCollectionInterface
	{
		#IndexController
		$router->map('GET', '/', 		'App\Controller\IndexController::index');
		$router->map('GET', '/about', 	'App\Controller\IndexController::about');
		$router->map('GET', '/default', 'App\Controller\IndexController::default');
		$router->map('GET', '/no-template', function (ServerRequestInterface $request) : ResponseInterface {
		    $response = new Response;
		    $response->getBody()->write('<h1>Good!</h1>');
		    return $response;
		});

		#AuthController
		$router->map('GET', '/signin',		'App\Controller\AuthController::signin');
		$router->map('POST', '/signin',		'App\Controller\AuthController::signin');

		return $router;
	}
	
}