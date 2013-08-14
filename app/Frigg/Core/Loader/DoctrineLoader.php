<?php

namespace Frigg\Core\Loader;

use Frigg\Core\Exception\LoaderException;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\ClassLoader;

defined('APP_TOKEN') or die('This file can not be called directly');

class DoctrineLoader extends BaseLoader
{
    protected $settings;

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->settings = $this->registry->getComponent('frigg/config')->getConfig('database/doctrine');

    }

    // get current app name
    public function getAppName()
    {
        return $this->registry->getSetting('frigg/app');
    }

    // override default app name
    public function setAppName($appName)
    {
        $this->registry->setSetting('frigg/app', $appName);
        return $this;
    }

    // get name of current skin
    public function getSettings()
    {
        return $this->settings;
    }

    // set all settings
    public function setSettings($settings)
    {
        $this->settings = $settings;
        return $this;
    }

    // set value of a setting key
    public function setSettingKey($key, $value)
    {
        $this->settings[$key] = $value;
        return $this;
    }

    // init doctrine entity manager
    protected function getEnvironment()
    {
        $appPath = $this->registry->getSetting('frigg/path/app');
        $appName = $this->registry->getSetting('frigg/app');
        $devMode = $this->registry->getHelper('frigg/global')->isDevMode();

        // autoload paths
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

        // create and return instance
        $config = Setup::createAnnotationMetadataConfiguration($entityPaths, $devMode);
        return EntityManager::create($this->settings, $config);
    }
}
