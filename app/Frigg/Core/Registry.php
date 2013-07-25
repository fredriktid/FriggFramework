<?php

namespace Frigg\Core;

class Registry
{     
    // unique instance of registry
    private static $instance;  

    // array for our components
    private static $components = array();  
      
    // array of settings
    private static $settings = array(); 
      
    // private constructor to prevent user having more than once instance
    private function __construct()
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
        if(!isset(self::$instance)) {
            self::$instance = new self;  
        }  
          
        return self::$instance;
    }

    // get component from registry
    public function getComponent($comp)  
    {  
        if(is_object(self::$components[$comp])) {  
            return self::$components[$comp];  
        }

        return false;
    }
  
    // set a componenet in registry
    public function setComponent($key, $identifier)  
    {        
        $classPattern = sprintf('\Frigg\Core\Components\%sComponent', self::pattern($identifier));
        self::$components[$key] = new $classPattern(self::$instance);
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
        self::$settings[$key] = $value;
        return $this;
    }  
      
    // get setting from registry
    public function getSetting($key)  
    {  
        return self::$settings[$key];
    }
      
}  
