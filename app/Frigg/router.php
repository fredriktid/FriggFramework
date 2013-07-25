<?php

use Frigg\Core as App;
use Frigg\Controllers;

// init
$registry = App\Registry::singleton()
	->setSetting('skin', 'default')
	->setSetting('tpl', 'twig')
	->setDefaultComponents();

// get components
$template = $registry->getComponent('tpl')->getEngine('twig')->getEnviornment();
$logger = $registry->getComponent('log');

// fetch the request string
$query = $_SERVER['QUERY_STRING'];

// split data by separator
$data = explode('&', $query);

// first parameter is controller target
$controller = trim(array_shift($data));
$controller = (!$controller) ? 'Index' : App\Registry::pattern($controller);

// extract GET-params from request
$request = array();
foreach($data as $item) {
    list($key, $value) = explode('=', $item);
    $request[$key] = $value;
}

// set controller pattern
$controllerPattern = sprintf('\Frigg\Controllers\%sController', $controller);

// set action pattern
$action = (array_key_exists('action', $request)) ? strtolower($request['action']) : 'index';
$actionPattern = $action . 'Action';

// is it callable?
if(!method_exists($controllerPattern, $actionPattern)) {
	return $template->render('error.html.twig', array(
		'code' => 500,
		'error' => sprintf('Router: Action not found', $actionPattern, $controllerPattern)
	));
}

try {
	// execute controller
	$controller = new $controllerPattern;
	return $controller->$actionPattern($request);
} catch(\Exception $e) {
	$logger->setFile('error')->write($e->getMessage());
	return $template->render('error.html.twig', array(
		'code' => 500,
		'error' => sprintf('Exception: %s', $e->getMessage())
	));
}