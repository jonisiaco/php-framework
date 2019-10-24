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

        $container = new Session\Container('session_data');
        if ($container->offsetExists('user')) {
            $queryBuilder = $entity_manager->createQueryBuilder();
            $queryBuilder
                ->select('u')
                ->from('App\Entity\User','u')
                ->where($queryBuilder->expr()->eq('u.id', ':id'))
                ->setParameter('id', $container->user['id']);

            $result = $queryBuilder->getQuery()->getSingleResult(Query::HYDRATE_ARRAY);

            $twig = new Environment( $this->container->get('twig_loader'));
            $body = $twig->render('users.html', ['user' => $result]);
            $response->getBody()->write($body);
            return $response->withStatus(200);

        } else {

            return $response->withStatus(300);
        }
        
    }

}