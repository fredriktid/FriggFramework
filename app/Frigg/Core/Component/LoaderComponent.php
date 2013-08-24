<?php

namespace Frigg\Core\Component;

use Frigg\Core\Loader;
use Frigg\Core\RegistryPattern;
use Frigg\Core\Exception\ErrorException;

// loads 3rd party libraries
class LoaderComponent extends ComponentBase implements ComponentInterface, LoaderComponentInterface
{
    protected $vendors = array();

    public function getInstance($identifier)
    {
        if(!array_key_exists($identifier, $this->vendors)) {
            list($appName, $className) = RegistryPattern::identifierToClass($identifier);
            $className = sprintf('\%s\Core\Loader\%sLoader', $appName, $className);

            try {
                $loader = new $className($this->registry);
                $this->vendors[$identifier] = $loader->getInstance();
            } catch(\Exception $e) {
                throw $e;
            }
        }

        return $this->vendors[$identifier];
    }
}
