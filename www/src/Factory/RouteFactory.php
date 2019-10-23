<?php

namespace App\Factory;

use DI\Container;
use League\Route\Route;

class RouteFactory 
{
	public static function build(Container $container): Route 
	{
		$route = new Route($container);

		return $route;
	}
}