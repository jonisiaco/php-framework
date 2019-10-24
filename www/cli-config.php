<?php

require_once __DIR__. '/vendor/autoload.php';

use DI\ContainerBuilder;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use App\Bootstrap;

$container_builder = new ContainerBuilder();
$container_builder->addDefinitions(__DIR__. '/config.php');
$container = $container_builder->build();

$bootstrap = new Bootstrap($container);
$entity_manager = $bootstrap->entityManager();

return ConsoleRunner::createHelperSet($entity_manager);
