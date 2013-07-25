<?php

namespace Frigg\Core\Components;

defined('APP_TOKEN') or die('This file can not be called directly');

class TemplateComponent extends BaseComponent
{
    private static $engines = array();

    public function __construct($instance)
    {
        parent::__construct($instance);

        // default
        $this->setEngine('twig');
    }

    public function getEngine($engine)  
    {  
        if(!is_object(static::$engines[$engine])) {  
            throw new \Exception('Unknown engine');
        }

        return static::$engines[$engine]; 
    }
  
    public function setEngine($engine)  
    {
        $enginePattern = sprintf('\Frigg\Core\Components\TemplateEngines\%sEngine', $this->instance->pattern($engine));
        
        try {
            static::$engines[$engine] = new $enginePattern($this->instance);
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
