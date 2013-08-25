<?php

namespace Frigg\Core\Component;

use Frigg\Helper\PatternHelper;
use Frigg\Core\Exception\RouterException;

class ResponseComponent extends ComponentBase implements ComponentInterface, ResponseComponentInterface
{
    protected $response = null;

    // print response
    public function __toString()
    {
        return (string) $this->response;
    }

    public function executeRouter(RequestComponentInterface $request)
    {
        // request data
        $controller = $request->getController();
        $query = $request->getQuery();

        // default action to "index"
        if(!array_key_exists('action', $query)) {
            $query['action'] = 'index';
        }

        // namespace of controller
        $classPattern = sprintf('\%s\Controller\%sController', APP_NAME, $controller);
        $functionPattern = sprintf('%sAction', strtolower($query['action']));

        // does the action exist?
        if(!method_exists($classPattern, $functionPattern)) {
            throw new RouterException('Unknown action');
        }

        try {
            // execute target controller
            // save response in object
            $controller = new $classPattern($this->registry);
            $this->response = $controller->$functionPattern($query);
        } catch(\Exception $e) {
            throw $e;
        }

        return $this;
    }
}
