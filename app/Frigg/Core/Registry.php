<?php

namespace Frigg\Core;

use Frigg\Core\RegistryPattern;
use Frigg\Core\Component;
use Frigg\Core\Exception\CoreException;

// core registry
// inherits singleton design pattern
class Registry extends RegistrySingleton implements RegistryInterface
{
    private static $components = array();
    private static $settings = array();

    // get component from registry
    public function getComponent($identifier)
    {
        if(!array_key_exists($identifier, static::$components)) {
            static::$components[$identifier] = static::setComponent($identifier);
        }

        return static::$components[$identifier];
    }

    // set a component in registry
    public function setComponent($identifier)
    {
        list($appName, $className) = RegistryPattern::identifierToClass($identifier);
        $classPattern = sprintf('\%s\Core\Component\%sComponent', $appName, $className);

        try {
            static::$components[$identifier] = new $classPattern(static::$instance);
        } catch(\Exception $e) {
            throw $e;
        }

        return $this;
    }

    // instantiate a helper class
    // returns a new object each time
    public function getHelper($identifier)
    {
        list($appName, $className) = RegistryPattern::identifierToClass($identifier);
        $helperPattern = sprintf('\%s\Helper\%sHelper', $appName, $className);
        return new $helperPattern(static::$instance);
    }

    // stores setting in registry
    public function setSetting($key, $value)
    {
        static::$settings[$key] = $value;
        return $this;
    }

    // get setting from registry
    public function getSetting($key)
    {
        return static::$settings[$key];
    }

    // default settings, mostly paths
    public function loadSettings()
    {
        static::$instance
            ->setSetting('frigg/app', APP_NAME)
            ->setSetting('frigg/dev', APP_DEV)
            ->setSetting('frigg/skin', APP_SKIN)
            ->setSetting('frigg/path', APP_PATH)
            ->setSetting('frigg/path/app', APP_PATH . '/app')
            ->setSetting('frigg/path/design', APP_PATH . '/design')
            ->setSetting('frigg/path/cache', APP_PATH . '/cache')
            ->setSetting('frigg/path/config', APP_PATH . '/config')
            ->setSetting('frigg/path/log', APP_PATH . '/log');

        return $this;
    }

    public function loadComponents()
    {
        static::$instance
            ->setComponent('frigg/config')
            ->setComponent('frigg/http')
            ->setComponent('frigg/logger')
            ->setComponent('frigg/request')
            ->setComponent('frigg/response')
            ->setComponent('frigg/loader')
            ->setComponent('frigg/form');

        return $this;
    }
}
