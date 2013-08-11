<?php

namespace Frigg\Core;

use Frigg\Core\ClassPattern;
use Frigg\Core\Component;
use Frigg\Core\Exception\RegistryException;

// core registry
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

    /// public singleton used to access the unique instance of the registry
    public static function singleton()  
    {  
        // virgin instantiation
        if(is_null(static::$instance)) {
            static::$instance = new static;  
        }

        // set default settings in registry
        static::loadSettings();

        // return unique instance
        return static::$instance;
    }

    // get component from registry
    public function getComponent($key)  
    {
        // find component and return it
        if(array_key_exists($key, static::$components) && is_object(static::$components[$key])) {
            return static::$components[$key]; 
        }

        throw new RegistryException(sprintf('Component key "%s" not found in registry. Try setting it first.', $key));
    }
  
    // set a componenet in registry
    public function setComponent($key, $classIdentifier)  
    {        
        $classPattern = sprintf('\Frigg\Core\Component\%sComponent', ClassPattern::identifierToClass($classIdentifier));
        static::$components[$key] = new $classPattern(static::$instance);
        return $this;
    }

    // instantiate a helper class
    // returns a new object each time
    public function getHelper($classIdentifier)
    {
        $items = explode('_', $classIdentifier);
        if(2 > count($items)) {
            throw new RegistryException('Missing one or more identifiers in helper pattern (<app>_<helper>)');
        }

        $appName = ClassPattern::identifierToClass(trim(array_shift($items)));
        $className = ClassPattern::identifierToClass(trim(implode($items)));
        $classPattern = sprintf('\%s\Helper\%sHelper', $appName, $className);
        return new $classPattern(static::$instance);
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

    // default settings
    private function loadSettings()
    {
        static::$instance
            ->setSetting('frigg_app', APP_NAME)
            ->setSetting('frigg_dev', APP_DEV)
            ->setSetting('frigg_skin', APP_SKIN)
            ->setSetting('frigg_path', APP_PATH)
            ->setSetting('frigg_path_app', APP_PATH . '/app')
            ->setSetting('frigg_path_design', APP_PATH . '/design')
            ->setSetting('frigg_path_cache', APP_PATH . '/cache')
            ->setSetting('frigg_path_config', APP_PATH . '/config')
            ->setSetting('frigg_path_log', APP_PATH . '/log');
    }

}
