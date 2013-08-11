<?php

namespace Frigg\Core\Engine;

use Frigg\Core\Exception\EngineException;

defined('APP_TOKEN') or die('This file can not be called directly');

class BaseEngine
{
    protected $registry;
    protected $instance;

    public function __construct($registry)
    {
        $this->instance = null;
        $this->registry = $registry;
    }

    public function getInstance()
    {
        $this->setEnvironmentInstance();
        return $this->instance;
    }

    protected function setEnvironmentInstance()
    {
        $this->instance = $this->getEnvironment();
    }

    protected function getEnvironment()
    {
        throw new EngineException(sprintf('Empty environment in %s', __CLASS__));
    }
}
