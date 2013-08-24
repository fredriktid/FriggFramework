<?php

namespace Frigg\Core\Loader;

use Frigg\Core\Registry;
use Frigg\Helper\GlobalHelper;
use Frigg\Core\Exception\ErrorException;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\ClassLoader;

class DoctrineLoader extends LoaderBase implements LoaderInterface
{
    public function __construct(Registry $registry)
    {
        parent::__construct($registry);
        $this->instance = $this->loadInstance();
    }

    public function loadInstance()
    {
        $appPath = $this->registry->getSetting('path/app');
        $appName = APP_NAME;

        // development mode
        $devMode = GlobalHelper::isDevMode();

        // entity autoload paths
        $entityPaths = array(sprintf('%s/%s/%s', $appPath, $appName, 'Entity'));

        // autoload entities and repositories
        foreach($entityPaths as $entityPath) {
            if(is_readable($entityPath)) {
                $entityLoader = new ClassLoader(sprintf('%s\Entity', $appName), realpath($appPath), 'loadClass');
                $entityLoader->register();
                if(is_readable(sprintf('%s/Repository', $entityPath))) {
                    $repositoryLoader = new ClassLoader(sprintf('%s\Entity\Repository', $appName), realpath($appPath), 'loadClass');
                    $repositoryLoader->register();
                }
            }
        }

        // return new entity manger instance
        $access = $this->registry->getComponent('frigg/config')->getSection('database/doctrine');
        $config = Setup::createAnnotationMetadataConfiguration($entityPaths, $devMode);
        return EntityManager::create($access, $config);
    }
}
