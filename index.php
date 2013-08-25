<?php

use Frigg\Core\Registry;

require_once __DIR__ . '/autoloader.php';

try {
    // get registry instance
    $registry = Registry::singleton();

    // handle request
    $http = $registry->getComponent('frigg/http');
    $request = $registry->getComponent('frigg/request');
    $request->parseRequest($http);

    // print response
    $response = $registry->getComponent('frigg/response');
    $response->executeRouter($request);
    echo $response;
} catch(\Exception $e) {
    $error = $registry->getComponent('frigg/error');
    $error->exceptionHandler($e);
    echo $error;
}
