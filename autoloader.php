<?php

require_once __DIR__ . '/config.php';
require_once APP_ROOT . '/vendor/autoload.php';

// magical autoloader
function friggAutoloader($class)
{
    $filename = APP_HOME . '/' . str_replace('\\', '/', $class) . '.php';
    if(is_readable($filename))
    {
    	require_once $filename;
    }
}

spl_autoload_register('friggAutoloader');