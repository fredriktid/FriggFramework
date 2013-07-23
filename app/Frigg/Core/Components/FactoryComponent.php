<?php

namespace Frigg\Core\Components;

use Frigg\Core;
use Frigg\Core\Components;
use Frigg\Entity;
use Frigg\Controllers;

if(!defined('APP_TOKEN'))
{
    die('This file can not be called directly');
}

class FactoryComponent extends BaseComponent
{
    public function build($class)
    {
        return new $class;
    }
}