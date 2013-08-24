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
    public static function getComponent($identifier)
    {
        // try to load if it doest exist in registry
        if(!array_key_exists($identifier, static::$components)) {
            static::setComponent($identifier);
        }

        return static::$components[$identifier];
    }

    // set a component in registry
    public static function setComponent($identifier)
    {
        list($appName, $className) = RegistryPattern::identifierToClass($identifier);
        $classPattern = sprintf('\%s\Core\Component\%sComponent', $appName, $className);

        try {
            static::$components[$identifier] = new $classPattern(static::$instance);
        } catch(\Exception $e) {
            throw $e;
        }

        return static::$instance;
    }

    // instantiate a helper class
    // returns a new object each time
    public static function getHelper($identifier)
    {
        list($appName, $className) = RegistryPattern::identifierToClass($identifier);
        $helperPattern = sprintf('\%s\Helper\%sHelper', $appName, $className);
        return new $helperPattern(static::$instance);
    }

    // set a setting key
    public static function setSetting($key, $value)
    {
        static::$settings[$key] = $value;
        return static::$instance;
    }

    // get a setting value
    public static function getSetting($key)
    {
        return static::$settings[$key];
    }

    // default settings, mostly paths
    public static function loadSettings()
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
    }
}
