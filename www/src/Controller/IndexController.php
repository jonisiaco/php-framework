<?php declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use DI\ContainerBuilder;
use Zend\Diactoros\Response;
use DI\Container;
use Twig\Environment;

class IndexController
{
    public $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     *
     */
    public function index(ServerRequestInterface $request) : ResponseInterface
    {
        $twig = new Environment( $this->container->get('twig_loader') , [
            'cache' => $this->container->get('twig_cache'),
        ]);
        $body = $twig->render('index.html', ['name' => 'Jon']);

        $response = new Response;

        $response->getBody()->write($body);
        return $response->withStatus(200);
        
    }

    public function about(ServerRequestInterface $request) : ResponseInterface
    {
        $twig = new Environment( $this->container->get('twig_loader') , [
            'cache' => $this->container->get('twig_cache'),
        ]);
        $body = $twig->render('about.html');

        $response = new Response;
        $response->getBody()->write($body);
        return $response->withStatus(200);
        
    }

    /**
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function default(ServerRequestInterface $request) : ResponseInterface
    {
        $response = new Response;
        $response->getBody()->write('<h1>Good Night!</h1>');
        return $response;
    }
}