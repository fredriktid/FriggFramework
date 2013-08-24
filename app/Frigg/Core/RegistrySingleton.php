<?php

namespace Frigg\Core;

use Frigg\Core\Exception\CoreException;

abstract class RegistrySingleton
{
    protected static $instance = null;

    final private function __construct()
    {}

    final public function __clone()
    {
        throw new CoreException('Cloning the registry is not permitted');
    }

    final public static function singleton()
    {
        if(is_null(self::$instance)) {
            self::$instance = new static;
        }

        return self::$instance;
    }
}
