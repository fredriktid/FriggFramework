<?php

namespace Frigg\Core\Components;

defined('APP_TOKEN') or die('This file can not be called directly');

class BaseComponent
{
	protected $instance; 

    public function __construct($instance)
    {
    	$this->instance = $instance;
    }

}
