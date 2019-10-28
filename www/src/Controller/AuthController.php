<?php declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Session;
use DI\Container;
use Twig\Environment;
use PDO;

use App\Bootstrap;

class AuthController
{
    public $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function signin(ServerRequestInterface $request) : ResponseInterface
    {
        $bootstrap = new Bootstrap($this->container);
        $entity_manager = $bootstrap->entityManager();

        $post = $request->getParsedBody();
        if($post){
            $salt = $this->container->get('aes')['token'];
            $raw_query = 'SELECT id, name, email, AES_DECRYPT( password, "'.$salt.'") pass FROM user WHERE email = ?;';
            
            $statement = $entity_manager
                            ->getConnection()
                            ->prepare($raw_query);
            $statement->bindValue("1", $post['email']);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ((string)$result['pass'] === (string)$post['password']) {
                $container = new Session\Container('session_data');
                $container->user = $result;
                return new Response\RedirectResponse('/admin/users');
            }
        }

        $twig = new Environment( $this->container->get('twig_loader'));
        $body = $twig->render('signin.html');

        $response = new Response;

        $response->getBody()->write($body);
        return $response->withStatus(200);
        
    }

}