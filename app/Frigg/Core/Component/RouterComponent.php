<?php

namespace Frigg\Core\Component;

use Frigg\Core\ClassPattern;
use Frigg\Core\Exception\RouterException;

defined('APP_TOKEN') or die('This file can not be called directly');

class RouterComponent extends BaseComponent
{
    protected $controller = null;
    protected $request = null;

    // parse request query
    public function parseRequest($queryString)
    {
        // split request string by delimiter into params
        $params = explode('&', $queryString);

        // first key is the controller
        $controllerName = trim(array_shift($params));
        $controllerName = (!$controllerName) ? 'index' : $controllerName;
        $controllerName = ClassPattern::snakeToCamel($controllerName);

        // read request into registry
        $request = array();
        if(count($params)) {
            foreach($params as $item) {
                list($key, $value) = explode('=', $item);
                $request[$key] = $value;
            }
        }

        $this->request = $request;
        $this->controller = $controllerName;
    }

    // execute controller and return response
    public function executeController()
    {
        // do we have a controller from the request?
        if(!is_string($this->controller)) {
            throw new RouterException('Bad request');
        }

        // default action
        if(!array_key_exists('action', $this->request)) {
            $this->request['action'] = 'index';
        }

        // namespace controller class and function
        $classPattern = sprintf('\%s\Controller\%sController', $this->registry->getSetting('frigg/app'), $this->controller);
        $functionPattern = sprintf('%sAction', strtolower($this->request['action']));

        // does the action exist?
        if(!method_exists($classPattern, $functionPattern)) {
            throw new RouterException(sprintf('Unknown action "%s" in "%s"', $functionPattern, $classPattern));
        }

        // execute controller, return response
        $controller = new $classPattern($this->registry);
        return $controller->$functionPattern($this->request);
    }
}
