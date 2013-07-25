<?php

namespace Frigg\Core;

class Registry
{     
    // unique instance of registry
    protected static $instance;  

    // array for our components
    protected static $components = array();  
      
    // array of settings
    protected static $settings = array(); 
      
    // protected constructor to prevent user having more than once instance
    protected function __construct()
    {

    }

    // prevent cloning of this object to ensure correct data
    public function __clone()  
    {  
        trigger_error('Cloning the registry is not permitted', E_USER_ERROR);  
    }

    /// public singleton method used to access the object
    public static function singleton()  
    {  
        if(!isset(static::$instance)) {
            static::$instance = new static;  
        }  
          
        return static::$instance;
    }

    // get component from registry
    public function getComponent($comp)  
    {  
        if(is_object(static::$components[$comp])) {  
            return static::$components[$comp];  
        }

        return false;
    }
  
    // set a componenet in registry
    public function setComponent($key, $identifier)  
    {        
        $classPattern = sprintf('\Frigg\Core\Components\%sComponent', static::pattern($identifier));
        static::$components[$key] = new $classPattern(static::$instance);
        return $this;
    }
     
    // load all default componenets in registry
    public function setDefaultComponents()
    {
        $this->setComponent('config', 'config');
        $this->setComponent('db', 'database');
        $this->setComponent('tpl', 'template');
        $this->setComponent('log', 'logger');

        return $this;
    }
    
    // converts snakecase to camelcase pattern
    public static function pattern($className)
    {
        return implode(array_map('ucfirst', explode('_', strtolower($className))));
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
      
}  
