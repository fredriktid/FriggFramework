<?php

namespace Frigg\Core\Component;

use Frigg\Core\RegistryPattern;
use Frigg\Core\Exception\RoutingException;

class ResponseComponent extends ComponentBase implements ComponentInterface, ResponseComponentInterface
{
    protected $response = null;

    public function __toString()
    {
        return (string) $this->response;
    }

    public function executeRouter(RequestComponent $request)
    {
        // default controller
        $controller = $request->getController();
        if(!$controller) {
            $controller = 'index';
        }

        // default action
        $query = $request->getQuery();
        if(!array_key_exists('action', $query)) {
            $query['action'] = 'index';
        }

        // namespace controller class and function
        $classPattern = sprintf('\%s\Controller\%sController', $this->registry->getSetting('frigg/app'), $controller);
        $functionPattern = sprintf('%sAction', strtolower($query['action']));

        // does the action exist?
        if(!method_exists($classPattern, $functionPattern)) {
            throw new RoutingException('Unknown action');
        }

        try {
            $controller = new $classPattern($this->registry);
            $this->response = $controller->$functionPattern($query);
        } catch(\Exception $e) {
            throw $e;
        }
    }
}
