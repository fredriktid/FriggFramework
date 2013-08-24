<?php

namespace Frigg\Helper;

use Frigg\Core\Exception\ErrorException;

class GlobalHelper extends HelperBase
{
    public static function isDevMode()
    {
    	return (defined('APP_DEV') && APP_DEV === true);
    }
}
