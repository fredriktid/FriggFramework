<?php

namespace Frigg\Core;

use Frigg\Core\ClassPattern;
use Frigg\Core\Component;
use Frigg\Core\Exception\RegistryException;

defined('APP_TOKEN') or die('This file can not be called directly');

// core registry class
// using the singleton design pattern
class Registry
{
    private static $instance = null;
    private static $components = array();
    private static $settings = array();

    // private constructor to prevent user having more than once instance
    // use singleton() instead
    private function __construct() {}

    // prevent cloning of this object to ensure correct data
    public function __clone()
    {
        trigger_error('Cloning the registry is not permitted', E_USER_ERROR);
    }

    // public singleton used to access the unique instance of the registry
    public static function singleton()
    {
        // virgin instantiation
        if(is_null(static::$instance)) {
            static::$instance = new static;
        }

        // set default settings in registry
        static::setSettings();

        // return unique instance
        return static::$instance;
    }

    // get component from registry
    public function getComponent($identifier)
    {
        // find component and return it
        if(array_key_exists($identifier, static::$components) && is_object(static::$components[$identifier])) {
            return static::$components[$identifier];
        }

        throw new RegistryException(sprintf('Component key "%s" not found in registry. Try setting it first.', $identifier));
    }

    // set a component in registry
    public function setComponent($identifier)
    {
        list($appName, $className) = ClassPattern::identifierToClass($identifier);
        $classPattern = sprintf('\%s\Core\Component\%sComponent', $appName, $className);
        static::$components[$identifier] = new $classPattern(static::$instance);
        return $this;
    }

    // instantiate a helper class
    // returns a new object each time
    public function getHelper($identifier)
    {
        list($appName, $className) = ClassPattern::identifierToClass($identifier);
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
    private function setSettings()
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
