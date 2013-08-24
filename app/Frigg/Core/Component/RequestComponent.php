<?php

namespace Frigg\Core\Component;

use Frigg\Core\RegistryPattern;
use Frigg\Core\RegistryInterface;
use Frigg\Core\Component\HttpComponentInterface;

class RequestComponent extends ComponentBase implements ComponentInterface, RequestComponentInterface
{
    protected $controller;
    protected $query;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry);

        $this->controller = null;
        $this->query = array();
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getQuery()
    {
        return $this->query;
    }

    // parse request query
    public function parseRequest(HttpComponentInterface $http)
    {
        // split request string by delimiter into params
        $queryData = explode('&', $http->queryString());

        // first key is the controller
        $target = trim(array_shift($queryData));
        $target = (!$target) ? 'index' : $target;
        $this->controller = RegistryPattern::snakeToCamel($target);

        // read request into registry
        $this->query = array();
        if(count($queryData)) {
            foreach($queryData as $item) {
                list($key, $value) = explode('=', $item);
                $this->query[$key] = $value;
            }
        }
    }
}
