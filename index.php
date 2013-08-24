<?php

use Frigg\Core\Registry;

require_once __DIR__ . '/autoloader.php';

// get singleton instance of registry
// and load required components
$registry = Registry::singleton()
    ->setComponent('frigg/config')
    ->setComponent('frigg/http')
    ->setComponent('frigg/logger')
    ->setComponent('frigg/router')
    ->setComponent('frigg/loader')
    ->setComponent('frigg/form');

// fetch necessary components from registry
$http = $registry->getComponent('frigg/http');
$router = $registry->getComponent('frigg/router');

// set default vendor in loader
$registry->getComponent('frigg/loader')->setLoader('frigg/doctrine');
$registry->getComponent('frigg/loader')->setLoader('frigg/twig');

// turn request into response
try {
    $router->parseRequest($http->queryString());
    echo $router->executeController();
} catch(\Exception $e) {
    $twig = $registry->getComponent('frigg/loader')->getLoader('frigg/twig')->setSkin('frigg')->getInstance();
    echo $twig->render('error/exception.html.twig', array(
        'exception' => sprintf('[%s] %s: %s', strftime('%F %T'), get_class($e), $e->getMessage()),
        'trace_string' => $e->getTraceAsString(),
    ));
}
