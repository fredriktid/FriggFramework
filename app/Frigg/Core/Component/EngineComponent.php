<?php

namespace Frigg\Core\Component;

use Frigg\Core\Engine;
use Frigg\Core\ClassPattern;
use Frigg\Core\Exception\EngineException;
use Frigg\Core\Exception\ComponentException;

defined('APP_TOKEN') or die('This file can not be called directly');

class EngineComponent extends BaseComponent
{
    protected $engines = array();

    public function getEngine($identifier)  
    {
        if(!isset($this->engines[$identifier])) {  
            throw new ComponentException(sprintf('Unknown engine identifier: %s. Try setting it first.', $identifier));
        }

        return $this->engines[$identifier]; 
    }
  
    public function setEngine($identifier)  
    {
        $enginePattern = sprintf('\Frigg\Core\Engine\%sEngine', ClassPattern::identifierToClass($identifier));
        
        try {
            $this->engines[$identifier] = new $enginePattern($this->registry);
        } catch (EngineException $e) {
            throw new ComponentException($e->getMessage(), 0, $e);
        }

        return $this;
    }
}
