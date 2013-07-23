<?php

namespace Frigg\Core\Components;

if(!defined('APP_TOKEN'))
{
    die('This file can not be called directly');
}

class BaseComponent
{
	protected $instance; 

    public function __construct($instance)
    {
    	$this->instance = $instance;
    }

}