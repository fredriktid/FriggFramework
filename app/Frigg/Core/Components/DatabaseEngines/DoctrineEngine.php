<?php

namespace Frigg\Core\Components\DatabaseEngines;

defined('APP_TOKEN') or die('This file can not be called directly');

use Frigg\Core as App;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\ClassLoader;

class DoctrineEngine
{
    private $registry;
    public $instance;

    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->instance = $this->getEntityManager();
    }

    // create doctrine entity manager
    private function getEntityManager()
    {
        $settings = $this->registry->getComponent('config')->getConfig('database', 'doctrine');

        $entityLoader = new ClassLoader(sprintf('%s\Entity', APP_NAME), realpath(APP_HOME), 'loadClass');
        $entityLoader->register();

        $repositoryLoader = new ClassLoader(sprintf('%s\Entity\Repository', APP_NAME), realpath(APP_HOME), 'loadClass');
        $repositoryLoader->register();

        $paths = array(APP_PATH . '/Entity');
        $devMode = (defined('APP_DEV') && APP_DEV);

        // connect
        $config = Setup::createAnnotationMetadataConfiguration($paths, $devMode);
        return EntityManager::create($settings, $config);
    }
}