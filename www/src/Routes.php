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
		$router->map('GET', '/', 'App\Controller\IndexController::index');
		$router->map('GET', '/about', 'App\Controller\IndexController::about');
		$router->map('GET', '/default', 'App\Controller\IndexController::default');
		$router->map('GET', '/no-template', function (ServerRequestInterface $request) : ResponseInterface {
		    $response = new Response;
		    $response->getBody()->write('<h1>Good!</h1>');
		    return $response;
		});

		#AuthController
		$router->map('GET', '/signin', 'App\Controller\AuthController::signin');
		$router->map('POST', '/signin', 'App\Controller\AuthController::signin');

		#UserController
		$router->map('GET', '/admin/users', 'App\Controller\UserController::index');
        $router->map('GET', '/admin/users/get', 'App\Controller\UserController::fetch_users');
        $router->map('GET', '/admin/logout', 'App\Controller\UserController::logout');

        #NewsController
        $router->map('GET', '/admin/news[/page/{page:number}]', 'App\Controller\NewsController::index');
        $router->map('GET', '/admin/news/{id:number}', 'App\Controller\NewsController::details');
        $router->map('GET', '/admin/news/add', 'App\Controller\NewsController::create');
        $router->map('POST', '/admin/news/add', 'App\Controller\NewsController::create');
        $router->map('GET', '/admin/news/edit/{id:number}', 'App\Controller\NewsController::update');
        $router->map('POST', '/admin/news/edit/{id:number}', 'App\Controller\NewsController::update');
        $router->map('GET', '/admin/news/delete/{id:number}', 'App\Controller\NewsController::delete');
        $router->map('POST', '/admin/news/delete', 'App\Controller\NewsController::delete');

		return $router;
	}
	
}