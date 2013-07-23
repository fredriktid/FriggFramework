<?php

use Frigg\Core as App;

// init registry instance
$registry = App\Registry::singleton();

// default settings
$registry->setSetting('skin', 'default');
$registry->setSetting('tpl', 'twig');

// load core components
$registry->loadComponents();

// return response
$response = require_once __DIR__ . '/router.php';
return $response;
