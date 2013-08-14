<?php

namespace Frigg\Core\Component;

use Frigg\Core\Loader;
use Frigg\Core\ClassPattern;
use Frigg\Core\Exception\LoaderException;
use Frigg\Core\Exception\CoreException;

defined('APP_TOKEN') or die('This file can not be called directly');

// loader component
// resposible for integrating 3rd party
class LoaderComponent extends BaseComponent
{
    protected $loader = array();

    public function getLoader($identifier)
    {
        if(!isset($this->loader[$identifier])) {
            throw new CoreException(sprintf('Loader %s is not set in our registry', $identifier));
        }

        return $this->loader[$identifier];
    }

    public function setLoader($identifier)
    {
        list($appName, $className) = ClassPattern::identifierToClass($identifier);
        $loaderPattern = sprintf('\%s\Core\Loader\%sLoader', $appName, $className);

        try {
            $this->loader[$identifier] = new $loaderPattern($this->registry);
        } catch (LoaderException $e) {
            throw new LoaderException($e->getMessage(), 0, $e);
        } catch (\Exception $u) {
            throw new \Exception($u->getMessage(), 0, $u);
        }

        return $this;
    }
}
