<?php

namespace Frigg\Core\Components;

defined('APP_TOKEN') or die('This file can not be called directly');

use Frigg\Core as App;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\ClassLoader;

class DatabaseComponent extends BaseComponent
{
    private static $em = null;

    // create doctrine entity manager
    private static function createEntityManager()
    {
        $registry = App\Registry::singleton();
        $settings = $registry->getComponent('config')->load('site');

        $entityLoader = new ClassLoader('Frigg\Entity', realpath(APP_HOME), 'loadClass');
        $entityLoader->register();

        $repositoryLoader = new ClassLoader('Frigg\Entity\Repository', realpath(APP_HOME), 'loadClass');
        $repositoryLoader->register();

        $paths = array(APP_PATH . '/Entity');
        $devMode = (defined('APP_DEV') && APP_DEV);

        // connect
        $config = Setup::createAnnotationMetadataConfiguration($paths, $devMode);
        self::$em = EntityManager::create($settings['database'], $config);
    }

    // get instance of entity manager
    public static function getEntityManager()
    {
        if(is_null(self::$em)) {
            self::createEntityManager();
        }

        return self::$em;
    }

}
