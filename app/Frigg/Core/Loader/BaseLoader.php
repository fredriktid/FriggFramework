<?php

namespace Frigg\Core\Loader;

use Frigg\Core\Registry;
use Frigg\Core\Exception\LoaderException;

defined('APP_TOKEN') or die('This file can not be called directly');

class BaseLoader
{
    protected $registry = null;
    protected $instance = null;

    public function __construct(Registry $registry)
    {
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
        throw new LoaderException(sprintf('Empty environment in %s', __CLASS__));
    }
}
