<?php

namespace Frigg\Core\Component;

use Frigg\Helper\PatternHelper;
use Frigg\Core\RegistryInterface;
use Frigg\Core\Component\HttpComponentInterface;

class RequestComponent extends ComponentBase implements ComponentInterface, RequestComponentInterface
{
    protected $controller  = null;
    protected $query = array();

    // parse request query
    public function parseRequest(HttpComponentInterface $http)
    {
        // split request string by delimiter into params
        $queryData = explode('&', $http->queryString());

        // first key is the controller
        $target = trim(array_shift($queryData));
        $target = (!$target) ? 'index' : $target;
        $this->controller = PatternHelper::snakeToCamel($target);

        // read request into registry
        $this->query = array();
        if(count($queryData)) {
            foreach($queryData as $item) {
                list($key, $value) = explode('=', $item);
                $this->query[$key] = $value;
            }
        }
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getQuery()
    {
        return $this->query;
    }
}
