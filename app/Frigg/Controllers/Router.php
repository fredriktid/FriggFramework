<?php

namespace Frigg\Controllers;

// fetch the request string
$request = $_SERVER['QUERY_STRING'];

// split data by separator
$data = explode('&', $request);

// first parameter is controller target
$controller = trim(array_shift($data));
$controller = (!$controller) ? 'Index' : \Frigg\Core\Registry::pattern($controller);

// extract GET-params from request
$params = array();
foreach($data as $item)
{
    list($key, $value) = explode('=', $item);
    $params[$key] = $value;
}

// set controller pattern
$controllerPattern = '\Frigg\Controllers\\' . $controller . 'Controller';

// set action pattern
$action = (array_key_exists('action', $params)) ? strtolower($params['action']) : 'index';
$actionPattern = $action . 'Action';

// is it reachable?
if(!method_exists($controllerPattern, $actionPattern))
{
    die(sprintf('404. Page not found.', $actionPattern));
}

// execute controller
$controller = new $controllerPattern;
return $controller->$actionPattern($params);
