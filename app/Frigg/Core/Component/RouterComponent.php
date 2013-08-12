<?php

namespace Frigg\Core\Component;

use Frigg\Core\ClassPatternConverter;
use Frigg\Core\Exception\RouterException;

defined('APP_TOKEN') or die('This file can not be called directly');

class RouterComponent extends BaseComponent
{
    protected $controller = null;
    protected $request = null;

    // read read into object
    public function handle($query)
    {
        // split by separator
        $params = explode('&', $query);

        // first parameter is controller target
        $identifier = trim(array_shift($params));
        $this->controller = ($identifier ? ClassPatternConverter::identifierToClassString($identifier) : 'Index');

        // set parameters in request constant array
        $this->request = array();
        if(count($params)) {
            foreach($params as $item) {
                list($key, $value) = explode('=', $item);
                $this->request[$key] = $value;
            }
        }
    }

    // execute controller and return response
    public function execute()
    {
        // validate controller class
        if(is_null($this->controller) || !is_array($this->request)) {
            throw new RouterException('Unknown error in request parameters.');
        }

        // validate action
        if(!array_key_exists('action', $this->request)) {
            $this->request['action'] = 'index';
        }

        // namespace controller class and function
        $classPattern = sprintf('\%s\Controller\%sController', $this->registry->getSetting('frigg_app'), $this->controller);
        $functionPattern = sprintf('%sAction', strtolower($this->request['action']));

        // is it callable?
        if(!method_exists($classPattern, $functionPattern)) {
            throw new RouterException(sprintf('Unknown action: %s', $this->request['action']));
        }

        // remove action from request before execution
        unset($this->request['action']);

        // execute controller, return response
        $controller = new $classPattern($this->registry);
        return $controller->$functionPattern($this->request);
    }
}
