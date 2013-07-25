<?php

namespace Frigg\Core\Components;

defined('APP_TOKEN') or die('This file can not be called directly');

class ConfigComponent extends BaseComponent
{
    public function load($config)
    {
    	$path = sprintf('%s/%s.php', APP_CONFIG, $config);
    	if(!is_readable($path))
    	{
    		return false;
    	}

    	return require_once $path;
    }
}