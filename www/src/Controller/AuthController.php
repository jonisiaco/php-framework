<?php declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use DI\ContainerBuilder;
use Zend\Diactoros\Response;
use DI\Container;
use Twig\Environment;

class AuthController
{
    public $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function signin(ServerRequestInterface $request) : ResponseInterface
    {
        $twig = new Environment( $this->container->get('twig_loader'));
        $body = $twig->render('signin.html');

        $response = new Response;

        $response->getBody()->write($body);
        return $response->withStatus(200);
        
    }

}