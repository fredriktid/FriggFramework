<?php

use Frigg\Core\Registry;
use Frigg\Core\ClassPatternConverter;
use Frigg\Core\Exception\RouterException;
use Frigg\Core\Exception\RegistryException;
use Frigg\Core\Exception\ControllerException;

require_once __DIR__ . '/autoloader.php';

try {
    // get virgin instance of registry
    // and load required components
    $registry = Registry::singleton()
        ->setComponent('config', 'config')
        ->setComponent('http', 'http')
        ->setComponent('log', 'logger')
        ->setComponent('router','router')
        ->setComponent('engine', 'engine');

    // load desired vendor engines
    $registry->getComponent('engine')->setEngine('doctrine');
    $registry->getComponent('engine')->setEngine('twig');

    // get some components to use now
    $http = $registry->getComponent('http');
    $router = $registry->getComponent('router');

    try {
        $router->handle($http->queryString());
        echo $router->execute();
    } catch(\Exception $e) {
        $tpl = $registry->getComponent('engine')->getEngine('twig')->setSkin('frigg')->getInstance();
        echo $tpl->render('error/exception.html.twig', array(
            'exception' => sprintf('[%s] %s: %s', strftime('%F %T'), get_class($e), $e->getMessage())
        ));
    }
} catch(\Exception $e) {
    printf('[%s] Registry error in %s: "%s"', strftime('%F %T'), get_class($e), $e->getMessage());
}
