<?php declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use DI\ContainerBuilder;
use Zend\Diactoros\Response;

class IndexController
{
    /**
     * Controller.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(ServerRequestInterface $request) : ResponseInterface
    {
        $response = new Response;
        $response->getBody()->write('<h1>Good Night!</h1>');
        return $response;

        $view = $this->container->get(Twig::class);
        $template = $view->render('hello');

        $loader = new \Twig\Loader\FilesystemLoader('/path/to/templates');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
        ]);

        echo $twig->render('index.html', ['name' => 'Fabien']);

        $response = new Response\HtmlResponse();
    }
}