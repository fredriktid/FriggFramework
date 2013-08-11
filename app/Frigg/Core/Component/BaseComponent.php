<?php

namespace Frigg\Core\Component;

defined('APP_TOKEN') or die('This file can not be called directly');

class BaseComponent
{
    protected $registry; 

    public function __construct($registry)
    {
        $this->registry = $registry;
    }
}
