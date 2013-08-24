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
            static::$instance = new static;
        }

        return static::$instance;
    }
}
