<?php declare(strict_types=1);

namespace App\Controller;

use Doctrine\ORM\Query;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Session;
use DI\Container;
use Twig\Environment;
use PDO;
use App\Bootstrap;

class UserController
{
    public $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function index(ServerRequestInterface $request) : ResponseInterface
    {
        $bootstrap = new Bootstrap($this->container);
        $entity_manager = $bootstrap->entityManager();
        $response = new Response;

        if ($this->session_exists() === false) {
            $response->getBody()->write('<h1>Denny access</h1>' );
            return $response->withStatus(200);
        }

        $container = new Session\Container('session_data');

        $queryBuilder = $entity_manager->createQueryBuilder();
        $queryBuilder   ->select('u')
                        ->from('App\Entity\User','u')
                        ->where($queryBuilder->expr()->eq('u.id', ':id'))
                        ->setParameter('id', $container->user['id']);

        $result = $queryBuilder->getQuery()->getSingleResult(Query::HYDRATE_ARRAY);

        $twig = new Environment( $this->container->get('twig_loader') );
        $body = $twig->render('users.html', ['user' => $result]);
        $response->getBody()->write($body);
        return $response->withStatus(200);
        
    }

    public function fetch_users(ServerRequestInterface $request) : ResponseInterface
    {
        if ($this->session_exists() === false) {
            return new Response\RedirectResponse('/signin');
        }

        $bootstrap = new Bootstrap($this->container);
        $entity_manager = $bootstrap->entityManager();
        $query = $entity_manager->getRepository('App\Entity\User');
        $users = $query->findAll();

        $twig = new Environment( $this->container->get('twig_loader') );
        $body = $twig->render('users-get.html', ['users' => $users]);

        $response = new Response;
        $response->getBody()->write($body);
        return $response->withStatus(200);

    }

    public function logout() : ResponseInterface
    {
        $container = new Session\Container('session_data');
        if ($container->offsetExists('user')) {
            $container->getManager()->getStorage()->clear('session_data');
        }

        return new Response\RedirectResponse('/signin');
    }

    private function session_exists() : bool
    {
        $container = new Session\Container('session_data');
        if ($container->offsetExists('user') === false) {
            return false;
        }
        return true;
    }

}