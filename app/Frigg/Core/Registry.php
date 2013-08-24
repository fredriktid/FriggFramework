<?php

namespace Frigg\Core;

use Frigg\Helper\PatternHelper;
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
        list($appName, $className) = PatternHelper::identifierToClass($identifier);
        $classPattern = sprintf('\%s\Core\Component\%sComponent', $appName, $className);

        try {
            static::$components[$identifier] = new $classPattern(static::$instance);
        } catch(\Exception $e) {
            throw $e;
        }

        return static::$instance;
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
    public static function defaultSettings()
    {
        static::$instance
            ->setSetting('path/app', APP_PATH . '/app')
            ->setSetting('path/design', APP_PATH . '/design')
            ->setSetting('path/cache', APP_PATH . '/cache')
            ->setSetting('path/config', APP_PATH . '/config')
            ->setSetting('path/log', APP_PATH . '/log');
    }
}
