<?php

namespace Frigg\Helper;

class GlobalHelper extends BaseHelper
{
    public function isDevMode()
    {
    	return ($this->registry->getSetting('frigg_dev') === true);
    }
}
