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
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Zend\Paginator\Adapter\Iterator as ZendPaginatorAdapterIterator;
use Zend\Paginator\Paginator as ZendPaginator;
use Zend\Paginator\ScrollingStyle\Sliding;
use App\Entity\News;
use App\Entity\User;

class NewsController
{
    public $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function index(ServerRequestInterface $request, array $args) : ResponseInterface
    {
        if ($this->session_exists() === false) {
            return new RedirectResponse('/signin');
        }

        $bootstrap = new Bootstrap($this->container);
        $entity_manager = $bootstrap->entityManager();
        $queryBuilder = $entity_manager->createQueryBuilder();
        $queryBuilder->select('n')
                    ->from('App\Entity\News','n');

        $paginator = new DoctrinePaginator($queryBuilder, $fetchJoinCollection = true);
        $adapter =  new ZendPaginatorAdapterIterator($paginator->getIterator());
        $zend_paginator = new ZendPaginator($adapter);

        $page = $args['page'] ?? 1;
        $news = $zend_paginator->setItemCountPerPage(2)
                                ->setCurrentPageNumber($page);

        ZendPaginator::setDefaultScrollingStyle(new Sliding());

        $twig = new Environment( $this->container->get('twig_loader') );
        $body = $twig->render('news.html', [
                                'news'  => $news,
                                'pages' => $zend_paginator->getPages()
                            ]);

        $response = new Response;
        $response->getBody()->write($body);
        return $response->withStatus(200);
        
    }

    public function details(ServerRequestInterface $request,  array $args) : ResponseInterface
    {
        if ($this->session_exists() === false) {
            return new RedirectResponse('/signin');
        }

        $bootstrap = new Bootstrap($this->container);
        $entity_manager = $bootstrap->entityManager();

        $page = $args['id'] ?? 0;
        $news = $entity_manager->getRepository('App\Entity\News')
                            ->findOneBy(['id' => (int)$page]);

        $twig = new Environment( $this->container->get('twig_loader') );
        $body = $twig->render('news-details.html', [
                                'news' => $news,
        ]);

        $response = new Response;
        $response->getBody()->write($body);
        return $response->withStatus(200);

    }

    public function create(ServerRequestInterface $request,  array $args) : ResponseInterface
    {
        if ($this->session_exists() === false) {
            return new RedirectResponse('/signin');
        }

        $bootstrap = new Bootstrap($this->container);
        $entity_manager = $bootstrap->entityManager();

        $error = null;
        $post = $request->getParsedBody();
        if($post){
            if (!empty($post['title'])
                && !empty($post['author'])
                && !empty($post['content'])
            ){
                $user = $entity_manager->getRepository('App\Entity\User')
                                        ->findOneBy(['id' => $post['author']]);
                $new = new News();
                $new->setTitle($post['title']);
                $new->setAuthor( $user );
                $new->setContent($post['content']);
                $new->setUpdatedAt(new \DateTime());
                $new->setCreatedAt(new \DateTime());
                $entity_manager->persist($new);
                $entity_manager->flush();

                return new RedirectResponse('/admin/news');
            } else {
                $error = 'Invalid data';
            }

        }

        $queryBuilder = $entity_manager->createQueryBuilder();
        $queryBuilder->select('u.id','u.name')
                    ->from('App\Entity\User','u');
        $authors = $queryBuilder->getQuery()->getArrayResult();

        $twig = new Environment( $this->container->get('twig_loader') );
        $body = $twig->render('news-add.html', [
                        'authors' => $authors,
                        'error' => $error
        ]);

        $response = new Response;
        $response->getBody()->write($body);
        return $response->withStatus(200);

    }

    public function update(ServerRequestInterface $request,  array $args) : ResponseInterface
    {
        if ($this->session_exists() === false) {
            return new RedirectResponse('/signin');
        }

        $bootstrap = new Bootstrap($this->container);
        $entity_manager = $bootstrap->entityManager();

        $page = $args['id'] ?? 0;
        $news = $entity_manager->getRepository('App\Entity\News')
                                ->findOneBy(['id' => $page]);

        $response = new Response;
        if (!$news){
            return $response->withStatus(404);
        }

        $error = null;

        $post = $request->getParsedBody();
        if($post) {
            if (!empty($post['title'])
                && !empty($post['author'])
                && !empty($post['content'])
            ) {
                $user_row = $entity_manager
                                ->getRepository('App\Entity\User')
                                ->findOneBy(['id' => $post['author']]);

                $news->setId($post['id']);
                $news->setTitle($post['title']);
                $news->setAuthor($user_row);
                $news->setContent($post['content']);
                $news->setUpdatedAt(new \DateTime());
                $entity_manager->merge($news);
                $entity_manager->flush();

                return new RedirectResponse('/admin/news');
            } else {
                $error = 'Invalid data';
            }
        }

        $queryBuilder = $entity_manager->createQueryBuilder();
        $queryBuilder->select('u.id','u.name')
                        ->from('App\Entity\User','u');
        $users = $queryBuilder->getQuery()->getArrayResult();

        $twig = new Environment( $this->container->get('twig_loader') );
        $body = $twig->render('news-edit.html', [
                                    'news' => $news,
                                    'error' => $error,
                                    'authors' => $users,
        ]);

        $response->getBody()->write($body);
        return $response->withStatus(200);

    }

    public function delete(ServerRequestInterface $request,  array $args) : ResponseInterface
    {
        if ($this->session_exists() === false) {
            return new RedirectResponse('/signin');
        }

        $twig = new Environment( $this->container->get('twig_loader') );
        $body = $twig->render('news-delete.html');

        $response = new Response;
        $response->getBody()->write($body);
        return $response->withStatus(200);

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