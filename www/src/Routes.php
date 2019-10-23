<?php

namespace App;

use League\Route\RouteCollectionInterface;
use Psr\Http\Message\ResponseIterface;
use Psr\Http\Message\ServerRequestInterface;

class Routes 
{
	public static function routes(RouteCollectionInterface $route): RouteCollectionInterface
	{
		$route->get('/', function(ServerRequestInterface $request, ResponseIterface $response){
			return $response->getBody()->write("<h1>Good!</h1>");
		}

		return $route;
	}
	
}