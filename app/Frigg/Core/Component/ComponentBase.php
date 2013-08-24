<?php

namespace Frigg\Core\Component;

use Frigg\Core\RegistryInterface;

abstract class ComponentBase
{
    protected $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function getClass()
    {
        return get_called_class();
    }
}
