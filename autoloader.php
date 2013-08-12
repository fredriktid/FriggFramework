<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

// custom autoloader
function friggAutoloader($class)
{
    // find filename to load by namespace pattern
    $filePath = __DIR__ . '/app/' . str_replace('\\', '/', $class) . '.php';
    if(is_readable($filePath)) {
        require_once $filePath;
    }
}

// register custom autoloader
spl_autoload_register('friggAutoloader');
