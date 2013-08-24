<?php

namespace Frigg\Core;

use Frigg\Core\Exception\CoreException;

abstract class RegistrySingleton
{
    protected static $instance = null;

    // not callable!
    // prevents users from having more than one instance
    final private function __construct()
    {}

    // prevent cloning
    // prevents users from having more than one instance
    final public function __clone()
    {
        throw new CoreException('Cloning the registry is not permitted');
    }

    // get singleton instance
    final public static function singleton()
    {
        if(is_null(static::$instance)) {
            // late static binding to allow static inheritance
            // another option would be to use get_called_class()
            static::$instance = new static;
            // set default settings in new registry instance
            call_user_func(array(static::$instance, 'defaultSettings'));
        }

        return static::$instance;
    }

    // a must-implement method for loading default settings
    // into the new registry object
    abstract public static function defaultSettings();
}
