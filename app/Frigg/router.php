<?php

use Frigg\Core as App;
use Frigg\Controllers;

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
$controllerPattern = '\\Frigg\\Controllers\\' . $controller . 'Controller';

// set action pattern
$action = (array_key_exists('action', $request)) ? strtolower($request['action']) : 'index';
$actionPattern = $action . 'Action';

// is it callable?
if(!method_exists($controllerPattern, $actionPattern)) {
    die(sprintf('Router: Action not found', $actionPattern, $controllerPattern));
}

// execute controller
$controller = new $controllerPattern;
return $controller->$actionPattern($request);
