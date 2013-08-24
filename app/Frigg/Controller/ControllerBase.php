<?php

namespace Frigg\Controller;

use Frigg\Core\RegistryInterface;

abstract class ControllerBase
{
    protected $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }
}
