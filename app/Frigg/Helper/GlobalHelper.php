<?php

namespace Frigg\Helper;

use Frigg\Core\Exception\ErrorException;

class GlobalHelper extends BaseHelper
{
    public function isDevMode()
    {
    	return ($this->registry->getSetting('frigg/dev') === true);
    }
}
