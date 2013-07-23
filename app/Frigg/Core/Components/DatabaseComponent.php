<?php

namespace Frigg\Core\Components;

if(!defined('APP_TOKEN'))
{
    die('This file can not be called directly');
}

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\ClassLoader;

class DatabaseComponent extends BaseComponent
{
    private static $em = null;

    // instantiate doctrines entity manager
    private static function init()
    {
        $entityLoader = new ClassLoader('Frigg\Entity', realpath(APP_HOME), 'loadClass');
        $entityLoader->register();

        $repositoryLoader = new ClassLoader('Frigg\Entity\Repository', realpath(APP_HOME), 'loadClass');
        $repositoryLoader->register();

        $paths = array(APP_PATH . '/Entity');
        $devMode = (defined('APP_DEV') && APP_DEV);

        // the connection configuration
        $dbParams = array(
            'driver'   => 'pdo_mysql',
            'user'     => 'root',
            'password' => 'root',
            'dbname'   => 'frigg',
        );

        // connect
        $config = Setup::createAnnotationMetadataConfiguration($paths, $devMode);
        self::$em = EntityManager::create($dbParams, $config);
    }

    // get instance of entity manager
    public static function getEntityManager()
    {
        if(is_null(self::$em))
        {
            self::init();
        }

        return self::$em;
    }

}


