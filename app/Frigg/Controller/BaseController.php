<?php

namespace Frigg\Controller;

use Frigg\Core\Exception\ControllerException;

class BaseController
{
    protected $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
    }
}
