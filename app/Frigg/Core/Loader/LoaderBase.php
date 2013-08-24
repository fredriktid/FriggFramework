<?php

namespace Frigg\Core\Loader;

use Frigg\Core\RegistryInterface;

abstract class LoaderBase
{
    protected $instance = null;
    protected $registry = null;

    public function __construct(RegistryInterface $registry)
    {
        $this->instance = null;
        $this->registry = $registry;
    }

    public function getInstance()
    {
        return $this->instance;
    }
}
