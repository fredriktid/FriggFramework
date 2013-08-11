<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

// magical autoloader
function friggAutoloader($class)
{
    $filename = __DIR__ . '/app/' . str_replace('\\', '/', $class) . '.php';
    if(is_readable($filename)) {
    	require_once $filename;
    }
}

spl_autoload_register('friggAutoloader');
