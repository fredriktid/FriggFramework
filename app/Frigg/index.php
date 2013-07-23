<?php

use Frigg\Core;

// init registry instance
$registry = \Frigg\Core\Registry::singleton();

// default settings
$registry->setSetting('skin', 'default');
$registry->setSetting('tpl', 'twig');

// load core components
$registry->loadComponents();

// return response
$response = require_once __DIR__ . '/Controllers/Router.php';
return $response;
