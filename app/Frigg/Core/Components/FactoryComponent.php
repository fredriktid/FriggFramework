<?php

namespace Frigg\Core\Components;

if(!defined('APP_TOKEN'))
{
    die('This file can not be called directly');
}

class FactoryComponent extends BaseComponent
{
    // fetch a specific object 
    public function build($class)
    {
        return new $class;
    }
}