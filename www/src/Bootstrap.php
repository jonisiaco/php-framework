<?php

namespace App;

use DI\Container;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

/**
 * 
 */
class Bootstrap
{
	#Doctrine Metadata Configuration
	private $dev_mode = true;
	private $proxy = null;
	private $cache = null;
	private $simple_mode = false;
	
	function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
 	* Create a Doctrine ORM configuration for Annotation Mapping
 	*/
	public function doctrineConfig()
	{
		return Setup::createAnnotationMetadataConfiguration( 
				[ $this->container->get('doctrine')['entity_path'] ],
				$this->dev_mode,
				$this->proxy,
				$this->cache,
				$this->simple_mode
		);
	}

	/**
 	* Get EntityManager;
 	* Return EntityManager object
 	*/
	public function entityManager() : EntityManager
	{
		return EntityManager::create(
			$this->container->get('doctrine')['connection']['params'], 
			$this->doctrineConfig()
		);
	}
}
