<?php
declare(strict_types=1);

use Zend\Diactoros\Response;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;
use Twig\Loader\FilesystemLoader;

return [

	'request' => function () {
		return ServerRequestFactory::fromGlobals(
			$_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
		);
	},
	'response' => new Response(),
	'emitter' => new SapiEmitter(),

	'twig_loader' => new FilesystemLoader( __DIR__. '/html' ),
	'twig_cache' =>  __DIR__. '/cache',

	'doctrine' => [
		'connection' => [
			'params' => [
					'dbname'	=> 'phpframework_news',
				    'user'		=> 'root',
				    'password'  => 'root',
				    'host' 		=> '172.16.238.1',
				    'driver'	=> 'pdo_mysql',
			]
		],
		'entity_path' => __DIR__."/src/Entity",
	],
	'aes' => [
		'token' => 'EDB45DABEC848155678E52BB817FD224',
	],

];